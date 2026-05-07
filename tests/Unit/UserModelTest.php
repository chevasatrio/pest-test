<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// ─────────────────────────────────────────────────────────────
// Path 1: role = 'manager' → harus return true
// ─────────────────────────────────────────────────────────────
test('Bryan Seorang manager', function () {

    // Arrange — buat object User dengan role manager
    $user = new User(['role' => 'manager']);

    // Act — panggil method yang diuji
    $hasil = $user->isManager();

    // Assert — verifikasi hasilnya
    expect($hasil)->toBeTrue();

});

// ─────────────────────────────────────────────────────────────
// Path 2: role = 'karyawan' → harus return false
// ─────────────────────────────────────────────────────────────
test('Bryan Bukan Manager', function () {

    // Arrange
    $user = new User(['role' => 'karyawan']);

    // Act
    $hasil = $user->isManager();

    // Assert
    expect($hasil)->toBeFalse();

});