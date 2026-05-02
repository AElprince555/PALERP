<?php

namespace App\Actions\System;

use App\Models\Module;
use Illuminate\Support\Collection;

class GetSubModulesAction
{
    /**
     * جلب الموديولات التابعة بناءً على المعرف الأب
     */
    public function execute(?int $parentId = null): Collection
    {
        return Module::query()
            ->select(['id', 'parent_id', 'code', 'name', 'icon', 'type', 'route', 'level', 'sort_order' , 'description'])
            ->where('parent_id', $parentId)
            ->where('is_active', true)
            ->withCount('children') // لمعرفة هل المجلد يحتوي على عناصر أم لا
            ->orderBy('sort_order')
            ->get();
    }
}
