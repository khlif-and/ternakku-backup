<?php

namespace App\Livewire\Shared\Driver;

use Livewire\Component;
use App\Models\Farm;
use App\Services\Web\Shared\Driver\DriverCoreService;

class EditComponent extends Component
{
    public Farm $farm;
    public $driverId;

    public $name;
    public $email;
    public $phone_number;
    public $password;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6',
        ];
    }

    protected $messages = [
        'name.required' => 'Nama wajib diisi.',
        'password.min' => 'Password minimal 6 karakter.',
    ];

    public function mount(Farm $farm, $id, DriverCoreService $service)
    {
        $this->farm = $farm;
        $this->driverId = $id;

        $farmUser = $service->get($farm->id, $id);
        $user = $farmUser->user;

        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone_number = $user->phone_number;
    }

    public function save(DriverCoreService $service)
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'phone_number' => $this->phone_number,
            'password' => $this->password,
        ];

        $response = $service->update($this->farm->id, $this->driverId, $data);

        if ($response['error']) {
            session()->flash('error', $response['message'] ?? 'Gagal mengupdate pengemudi.');
            return;
        }

        session()->flash('success', 'Pengemudi berhasil diperbarui.');
        return redirect()->route('shared.driver.index', $this->farm->id);
    }

    public function render()
    {
        return view('livewire.shared.driver.edit-component');
    }
}
