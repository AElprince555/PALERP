<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->index()->constrained('modules')->restrictOnDelete()->cascadeOnUpdate();
            $table->string('code')->unique();
            $table->json('name');
            $table->json('description')->nullable();

            $table->unsignedTinyInteger('level')->default(1)->index();
            $table->enum('type',['sector','folder','app','mix'])->default('folder');

            $table->string('icon')->default('o-folder');
            $table->string('route')->nullable();
            $table->integer('sort_order')->default(0);
            $table->string('component_name')->nullable();
            $table->string('view_name')->nullable();
            $table->string('form_name')->nullable();

            $table->boolean('is_active')->default(true)->index();
            $table->json('settings')->nullable();
            $table->string('permission_key')->nullable()->unique();
            $table->json('metadata')->nullable();

            $table->index(['parent_id', 'level','sort_order' , 'is_active']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};
