<?php

namespace App\Livewire\Admin\Customer;

use Livewire\Component;
use App\Models\Farm;
use App\Models\Region;
use App\Services\Web\Farming\Customer\CustomerCoreService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CreateComponent extends Component
{
    public Farm $farm;

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

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
        $this->addAddress();
    }

    public function addAddress()
    {
        $this->addresses[] = [
            'name' => '',
            'description' => '',
            'region_id' => null,
            'region_search' => '', // Add search term
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
            $customer = $coreService->storeCustomer($this->farm, [
                'name' => $this->name,
                'phone' => $this->phone,
                'email' => $this->email,
            ], Auth::id());

            foreach ($this->addresses as $addressData) {
                if (!empty($addressData['name']) && !empty($addressData['address_line'])) {
                    // Ensure we don't pass the search field to the service
                    $dataToStore = $addressData;
                    unset($dataToStore['region_search']); 

                    // Fix: Convert empty strings to null for decimal columns
                    $dataToStore['latitude'] = empty($dataToStore['latitude']) ? null : $dataToStore['latitude'];
                    $dataToStore['longitude'] = empty($dataToStore['longitude']) ? null : $dataToStore['longitude'];

                    $coreService->storeAddress($this->farm, $customer->id, $dataToStore);
                }
            }

            session()->flash('success', 'Data customer berhasil ditambahkan.');
            return redirect()->route('admin.care-livestock.customer.index', $this->farm->id);

        } catch (\Throwable $e) {
            Log::error('Customer Create Error', [
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
            // Find regions based on search term for this specific row
            // If search is empty, show nothing or limited set? Better show filtered only to avoid lag
            $search = $address['region_search'] ?? '';

            if (strlen($search) > 1) {
                 $availableRegions[$index] = Region::where('name', 'like', '%' . $search . '%')
                    ->orderBy('name')
                    ->limit(30)
                    ->get();
            } elseif ($address['region_id']) {
                 // If ID selected but no search, show the selected one so it doesn't look empty
                 $availableRegions[$index] = Region::where('id', $address['region_id'])->get();
            } else {
                 $availableRegions[$index] = collect([]); // Empty if no search
            }
        }

        return view('livewire.admin.customer.create-component', [
            'availableRegions' => $availableRegions
        ]);
    }
}