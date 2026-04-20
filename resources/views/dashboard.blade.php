<x-layouts.app title="لوحة التحكم">
    <div class="w-full space-y-6 animate-fade-in-up">

        {{-- STATS GRID --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <x-mary-stat
                title="إجمالي المبيعات"
                value="15,250 ₪"
                icon="o-currency-dollar"
                class="bg-base-100 shadow-sm border-b-4 border-primary"
            />

            <x-mary-stat
                title="العملاء النشطون"
                value="1,200"
                icon="o-users"
                class="bg-base-100 shadow-sm border-b-4 border-secondary"
            />

            <x-mary-stat
                title="طلبات معلقة"
                value="18"
                icon="o-clock"
                class="bg-base-100 shadow-sm border-b-4 border-accent"
            />
        </div>

        {{-- MAIN CARD --}}
        <x-mary-card title="أحدث العمليات" subtitle="قائمة بآخر الحركات المالية" shadow separator class="w-full">
            <div class="py-12 text-center opacity-40 italic border-2 border-dashed border-base-300 rounded-lg">
                لا توجد بيانات لعرضها حالياً.. ابدأ بإضافة أول عملية!
            </div>
        </x-mary-card>
    </div>
</x-layouts.app>
