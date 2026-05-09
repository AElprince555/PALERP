<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * اختبار: التأكد من تحويل المستخدم غير المسجل لصفحة الدخول
 */
test('unauthenticated users are redirected to login', function () {
    $this->get('/')
        ->assertStatus(302)
        ->assertRedirect('/login');
});

/**
 * اختبار: التأكد من عرض صفحة الدخول بنجاح
 */
test('login page can be rendered', function () {
    $this->get('/login')
        ->assertStatus(200);
});

/**
 * اختبار: التأكد من نجاح عملية تسجيل الدخول
 */
test('users can authenticate using the login screen', function () {
    $user = User::factory()->create([
        'password' => bcrypt($password = 'palestine-123'),
    ]);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => $password,
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect('/');
});

/**
 * اختبار: التأكد من حماية الـ Dashboard للمسجلين فقط
 * أضفنا $this->withoutExceptionHandling() هنا لاكتشاف سبب خطأ 500
 */
test('authenticated users can access dashboard', function () {
    // إنشاء مستخدم
    $user = User::factory()->create();

    // إيقاف إخفاء الأخطاء لإظهار السبب الحقيقي لخطأ 500 في الـ Terminal
    $this->withoutExceptionHandling();

    // محاولة الدخول كـ User وطلب الصفحة الرئيسية
    $this->actingAs($user)
        ->get('/')
        ->assertStatus(200);
});
