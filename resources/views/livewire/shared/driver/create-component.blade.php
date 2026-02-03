<div>
    <x-alert.session />

    <div class="bg-white rounded-lg shadow-sm border p-6">
        <form wire:submit.prevent="save" class="space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-form.input wire:model="name" name="name" label="Nama" placeholder="Masukkan nama pengemudi" />

                <x-form.input wire:model="email" name="email" type="email" label="Email" placeholder="Masukkan email" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-form.input wire:model="phone_number" name="phone_number" label="No. HP"
                    placeholder="Masukkan nomor HP" />

                <x-form.input wire:model="password" name="password" type="password" label="Password"
                    placeholder="Masukkan password" />
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t">
                <x-button.link href="{{ route('shared.driver.index', $farm->id) }}" color="gray">
                    Batal
                </x-button.link>
                <x-button.primary type="submit">
                    Simpan
                </x-button.primary>
            </div>
        </form>
    </div>
</div>