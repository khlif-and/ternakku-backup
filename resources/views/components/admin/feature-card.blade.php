@props(['title', 'backUrl' => null, 'breadcrumbs' => [], 'actions' => []])



<div class="p-6">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <p class="text-gray-700 text-lg mb-3 font-semibold">[ {{ $title }} ]</p>
            <ul class="flex items-center text-sm space-x-2 text-gray-500 mb-4">
                @foreach($breadcrumbs as $crumb)
                    <li>
                        @if(isset($crumb['route']))
                            <a href="{{ $crumb['route'] }}" class="hover:text-blue-600">
                                @if(isset($crumb['icon'])) <i class="{{ $crumb['icon'] }}"></i> @endif
                                {{ $crumb['label'] ?? '' }}
                            </a>
                        @else
                            @if(isset($crumb['icon'])) <i class="{{ $crumb['icon'] }}"></i> @endif
                            {{ $crumb['label'] ?? '' }}
                        @endif
                    </li>
                    @if(!$loop->last)
                        <li><i class="icon-arrow-right"></i></li>
                    @endif
                @endforeach
            </ul>
        </div>
        @if(!empty($actions))
            <div class="flex items-center gap-2">
                @foreach($actions as $action)
                    <x-button.action 
                        :href="$action['route']" 
                        :type="$action['type'] ?? 'primary'"
                    >
                        {{ $action['label'] }}
                    </x-button.action>
                @endforeach
            </div>
        @elseif(isset($headerActions))
            <div class="flex items-center gap-2">
                {{ $headerActions }}
            </div>
        @endif
    </div>

    <div class="bg-white rounded-2xl shadow-lg overflow-hidden w-full transition-all duration-300">
        @if($backUrl && !isset($headerActions))
        <div class="w-full flex items-center justify-end px-8 h-[90px] bg-white border-b border-gray-200">
            <a href="{{ $backUrl }}"
                class="bg-gray-400 hover:bg-gray-500 text-white font-semibold rounded-xl px-5 py-2 text-base shadow transition-all font-sans">
                Kembali ke Daftar
            </a>
        </div>
        @endif

        <div class="{{ ($backUrl || !empty($actions) || isset($headerActions)) ? 'px-16 py-8' : 'px-8 py-6' }}">
            {{ $slot }}
        </div>
    </div>
</div>
