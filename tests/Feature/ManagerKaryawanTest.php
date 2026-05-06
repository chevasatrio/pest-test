<?php

use App\Models\Karyawan;

test('manager bisa melihat daftar semua karyawan', function () {
    $manager = buatManager();
    buatKaryawan();

    $response = $this->actingAs($manager)->get('/karyawan');

    $response->assertStatus(200);
    $response->assertSee('Karyawan Test');
});

test('manager bisa melihat halaman tambah karyawan', function () {
    $manager = buatManager();

    $response = $this->actingAs($manager)->get('/karyawan/create');

    $response->assertStatus(200);
});

test('manager bisa menambah karyawan baru', function () {
    $manager = buatManager();

    $response = $this->actingAs($manager)->post('/karyawan', [
        'name'          => 'Budi Santoso',
        'email'         => 'budi@test.com',
        'password'      => 'password123',
        'jabatan'       => 'Backend Developer',
        'departemen'    => 'IT',
        'no_telepon'    => '08111222333',
        'tanggal_masuk' => '2024-01-15',
    ]);

    $response->assertRedirect(route('karyawan.index'));
    $this->assertDatabaseHas('karyawans', ['nama' => 'Budi Santoso']);
});

test('tambah karyawan gagal jika email sudah terdaftar', function () {
    $manager = buatManager();
    buatKaryawan();

    $response = $this->actingAs($manager)->post('/karyawan', [
        'name'          => 'Duplikat',
        'email'         => 'karyawan@test.com',
        'password'      => 'password123',
        'jabatan'       => 'Developer',
        'departemen'    => 'IT',
        'tanggal_masuk' => '2024-01-01',
    ]);

    $response->assertSessionHasErrors('email');
});

test('manager bisa mengedit data karyawan', function () {
    $manager  = buatManager();
    buatKaryawan();
    $karyawan = Karyawan::first();

    $response = $this->actingAs($manager)->put("/karyawan/{$karyawan->id}", [
        'jabatan'       => 'Senior Developer',
        'departemen'    => 'IT',
        'no_telepon'    => '08999888777',
        'tanggal_masuk' => '2023-06-01',
    ]);

    $response->assertRedirect(route('karyawan.index'));
    $this->assertDatabaseHas('karyawans', ['jabatan' => 'Senior Developer']);
});

test('manager bisa menghapus karyawan', function () {
    $manager  = buatManager();
    buatKaryawan();
    $karyawan = Karyawan::first();

    $response = $this->actingAs($manager)->delete("/karyawan/{$karyawan->id}");

    $response->assertRedirect(route('karyawan.index'));
    $this->assertDatabaseMissing('karyawans', ['id' => $karyawan->id]);
});