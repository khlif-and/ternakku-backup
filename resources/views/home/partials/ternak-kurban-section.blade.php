<style>
    /* STYLE ANDA (Keyframe)
      Saya tidak mengubah ini sama sekali, sesuai permintaan Anda.
    */
    @keyframes orbit-1 {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    @keyframes orbit-2 {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(-360deg);
        }
    }

    @keyframes orbit-3 {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    /* Counter-rotation (Keyframe Anda) */
    @keyframes counter-1 {
        from {
            transform: translate(-50%, -50%) rotate(0deg);
        }

        to {
            transform: translate(-50%, -50%) rotate(-360deg);
        }
    }

    @keyframes counter-2 {
        from {
            transform: translate(-50%, -50%) rotate(0deg);
        }

        to {
            transform: translate(-50%, -50%) rotate(360deg);
        }
    }

    @keyframes counter-2-bottom {
        from {
            transform: translate(-50%, 50%) rotate(0deg);
        }

        to {
            transform: translate(-50%, 50%) rotate(360deg);
        }
    }

    @keyframes counter-3 {
        from {
            transform: translate(50%, -50%) rotate(0deg);
        }

        to {
            transform: translate(50%, -50%) rotate(-360deg);
        }
    }

    /* Class Animasi (Keyframe Anda) */
    .orbit-1 {
        animation: orbit-1 80s linear infinite;
    }

    .orbit-2 {
        animation: orbit-2 100s linear infinite;
    }

    .orbit-3 {
        animation: orbit-3 120s linear infinite;
    }

    .counter-spin-1 {
        animation: counter-1 80s linear infinite;
    }

    .counter-spin-2 {
        animation: counter-2 100s linear infinite;
    }

    .counter-spin-2-bottom {
        animation: counter-2-bottom 100s linear infinite;
    }

    .counter-spin-3 {
        animation: counter-3 120s linear infinite;
    }
</style>

<section class="w-full py-24 px-6 bg-white text-gray-900 overflow-hidden">
    <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 items-center gap-16">

        <div>
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 leading-tight mb-8">
                Unlock Top Marketing Talent You Thought Was Out of Reach –<br /> Now Just One Click Away!
            </h2>
            <a href="#"
                class="inline-flex items-center gap-2 bg-black text-white px-6 py-3 rounded-full hover:bg-gray-800 transition font-semibold text-base shadow-lg">
                Start Project
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>

        <div class="relative w-full flex justify-center min-h-[400px] md:min-h-[450px] items-center">

            <div class="absolute -top-10 -right-10 w-72 h-72 bg-purple-100/50 rounded-full blur-[120px] opacity-80">
            </div>
            <div class="absolute -bottom-10 -left-10 w-72 h-72 bg-blue-100/50 rounded-full blur-[100px] opacity-70">
            </div>

            <div class="relative z-10 text-center">
                <h3 class="text-5xl md:text-6xl font-bold text-gray-900">20k+</h3>
                <p class="text-gray-600 text-lg">Specialists</p>
            </div>

            <div
                class="absolute inset-0 m-auto w-[220px] h-[220px] sm:w-[250px] sm:h-[250px] rounded-full border border-gray-200 orbit-1">
                <div class="counter-spin-1 absolute w-12 h-12 rounded-lg bg-white shadow-lg border border-gray-100 flex items-center justify-center transition-transform duration-300 hover:scale-110"
                    style="top: 0; left: 0;">
                    <svg class="w-7 h-7 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"></path>
                    </svg>
                </div>
                <div class="counter-spin-1 absolute w-12 h-12 rounded-lg bg-white shadow-lg border border-gray-100 flex items-center justify-center transition-transform duration-300 hover:scale-110"
                    style="top: 50%; left: 0;">
                    <svg class="w-7 h-7 text-blue-500" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 10l3 3-3 3"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 16h5"></path>
                    </svg>
                </div>
            </div>

            <div
                class="absolute inset-0 m-auto w-[300px] h-[300px] sm:w-[350px] sm:h-[350px] rounded-full border border-gray-200 orbit-2">
                <div class="counter-spin-2 absolute w-12 h-12 rounded-full bg-cover bg-center border border-gray-200 shadow-md transition-transform duration-300 hover:scale-110"
                    style="top: 0; left: 50%; background-image: url('https://i.pravatar.cc/150?img=1');"></div>
                <div class="counter-spin-2-bottom absolute w-12 h-12 rounded-full bg-cover bg-center border border-gray-200 shadow-md transition-transform duration-300 hover:scale-110"
                    style="bottom: 0; left: 50%; background-image: url('https://i.pravatar.cc/150?img=2');"></div>
            </div>

            <div
                class="absolute inset-0 m-auto w-[380px] h-[380px] sm:w-[450px] sm:h-[450px] rounded-full border border-gray-200 orbit-3">
                <div class="counter-spin-3 absolute w-12 h-12 rounded-full bg-cover bg-center border border-gray-200 shadow-md transition-transform duration-300 hover:scale-110"
                    style="top: 50%; right: 0; background-image: url('https://i.pravatar.cc/150?img=3');"></div>
            </div>

        </div>
    </div>
</section>
