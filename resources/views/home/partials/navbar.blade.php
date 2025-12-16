<nav id="navbar"
    x-data="{
        isOpen: false,
        navbarState: 1, // 1=Transparan, 2=Blur, 3=Solid

        checkScroll() {
            const scrollY = window.scrollY;
            const heroBanner = document.getElementById('hero-banner');
            const blurTriggerPoint = 10;

            if (!heroBanner) {
                // Fallback (jika di halaman non-hero)
                this.navbarState = scrollY > blurTriggerPoint ? 3 : 1;
                return;
            }

            const heroTriggerPoint = heroBanner.offsetHeight - this.$el.offsetHeight;

            // Jika offsetHeight masih 0 (sangat tidak mungkin terjadi saat scroll,
            // tapi untuk jaga-jaga)
            if (heroTriggerPoint <= 0) {
                 this.navbarState = scrollY > blurTriggerPoint ? 3 : 1;
                 return;
            }

            // Logika Normal
            if (scrollY > heroTriggerPoint) {
                this.navbarState = 3; // Solid
            } else if (scrollY > blurTriggerPoint) {
                this.navbarState = 2; // Blur
            } else {
                this.navbarState = 1; // Transparan
            }
        }
    }"
    {{--
      PERBAIKAN: x-init="checkScroll()" DIHAPUS
      State awal (navbarState: 1) sudah benar (Transparan).
      checkScroll() hanya akan jalan saat @scroll.
    --}}
    @scroll.window.throttle.100ms="checkScroll()"
    class="fixed top-0 left-0 right-0 z-30 w-full text-gray-900 transition-all duration-500 ease-in-out"
    :class="{
        'bg-white shadow-md backdrop-blur-none': navbarState === 3,
        'bg-white/80 backdrop-blur-md shadow-sm': navbarState === 2,
        'bg-transparent backdrop-blur-none shadow-none': navbarState === 1
    }">

    <div class="flex items-center transition-all duration-500 ease-in-out px-4 md:px-8 py-4 nav-inner">
        <div class="flex items-center space-x-5">
            <div class="w-9 h-9 flex items-center justify-center rounded-lg bg-black">
                <svg width="24" height="24" viewBox="0 0 24" fill="none">
                    <rect width="24" height="24" rx="5" fill="black" />
                    <path d="M7 17L17 7" stroke="white" stroke-width="2.5" stroke-linecap="round" />
                </svg>
            </div>
            <span id="nav-brand" class="text-xl font-bold">Ternakku</span>
        </div>

        {{-- Tombol Hamburger dengan Alpine --}}
        <button @click="isOpen = !isOpen" class="ml-auto md:hidden text-2xl p-2 focus:outline-none" aria-label="Open menu">
            {{-- Ikon Hamburger (tampil saat isOpen false) --}}
            <svg x-show="!isOpen" xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-width="2" d="M4 7h16M4 12h16M4 17h16" />
            </svg>
            {{-- Ikon Close (tampil saat isOpen true) --}}
            <svg x-show="isOpen" xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" style="display: none;">
                <path stroke-linecap="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <ul class="hidden md:flex flex-1 justify-center items-center space-x-8 text-base">
            <li><a href="#" class="nav-link font-bold text-black ml-8">Beranda</a></li>
            <li><a href="#" class="nav-link font-semibold text-gray-700 hover:text-black">Tentang Aplikasi</a></li>
            <li><a href="#" class="nav-link font-semibold text-gray-700 hover:text-black">Fitur Kamu</a></li>
        </ul>

        <a id="nav-login-btn" href="{{ url('auth/login') }}"
            class="hidden md:inline-block ml-8 font-semibold px-6 py-2 bg-black text-white rounded-full hover:bg-gray-800 transition text-base">
            Login Ternakku
        </a>
    </div>

    {{-- Menu Mobile dengan Alpine (Sudah benar dengan bg-white) --}}
    <div x-show="isOpen"
        @click.away="isOpen = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="w-full px-8 pb-4 md:hidden bg-white shadow-lg"
        style="display: none;">
        <ul class="flex flex-col space-y-3">
            <li><a href="#" class="nav-link-mobile block py-2 font-bold text-black">Beranda</a></li>
            <li><a href="#" class="nav-link-mobile block py-2 font-semibold text-gray-700 hover:text-black">Tentang Aplikasi</a></li>
            <li><a href="#" class="nav-link-mobile block py-2 font-semibold text-gray-700 hover:text-black">Fitur Kamu</a></li>
            <li>
                <a id="nav-login-btn-mobile" href="{{ url('auth/login') }}"
                    class="block w-full text-center mt-2 font-semibold px-6 py-2 bg-black text-white rounded-full hover:bg-gray-800 transition text-base">
                    Login TernakRku
                </a>
            </li>
        </ul>
    </div>
</nav>
