<?php

namespace App\Livewire\Admin\Customer;

use Livewire\Component;
use App\Models\Farm;
use App\Models\Region;
use App\Models\QurbanCustomer;
use App\Services\Web\Farming\Customer\CustomerCoreService;
use Illuminate\Support\Facades\Log;

class EditComponent extends Component
{
    public Farm $farm;
    public QurbanCustomer $customer;

    public $name;
    public $phone;
    public $email;
    public $addresses = [];

    public $regions = [];

    protected function rules()
    {
        return [
            'name' => 'required|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'addresses' => 'array',
            'addresses.*.id' => 'nullable|integer',
            'addresses.*.name' => 'required_with:addresses.*.address_line|string',
            'addresses.*.description' => 'nullable|string',
            'addresses.*.region_id' => 'required_with:addresses.*.name|exists:regions,id',
            'addresses.*.postal_code' => 'nullable|string',
            'addresses.*.address_line' => 'required_with:addresses.*.name|string',
            'addresses.*.longitude' => 'nullable|string',
            'addresses.*.latitude' => 'nullable|string',
        ];
    }

    protected $messages = [
        'name.required' => 'Nama wajib diisi.',
    ];

    public function mount(Farm $farm, QurbanCustomer $customer)
    {
        $this->farm = $farm;
        $this->customer = $customer;
        // Removed Region::orderBy('name')->get(); to prevent lag
        
        $this->fillFormData();
    }

    public function fillFormData()
    {
        $this->name = $this->customer->user->name;
        $this->phone = $this->customer->user->phone_number;
        $this->email = $this->customer->user->email;

        $this->addresses = $this->customer->addresses->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'description' => $item->description,
                'region_id' => $item->region_id,
                'region_search' => $item->region ? $item->region->name : '', // Pre-fill search
                'postal_code' => $item->postal_code,
                'address_line' => $item->address_line,
                'longitude' => $item->longitude,
                'latitude' => $item->latitude,
            ];
        })->toArray();

        if (empty($this->addresses)) {
            $this->addAddress();
        }
    }

    public function addAddress()
    {
        $this->addresses[] = [
            'name' => '',
            'description' => '',
            'region_id' => null,
            'region_search' => '',
            'postal_code' => '',
            'address_line' => '',
            'longitude' => '',
            'latitude' => '',
        ];
    }

    public function removeAddress($index)
    {
        if (count($this->addresses) > 1) {
            unset($this->addresses[$index]);
            $this->addresses = array_values($this->addresses);
        }
    }

    public function save(CustomerCoreService $coreService)
    {
        $this->validate();

        try {
            $coreService->updateCustomer($this->customer->id, [
                'name' => $this->name,
                'phone' => $this->phone,
                'email' => $this->email,
            ]);

            $existingIds = $this->customer->addresses->pluck('id')->toArray();
            $submittedIds = [];

            foreach ($this->addresses as $addressData) {
                // Remove temp search field
                $dataToStore = $addressData;
                unset($dataToStore['region_search']);

                // Fix: Convert empty strings to null for decimal columns
                $dataToStore['latitude'] = empty($dataToStore['latitude']) ? null : $dataToStore['latitude'];
                $dataToStore['longitude'] = empty($dataToStore['longitude']) ? null : $dataToStore['longitude'];

                if (isset($addressData['id']) && $addressData['id']) {
                    $coreService->updateAddress($addressData['id'], $dataToStore);
                    $submittedIds[] = $addressData['id'];
                } else {
                    $coreService->storeAddress($this->farm, $this->customer->id, $dataToStore);
                }
            }

            $idsToDelete = array_diff($existingIds, $submittedIds);
            foreach ($idsToDelete as $id) {
                $coreService->deleteAddress($id);
            }

            session()->flash('success', 'Data customer berhasil diperbarui.');
            return redirect()->route('admin.care-livestock.customer.index', $this->farm->id);

        } catch (\Throwable $e) {
            Log::error('Customer Edit Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $availableRegions = [];

        foreach ($this->addresses as $index => $address) {
            $search = $address['region_search'] ?? '';

            if (strlen($search) > 1) {
                 $availableRegions[$index] = Region::where('name', 'like', '%' . $search . '%')
                    ->orderBy('name')
                    ->limit(30)
                    ->get();
            } elseif (isset($address['region_id']) && $address['region_id']) {
                 $availableRegions[$index] = Region::where('id', $address['region_id'])->get();
            } else {
                 $availableRegions[$index] = collect([]); 
            }
        }

        return view('livewire.admin.customer.edit-component', [
            'availableRegions' => $availableRegions
        ]);
    }
}