<?php

test('halaman login bisa diakses oleh tamu', function () {
    $response = $this->get('/login');
    $response->assertStatus(200);
});

test('manager bisa login dengan kredensial yang benar', function () {
    $manager = buatManager();

    $response = $this->post('/login', [
        'email'    => 'manager@test.com',
        'password' => 'password123',
    ]);

    $response->assertRedirect('/karyawan');
    $this->assertAuthenticatedAs($manager);
});

test('karyawan bisa login dengan kredensial yang benar', function () {
    $karyawan = buatKaryawan();

    $response = $this->post('/login', [
        'email'    => 'karyawan@test.com',
        'password' => 'password123',
    ]);

    $response->assertRedirect('/karyawan');
    $this->assertAuthenticatedAs($karyawan);
});

test('login gagal jika password salah', function () {
    buatManager();

    $response = $this->post('/login', [
        'email'    => 'manager@test.com',
        'password' => 'passwordsalah',
    ]);

    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

test('login gagal jika email tidak terdaftar', function () {
    $response = $this->post('/login', [
        'email'    => 'tidakada@test.com',
        'password' => 'password123',
    ]);

    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

test('user yang belum login diarahkan ke halaman login', function () {
    $response = $this->get('/karyawan');
    $response->assertRedirect('/login');
});

test('user bisa logout', function () {
    $manager = buatManager();

    $this->actingAs($manager)
         ->post('/logout')
         ->assertRedirect('/');

    $this->assertGuest();
});