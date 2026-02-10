<?php

namespace App\Livewire\Shared\Driver;

use Livewire\Component;
use App\Models\Farm;
use App\Services\Web\Shared\Driver\DriverCoreService;

class CreateComponent extends Component
{
    public Farm $farm;

    public $name;
    public $email;
    public $phone_number;
    public $password;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'nullable|string|max:20',
            'password' => 'required|string|min:6',
        ];
    }

    protected $messages = [
        'name.required' => 'Nama wajib diisi.',
        'email.required' => 'Email wajib diisi.',
        'email.email' => 'Format email tidak valid.',
        'password.required' => 'Password wajib diisi.',
        'password.min' => 'Password minimal 6 karakter.',
    ];

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
    }

    public function save(DriverCoreService $service)
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'password' => $this->password,
        ];

        $response = $service->store($this->farm->id, $data);

        if ($response['error']) {
            session()->flash('error', $response['message'] ?? 'Gagal menambahkan pengemudi.');
            return;
        }

        session()->flash('success', 'Pengemudi berhasil ditambahkan.');
        return redirect()->route('shared.driver.index', $this->farm->id);
    }

    public function render()
    {
        return view('livewire.shared.driver.create-component');
    }
}
