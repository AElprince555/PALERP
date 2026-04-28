<?php

namespace Database\Factories;

use App\Models\Module;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Module>
 */
class ModuleFactory extends Factory
{
    protected $model = Module::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // توليد كود فريد لضمان عدم تعارض الـ Unique Constraint
        $code = strtoupper(Str::random(3)) . '-' . strtoupper(Str::random(3));

        return [
            'parent_id'      => null,
            'code'           => $code,
            'name'           => [
                'ar' => 'موديول ' . fake()->word(),
                'en' => ucwords(fake()->words(2, true))
            ],
            'description'    => [
                'ar' => 'وصف تجريبي للموديول ' . fake()->word(),
                'en' => fake()->sentence()
            ],
            'level'          => 1,
            'type'           => 'folder',
            'icon'           => 'o-folder', // افتراضياً أيقونة من Heroicons/MaryUI
            'route'          => null,
            'sort_order'     => fake()->numberBetween(1, 100),
            'is_active'      => true,
            'permission_key' => strtolower(str_replace('-', '.', $code)),
            'settings'       => [],
            'metadata'       => [],
        ];
    }

    /**
     * حالة مخصصة لإنشاء موديول من نوع "مجلد" (Sector/Folder)
     */
    public function folder(): static
    {
        return $this->state(fn (array $attributes) => [
            'type'  => 'folder',
            'route' => null, // المجلدات تستخدم الـ Dynamic Route الذي بنيناه
            'icon'  => 'o-rectangle-group',
        ]);
    }

    /**
     * حالة مخصصة لإنشاء موديول من نوع "تطبيق" (App)
     */
    public function app(string $route = null): static
    {
        return $this->state(fn (array $attributes) => [
            'type'  => 'app',
            'route' => $route ?? 'dashboard', // مسار افتراضي لتجنب أخطاء 404 في الاختبارات
            'icon'  => 'o-document-text',
            'level' => 3, // عادة التطبيقات تكون في المستوى الثالث
        ]);
    }

    /**
     * حالة مخصصة لجعل الموديول غير مفعل
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * حالة لإنشاء موديول فرعي تابع لموديول أب (Hierarchical Relationship)
     */
    public function childOf(Module $parent): static
    {
        return $this->state(fn (array $attributes) => [
            'parent_id' => $parent->id,
            'level'     => $parent->level + 1,
        ]);
    }
}
