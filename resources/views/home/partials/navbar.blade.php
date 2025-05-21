<nav id="navbar" class="fixed top-0 left-0 right-0 z-30 w-full transition-all duration-500 ease-in-out bg-transparent">
    <div class="flex items-center transition-all duration-500 ease-in-out px-4 md:px-8 py-4 nav-inner">
        <!-- Logo & Brand -->
        <div class="flex items-center space-x-5">
            <div class="w-9 h-9 flex items-center justify-center rounded-lg bg-black">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <rect width="24" height="24" rx="5" fill="black" />
                    <path d="M7 17L17 7" stroke="white" stroke-width="2.5" stroke-linecap="round" />
                </svg>
            </div>
            <span class="text-xl font-bold">Ternakku</span>
        </div>
        <!-- Hamburger (Mobile only) -->
        <button class="ml-auto md:hidden text-2xl p-2 focus:outline-none" aria-label="Open menu">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-width="2" d="M4 7h16M4 12h16M4 17h16" />
            </svg>
        </button>
        <!-- Menu Desktop -->
        <ul class="hidden md:flex flex-1 justify-center items-center space-x-8 text-base">
            <li><a href="#" class="font-bold text-black ml-8">Beranda</a></li>
            <li><a href="#" class="font-semibold text-gray-700 hover:text-black">Tentang Aplikasi</a></li>
            <li><a href="#" class="font-semibold text-gray-700 hover:text-black">Fitur Kamu</a></li>
        </ul>
        <a href="{{ url('auth/login') }}"
            class="hidden md:inline-block ml-8 font-semibold px-6 py-2 bg-black text-white rounded-full shadow hover:bg-gray-900 transition text-base">
            Login Ternakku
        </a>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const navbar = document.getElementById('navbar');
        const navInner = navbar.querySelector('.nav-inner');
        let isSolid = false;

        window.addEventListener('scroll', function() {
            const scrolled = window.scrollY > 20;

            if (scrolled && !isSolid) {
                navbar.classList.remove('bg-transparent');
                navbar.classList.add('bg-white', 'shadow-md');
                navInner.classList.add('py-3');
                navInner.classList.remove('py-4');
                isSolid = true;
            } else if (!scrolled && isSolid) {
                navbar.classList.remove('bg-white', 'shadow-md');
                navbar.classList.add('bg-transparent');
                navInner.classList.remove('py-3');
                navInner.classList.add('py-4');
                isSolid = false;
            }
        });
    });
</script>
