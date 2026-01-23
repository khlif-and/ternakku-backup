<div>
    @if (session('success'))
        <x-alert.flash type="success">{{ session('success') }}</x-alert.flash>
    @endif

    @if (session('error'))
        <x-alert.flash type="error">{{ session('error') }}</x-alert.flash>
    @endif
</div>
