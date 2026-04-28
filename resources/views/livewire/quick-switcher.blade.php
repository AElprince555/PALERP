<div x-data="{
        open: false,
        selectedIndex: 0,
        get max() { return $refs.resultsList ? $refs.resultsList.querySelectorAll('.search-item').length : 0 }
     }"
     @keydown.window.cmd.k.prevent="open = true"
     @keydown.window.ctrl.k.prevent="open = true"
     @keydown.escape.window="open = false; $wire.set('query', '')"
     @keydown.down.prevent="if(open) { selectedIndex = (selectedIndex + 1) % (max || 1); $nextTick(() => $refs.resultsList.querySelectorAll('.search-item')[selectedIndex].scrollIntoView({ block: 'nearest' })) }"
     @keydown.up.prevent="if(open) { selectedIndex = (selectedIndex - 1 + max) % (max || 1); $nextTick(() => $refs.resultsList.querySelectorAll('.search-item')[selectedIndex].scrollIntoView({ block: 'nearest' })) }"
     @keydown.enter.prevent="if(open && max > 0) { $refs.resultsList.querySelectorAll('.search-item')[selectedIndex].click() }">

    {{-- Modal Overlay --}}
    <div x-show="open"
         x-transition.opacity
         x-cloak
         class="fixed inset-0 z-[999] bg-secondary/40 backdrop-blur-md flex items-start justify-center pt-[15vh]">

        {{-- Modal Content --}}
        <div @click.away="open = false"
             class="bg-base-100 w-full max-w-2xl rounded-3xl shadow-2xl border border-white/20 overflow-hidden mx-4 flex flex-col ring-1 ring-black/5">

            {{-- Search Input --}}
            <div class="relative flex items-center p-6 border-b border-base-200 flex-none">
                <x-mary-icon name="o-magnifying-glass" class="w-6 h-6 text-primary absolute left-8" />
                <input type="text"
                       wire:model.live.debounce.250ms="query"
                       @input="selectedIndex = 0"
                       class="input input-ghost w-full pl-14 focus:outline-none text-xl font-medium placeholder:text-base-content/30"
                       placeholder="{{ __('ابحث عن موديول أو اختصار...') }}"
                       x-init="$watch('open', value => value && setTimeout(() => $el.focus(), 100))">

                <div class="absolute right-8 flex gap-1">
                    <kbd class="kbd kbd-sm bg-base-200">ESC</kbd>
                </div>
            </div>

            {{-- Results List --}}
            <div class="max-h-[450px] overflow-y-auto p-4 custom-scrollbar" x-ref="resultsList">
                @forelse($results as $index => $module)
                    <a href="{{ $module->route ? route($module->route) : '#' }}"
                       wire:navigate
                       @click="open = false"
                       :class="{ 'bg-primary text-white shadow-lg scale-[1.02]': selectedIndex === {{ $index }}, 'hover:bg-base-200': selectedIndex !== {{ $index }} }"
                       class="search-item flex items-center justify-between p-4 mb-2 rounded-2xl transition-all duration-200 group outline-none">

                        <div class="flex items-center gap-4">
                            <div :class="selectedIndex === {{ $index }} ? 'bg-white/20' : 'bg-primary/10'" class="p-3 rounded-xl">
                                <x-mary-icon :name="$module->icon ?? 'o-cube'" class="w-6 h-6" />
                            </div>
                            <div class="flex flex-col">
                                <span class="font-bold text-base">{{ $module->name }}</span>
                                <span class="text-xs opacity-70 tracking-wide">{{ $module->parent?->name ?? 'System Module' }}</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                             <span :class="selectedIndex === {{ $index }} ? 'bg-white/20' : 'bg-base-200'"
                                   class="px-3 py-1 rounded-lg font-mono text-[11px] font-bold">
                                {{ $module->code }}
                             </span>
                            <x-mary-icon name="o-chevron-left" class="w-4 h-4 opacity-50 rtl:rotate-0 rotate-180" />
                        </div>
                    </a>
                @empty
                    @if(strlen($query) > 1)
                        <div class="p-16 text-center">
                            <x-mary-icon name="o-exclamation-triangle" class="w-16 h-16 mx-auto mb-4 opacity-20 text-primary" />
                            <p class="text-lg opacity-40 font-bold uppercase tracking-widest">{{ __('لا توجد نتائج مطابقة') }}</p>
                        </div>
                    @else
                        <div class="p-10 text-center opacity-40">
                            <p class="text-sm font-medium italic">{{ __('ابدأ الكتابة للبحث عن الحسابات، الموردين، أو الموديولات...') }}</p>
                        </div>
                    @endif
                @endforelse
            </div>

            {{-- Footer Help --}}
            <div class="p-4 bg-secondary/5 border-t border-base-200 text-[11px] flex justify-between px-8 opacity-60 font-bold tracking-widest">
                <div class="flex gap-6">
                    <span class="flex items-center gap-1"><kbd class="kbd kbd-xs">↑↓</kbd> Navigate</span>
                    <span class="flex items-center gap-1"><kbd class="kbd kbd-xs">↵</kbd> Select</span>
                </div>
                <span>PAL-ERP COMMAND CENTER</span>
            </div>
        </div>
    </div>
</div>
