<?php

use App\Models\Karyawan;

test('karyawan hanya melihat data miliknya sendiri', function () {
    $userA = buatKaryawan('Andi', 'andi@test.com');
    $userB = buatKaryawan('Budi', 'budi@test.com');

    $response = $this->actingAs($userA)->get('/karyawan');

    $response->assertStatus(200);
    $response->assertSee('Andi');
    $response->assertDontSee('Budi');
});

test('karyawan tidak bisa mengakses halaman tambah karyawan', function () {
    $karyawan = buatKaryawan();

    $response = $this->actingAs($karyawan)->get('/karyawan/create');

    $response->assertStatus(403);
});

test('karyawan tidak bisa menambah karyawan baru', function () {
    $karyawan = buatKaryawan();

    $response = $this->actingAs($karyawan)->post('/karyawan', [
        'name'          => 'Unauthorized',
        'email'         => 'unauthorized@test.com',
        'password'      => 'password123',
        'jabatan'       => 'Intern',
        'departemen'    => 'IT',
        'tanggal_masuk' => '2024-01-01',
    ]);

    $response->assertStatus(403);
    $this->assertDatabaseMissing('users', ['email' => 'unauthorized@test.com']);
});

test('karyawan tidak bisa menghapus karyawan lain', function () {
    $userA     = buatKaryawan('Andi', 'andi@test.com');
    $userB     = buatKaryawan('Budi', 'budi@test.com');
    $karyawanB = Karyawan::where('user_id', $userB->id)->first();

    $response = $this->actingAs($userA)->delete("/karyawan/{$karyawanB->id}");

    $response->assertStatus(403);
    $this->assertDatabaseHas('karyawans', ['id' => $karyawanB->id]);
});