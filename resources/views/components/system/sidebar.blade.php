<x-slot:sidebar drawer="main-drawer" collapsible class="bg-secondary text-secondary-content lg:bg-secondary border-e border-base-300">

    {{-- 1. BRAND / LOGO --}}
    <div class="flex items-center justify-center lg:justify-start p-6 pt-5 text-2xl font-black text-white tracking-tighter">
        <a href="/" wire:navigate class="flex items-center gap-2">
            <span class="text-white">P</span>
            <span class="mary-hideable inline-flex!">
                <span class="text-white">AL</span>
                <span class="text-primary italic font-black">ERP</span>
            </span>
        </a>
    </div>

    {{-- 2. USER PROFILE --}}
    @if($user = auth()->user())
        <div class="px-4 py-2">
            <x-mary-list-item :item="$user" value="name" sub-value="email" no-separator no-hover class="rounded-xl bg-base-100/10 text-white border border-white/5">
                <x-slot:actions>
                    <x-mary-button
                        icon="o-power"
                        class="btn-circle btn-ghost btn-xs text-accent hover:bg-accent/20"
                        tooltip="{{ __('خروج') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();" />
                    <form id="logout-form" action="/logout" method="POST" class="hidden">@csrf</form>
                </x-slot:actions>
            </x-mary-list-item>
        </div>
        <x-mary-menu-separator />
    @endif

    {{-- 3. MAIN MENU --}}
    <x-mary-menu activate-by-route class="px-2">

        {{-- الرئيسية --}}
        <x-mary-menu-item title="{{ __('الرئيسية') }}" icon="o-home" link="/" wire:navigate />

        {{-- الرسائل: دائرة خضراء (Primary) --}}
        <x-mary-menu-item title="{{ __('الرسائل') }}" icon="o-chat-bubble-left-right" link="####">
            <x-slot:actions>
                <div class="flex items-center gap-1.5">
                    {{-- نقطة النبض --}}
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-primary"></span>
                    </span>
                    {{-- الرقم داخل Badge صلب ليظهر فوق السواد --}}
                    <span class="flex h-5 min-w-[20px] px-1.5 items-center justify-center rounded-full bg-primary text-primary-content text-[10px] font-black shadow-md border border-white/10">
                        5
                    </span>
                </div>
            </x-slot:actions>
        </x-mary-menu-item>

        {{-- الإشعارات: دائرة حمراء (Accent) --}}
        <x-mary-menu-item title="{{ __('الإشعارات') }}" icon="o-bell" link="####">
            <x-slot:actions>
                <div class="flex items-center">
                    <span class="flex h-5 min-w-[20px] px-1.5 items-center justify-center rounded-full bg-accent text-accent-content text-[10px] font-black shadow-md border border-white/10">
                        12
                    </span>
                </div>
            </x-slot:actions>
        </x-mary-menu-item>

        <x-mary-menu-separator />

        {{-- موديول المالية (مثال للاختبار) --}}

        <x-mary-menu-separator />

        {{-- الإعدادات --}}
        <x-mary-menu-sub title="{{ __('الإعدادات') }}" icon="o-cog-6-tooth">
            <x-mary-menu-item title="{{ __('إعدادات النظام') }}" icon="o-adjustments-horizontal" link="####" />
            <x-mary-menu-item title="{{ __('إدارة المستخدمين') }}" icon="o-users" link="####" />
            <x-mary-menu-item title="{{ __('سجل العمليات') }}" icon="o-document-text" link="####" />
        </x-mary-menu-sub>

    </x-mary-menu>

</x-slot:sidebar>
