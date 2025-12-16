<div class="px-4 pt-6 pb-2 text-center">
    <a href="{{ route('admin.care-livestock.dashboard', ['farm_id' => $farm->id]) }}"
        class="flex justify-center w-full">
        <span
            class="font-bold text-white text-base font-[Oleo_Script,cursive] tracking-wide sidebar-label text-center block"
            style="font-family:'Oleo Script',cursive;">
            {{ $farm->name ?? 'Your Farm' }}
        </span>
    </a>
</div>
