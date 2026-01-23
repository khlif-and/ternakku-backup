<section class="grid grid-cols-1 gap-6 p-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5" style="perspective: 1000px;">
    <x-menu.card 
        :href="url('select-farm?redirect_url=care-livestock/dashboard')"
        label="Pelihara Ternak"
        :icon="asset('home/assets/icons/ic_pelihara_ternak.png')"
        color="emerald"
    />

    <x-menu.card 
        href="#"
        label="Pakan & Keswan"
        :icon="asset('home/assets/icons/ic_pelihara_ternak.png')"
        color="emerald"
    />

    <x-menu.card 
        :href="route('livestock_outlet.dashboard')"
        label="Outlet Ternak"
        :icon="asset('home/assets/icons/ic_pelihara_ternak.png')"
        color="blue"
    />

    <x-menu.card 
        href="#"
        label="Usaha Ternak"
        :icon="asset('home/assets/icons/ic_pelihara_ternak.png')"
        color="emerald"
    />

    <x-menu.card 
        :href="url('select-farm?redirect_url=qurban/dashboard')"
        label="Ternak Kurban"
        :icon="asset('home/assets/icons/ic_pelihara_ternak.png')"
        color="emerald"
    />
</section>
