<nav role="navigation" aria-label="Pagination Navigation" class="flex w-full items-center justify-center">

    <div class="inline-flex items-stretch rounded-full bg-white shadow-sm ring-1 ring-inset ring-gray-200">

        <div class="flex items-center px-2">
            @if ($paginator->onFirstPage())
                <span class="p-2 text-gray-400 cursor-default">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z"
                            clip-rule="evenodd" />
                    </svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                    class="p-2 text-gray-500 rounded-full hover:bg-gray-100 focus:z-10 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z"
                            clip-rule="evenodd" />
                    </svg>
                </a>
            @endif

            <div class="hidden sm:flex items-center px-2">
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="px-2 py-1 text-sm text-gray-400">{{ $element }}</span>
                    @endif
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page"
                                    class="flex items-center justify-center w-8 h-8 text-sm font-bold text-blue-600 bg-blue-100 rounded-full">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}"
                                    class="flex items-center justify-center w-8 h-8 text-sm text-gray-600 rounded-full hover:bg-gray-100 focus:z-10 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                    class="p-2 text-gray-500 rounded-full hover:bg-gray-100 focus:z-10 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                            clip-rule="evenodd" />
                    </svg>
                </a>
            @else
                <span class="p-2 text-gray-400 cursor-default">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                            clip-rule="evenodd" />
                    </svg>
                </span>
            @endif
        </div>

        <div class="border-l border-gray-200">
            <div x-data="{ open: false }" class="relative flex-shrink-0">
                <button @click="open = !open" type="button"
                    class="flex items-center justify-between text-sm text-gray-700 rounded-r-full w-32 px-4 py-2 transition-colors hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <span>{{ request('per_page', 10) }} / page</span>
                    <svg class="w-4 h-4 ml-2 text-gray-500 transform transition-transform duration-200"
                        :class="{ 'rotate-180': open }" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                            clip-rule="evenodd" />
                    </svg>
                </button>

                <div x-show="open" @click.away="open = false" x-transition
                    class="absolute right-0 z-10 bottom-full mb-2 w-32 origin-bottom-right bg-white rounded-xl shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                    style="display: none;">
                    <div class="py-1 divide-y divide-gray-100">
                        @foreach ([10, 25, 50, 100] as $size)
                            <a href="{{ url()->current() . '?' . http_build_query(array_merge(request()->except(['page', 'per_page']), ['per_page' => $size])) }}"
                                class="block px-4 py-2 text-sm text-center {{ request('per_page', 10) == $size ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                                {{ $size }} / page
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

    </div>
</nav>
