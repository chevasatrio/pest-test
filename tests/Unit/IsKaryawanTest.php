<?php

use App\Models\User;

// ─────────────────────────────────────────────────────────────
// Path 1: role = 'karyawan' → harus return true
// Rute: N1 → N2(TRUE) → N3 → N5
// ─────────────────────────────────────────────────────────────
test('isKaryawan mengembalikan true jika role adalah karyawan', function () {

    // Arrange — buat object User dengan role karyawan
    $user = new User(['role' => 'karyawan']);

    // Act — panggil method yang diuji
    $hasil = $user->isKaryawan();

    // Assert — verifikasi hasilnya
    expect($hasil)->toBeTrue();

});

// ─────────────────────────────────────────────────────────────
// Path 2: role = 'manager' → harus return false
// Rute: N1 → N2(FALSE) → N4 → N5
// ─────────────────────────────────────────────────────────────
test('isKaryawan mengembalikan false jika role adalah manager', function () {

    // Arrange
    $user = new User(['role' => 'manager']);

    // Act
    $hasil = $user->isKaryawan();

    // Assert
    expect($hasil)->toBeFalse();

});

// ─────────────────────────────────────────────────────────────
// Path 3: role = '' (kosong) → harus return false (boundary)
// Rute: N1 → N2(FALSE) → N4 → N5
// ─────────────────────────────────────────────────────────────
test('isKaryawan mengembalikan false jika role kosong', function () {

    // Arrange — boundary case: role string kosong
    $user = new User(['role' => '']);

    // Act
    $hasil = $user->isKaryawan();

    // Assert
    expect($hasil)->toBeFalse();

});
