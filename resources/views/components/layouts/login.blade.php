<x-layouts.guest title="تسجيل الدخول">
    {{-- شعار النظام في منتصف الصفحة --}}
    <div class="text-center mb-8">
        <p class="text-xs opacity-50 uppercase tracking-widest mt-2">Secure Access Gateway</p>
    </div>

    {{-- بطاقة تسجيل الدخول باستخدام Mary UI --}}

        {{-- عرض رسائل الخطأ من Fortify إن وجدت --}}
        @if ($errors->any())
            <div class="mb-4">
                @foreach ($errors->all() as $error)
                    <x-mary-alert  icon="o-exclamation-triangle" class="alert-error mb-2 text-sm p-2">
                        {{ $error }}
                    </x-mary-alert>
                @endforeach
            </div>
        @endif

        <x-mary-form action="/login" method="POST">
            @csrf

            {{-- حقل البريد الإلكتروني مع الحفاظ على القيمة القديمة عند الخطأ --}}
            <x-mary-input
                label="البريد الإلكتروني"
                name="email"
                type="email"
                icon="o-envelope"
                value="{{ old('email') }}"
                required
                autofocus
                inline
            />

            {{-- حقل كلمة المرور --}}
            <x-mary-input
                label="كلمة المرور"
                name="password"
                type="password"
                icon="o-key"
                required
                inline
            />

            {{-- خيار تذكرني --}}
            <div class="flex justify-between items-center px-1">
                <x-mary-checkbox label="تذكرني" name="remember" class="checkbox-sm" />
                {{-- رابط نسيت كلمة المرور (اختياري) --}}
                {{-- <a href="#" class="text-xs text-primary hover:underline">نسيت كلمة المرور؟</a> --}}
            </div>

            <x-slot:actions class="w-full flex flex-col gap-3">
                {{-- زر الدخول مع خاصية الـ Spinner عند الإرسال --}}
                <x-mary-button
                    label="دخول"
                    type="submit"
                    class="btn-primary w-full"
                />
            </x-slot:actions>
        </x-mary-form>

    {{-- تذييل الصفحة --}}
    <div class="text-center mt-6">
        <p class="text-[10px] opacity-30 uppercase tracking-[0.2em]">
            &copy; {{ date('Y') }} PALERP System v1.0
        </p>
    </div>
</x-layouts.guest>
