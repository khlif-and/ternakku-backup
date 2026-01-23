<div class="absolute bottom-4 w-full text-center">
    <button @click="sidebarCollapsed = !sidebarCollapsed"
        class="w-9 h-9 mx-auto rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center">
        <svg class="w-5 h-5 text-white arrow-icon"
            :class="{ 'rotate-180': sidebarCollapsed }" fill="none"
            stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
        </svg>
    </button>
</div>
