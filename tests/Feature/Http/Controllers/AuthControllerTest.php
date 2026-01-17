<?php

use App\Models\User;

test('showLogin displays login form', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
    $response->assertViewIs('auth.login');
});

test('showLogin redirects authenticated users to dashboard', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/login');

    $response->assertRedirect(route('dashboard'));
});

test('login succeeds with valid credentials', function () {
    $user = User::factory()->create([
        'password' => bcrypt('password123'),
    ]);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password123',
    ]);

    $response->assertRedirect('dashboard');
    $this->assertAuthenticatedAs($user);
});

test('login fails with invalid credentials', function () {
    $user = User::factory()->create([
        'password' => bcrypt('password123'),
    ]);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

test('login validates required fields', function () {
    $response = $this->post('/login', []);

    $response->assertSessionHasErrors(['email', 'password']);
});

test('logout logs out authenticated user', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $response->assertRedirect('/');
    $this->assertGuest();
});
