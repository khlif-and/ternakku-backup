<x-modal.confirm 
    title="Konfirmasi Logout"
    message="Anda yakin ingin mengakhiri sesi Anda saat ini?"
    confirmText="Ya, Logout"
    cancelText="Batal"
    :confirmAction="route('logout')"
    icon="logout"
    :danger="true"
/>
