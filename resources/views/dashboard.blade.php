<x-layouts.app title="لوحة التحكم">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 animate-fade-in-up">

        {{-- Card 1: المبيعات --}}
        <x-mary-stat
            title="إجمالي المبيعات"
            value="15,250 ₪"
            icon="o-currency-dollar"
            description="زيادة بنسبة 12% عن الشهر الماضي"
            class="bg-base-100 shadow-sm border-b-4 border-primary"
        />

        {{-- Card 2: العملاء --}}
        <x-mary-stat
            title="العملاء النشطون"
            value="1,200"
            icon="o-users"
            description="50 عميل جديد اليوم"
            class="bg-base-100 shadow-sm border-b-4 border-secondary"
        />

        {{-- Card 3: التنبيهات --}}
        <x-mary-stat
            title="طلبات معلقة"
            value="18"
            icon="o-clock"
            description="تحتاج لمراجعة فورية"
            class="bg-base-100 shadow-sm border-b-4 border-accent"
        />

    </div>

    <div class="mt-8">
        <x-mary-card title="أحدث العمليات" subtitle="قائمة بآخر الحركات المالية في النظام" shadow separator>
            {{-- هنا سنضع جدول البيانات لاحقاً --}}
            <div class="py-10 text-center opacity-30 italic">
                لا توجد بيانات لعرضها حالياً.. ابدأ بإضافة أول عملية!
            </div>
        </x-mary-card>
    </div>
</x-layouts.app>
