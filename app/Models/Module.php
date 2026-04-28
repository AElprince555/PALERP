<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Module extends Model
{
    use HasFactory , HasTranslations;

    // حماية الـ ID فقط والسماح بالباقي (بما فيها الأعمدة الجديدة)
    protected $guarded = ['id'];
    public array $translatable = ['name' , 'description'];
    protected function casts(): array
    {
        return [
            'name'        => 'array',
            'description' => 'array',
            'settings'    => AsArrayObject::class,
            'metadata'    => AsArrayObject::class,
            'is_active'   => 'boolean',
        ];
    }

    // ==========================================
    // العلاقات (Relationships)
    // ==========================================

    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Module::class, 'parent_id');
    }

    public function children(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Module::class, 'parent_id');
    }

    // ==========================================
    // النطاقات (Scopes)
    // ==========================================

    public function scopeRoots($query)
    {
        return $query->whereNull('parent_id')->where('is_active', true);
    }

    // ==========================================
    // محرك العرض الذكي (PALERP Smart Renderer)
    // ==========================================

    /**
     * تحديد كلاس Livewire الذي سيتم تشغيله لعرض الشاشة الرئيسية
     */
    public function getResolvedComponent(): string
    {
        // 1. إذا كان هناك مكون مخصص في قاعدة البيانات، نستخدمه
        if (!empty($this->component_name)) {
            return $this->component_name;
        }

        // 2. إذا لم يوجد، نستخدم مكونات Volt العامة التي قمنا بإنشائها
        // لاحظ أننا نستخدم الاسم المختصر (Alias) الخاص بـ Volt/Livewire
        return match($this->type) {
            'folder' => 'system.generic-folder',
            'app'    => 'system.generic-app',
            'mix'    => 'system.generic-mix', // الـ Mix يعمل كـ Folder مبدئياً لأنه يحتوي على تطبيقات فرعية
            default  => 'system.generic-sector',
        };
    }

    /**
     * (اختياري) تحديد كلاس Livewire الخاص بنموذج الإدخال (Form) للإضافة والتعديل
     * هذا سنستخدمه لاحقاً في شاشات الـ CRUD
     */
    public function getResolvedForm(): ?string
    {
        if (!empty($this->form_name)) {
            return $this->form_name;
        }

        if (in_array($this->type, ['app', 'mix'])) {
            return 'system.generic-form'; // سننشئ هذا لاحقاً بالـ Volt أيضاً
        }

        return null;
    }
}
