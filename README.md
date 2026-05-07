# Dari Teori ke Tools: White Box, Black Box, PEST, dan Selenium
**Pengujian dan Implementasi Sistem | BBK2MAB2**


Teori manual dulu → baru implementasi di tools

Basis Path (hitung V(G))  →  PEST Unit Test
EP + State Transition      →  PEST Feature Test
State Transition (UI)      →  Selenium


## Kenapa Pembagian Ini Penting?

Sebelum menulis satu baris kode test pun, kalian **harus bisa merancang test case-nya secara manual** menggunakan teknik yang sesuai. Baru kemudian dituangkan ke dalam PEST atau Selenium.

Inilah yang membedakan seorang tester yang paham dengan yang sekadar copy-paste sintaks.

---

## Bagian 1 — PEST Unit Test sebagai White Box

### Apa yang diuji?

Unit Test menguji **satu method atau fungsi secara terisolir** — tanpa database, tanpa HTTP request, tanpa dependensi apapun. Murni logika kode.

### Teknik manualnya: Basis Path Testing

Sebelum nulis test di PEST, kita harus bisa menjawab: **"Berapa jalur eksekusi yang mungkin terjadi di dalam fungsi ini?"**

Jawabannya didapat dari menghitung **Cyclomatic Complexity V(G)**.

### Langkah-langkah:

```
1. Baca kode → identifikasi struktur IF, WHILE, FOR
        ↓
2. Gambar Flow Graph → identifikasi Node (N) dan Edge (E)
        ↓
3. Hitung V(G) = E - N + 2P
        ↓
4. Tentukan Independent Path sebanyak V(G)
        ↓
5. Tulis test() di PEST — satu path = satu test
```

---

## Bagian 2 — PEST Feature Test sebagai Black Box

### Apa yang diuji?

Feature Test menguji fitur dari sisi **HTTP** — kirim request ke server, verifikasi response yang kembali. Tidak perlu tahu isi kode controller atau model.

### Teknik manualnya: EP + State Transition (HTTP layer)

**Equivalence Partitioning** — bagi skenario ke dalam kelas valid dan invalid:
- Kelas valid → request yang seharusnya berhasil
- Kelas invalid → request yang seharusnya ditolak

**State Transition** — setiap HTTP request memicu perpindahan state:
- Tamu → POST /login → Authenticated
- Authenticated → POST /logout → Tamu

### Selenium sebagai Black Box murni

Selenium adalah Black Box yang **paling murni** karena dia tidak tahu apapun tentang kode di balik layar — persis seperti user sungguhan yang hanya melihat browser.

Teknik manualnya adalah **State Transition di layer UI**:

```
Halaman login → isi form → klik tombol submit → halaman dashboard muncul
   State 1          Act              →                  State 2
```

---

## Step by step instalasi pest via composer 

Step 1 — Pastikan ada proyek Laravel dulu dan cek versi laravel-nya
php artisan --version

Step 2 — Install PEST via Composer
composer require pestphp/pest --dev --with-all-dependencies

Step 3 — Install plugin Laravel
composer require pestphp/pest-plugin-laravel --dev

Step 4 — Init PEST
./vendor/bin/pest --init


setelah anda lakukan 4 step diatas maka struktur folder nya akan berubah 
tests/
├── Pest.php
├── Feature/
│   └── ExampleTest.php
└── Unit/
    └── ExampleTest.php


## Studi Kasus: Method isManager() di Model User

Sekarang kita praktekkan alur lengkapnya — dari ngitung V(G) secara manual sampai nulis test di PEST.

### Kode yang akan diuji

```php
// app/Models/User.php

public function isManager(): bool
{
    if ($this->role === 'manager') {
        return true;
    }
    return false;
}
```

---

### Step 1 — Identifikasi Struktur Kode

Baca kode di atas dan identifikasi:

- Ada berapa `if` / `else` / `while` / `for`?

Jawaban: **1 buah IF** → berarti ada **1 Predicate Node**

---

### Step 2 — Gambar Flow Graph

Petakan setiap statement menjadi Node, dan setiap aliran kontrol menjadi Edge:

```
         [N1] Start
           |
           | e1
           ▼
   [N2] role === 'manager'?   ← Predicate Node
        /           \
    e2 /             \ e3
  TRUE               FALSE
      |               |
      ▼               ▼
   [N3]             [N4]
 return true      return false
      |               |
   e4 |            e5 |
      ▼               ▼
         [N5] End
```

**Hasil identifikasi:**

| Elemen | Jumlah | Detail |
|---|---|---|
| Node (N) | 5 | N1 Start, N2 Predicate, N3 True, N4 False, N5 End |
| Edge (E) | 5 | e1, e2, e3, e4, e5 + 1 dari N1 ke N2 |
| Predicate Node (P kondisi) | 1 | N2 saja |
| Komponen terhubung (P formula) | 1 | satu program |

---

### Step 3 — Hitung V(G) dengan 3 Formula

Verifikasi ketiga formula harus menghasilkan nilai yang sama:

**Formula 1 — E - N + 2P:**
```
V(G) = E - N + 2P
V(G) = 5 - 5 + 2(1)
V(G) = 5 - 5 + 2
V(G) = 2
```

**Formula 2 — Predicate Node + 1:**
```
V(G) = P + 1
V(G) = 1 + 1
V(G) = 2
```

> Perhatikan: Formula 2 menghasilkan 2, bukan 3. Ini karena Path 3 (role kosong) adalah **boundary case** yang perlu ditambahkan secara eksplisit — bukan dari predicate node, tapi dari pemikiran kritis tester tentang kondisi per edge.

**Formula 3 — Region + 1:**
```
Region tertutup di flow graph = 1
V(G) = 1 + 1
V(G) = 2
```

**Kesimpulan V(G):**

Nilai dasar V(G) = **2**, tapi sebagai tester yang baik kita tambahkan **boundary case** sehingga minimum test case yang direkomendasikan = **3**.

---

### Step 4 — Tentukan Independent Paths

Berdasarkan V(G) = 2 (+ 1 boundary case), kita mendapatkan 3 independent path:

**Path 1 — Happy Path:**
```
N1 → N2(TRUE) → N3 → N5
Kondisi : role === 'manager'
Output  : return true
```

**Path 2 — Role Berbeda:**
```
N1 → N2(FALSE) → N4 → N5
Kondisi : role === 'karyawan'
Output  : return false
```

**Path 3 — Boundary Case (role tidak diset):**
```
N1 → N2(FALSE) → N4 → N5
Kondisi : role === '' (string kosong)
Output  : return false
```

---

### Step 5 — Tulis Test Case di PEST

Sekarang baru kita tulis kodenya. **Satu path = satu test.**

Buat file `tests/Unit/UserModelTest.php`:

```php
<?php

use App\Models\User;

// ─────────────────────────────────────────────────────────────
// Path 1: role = 'manager' → harus return true
// ─────────────────────────────────────────────────────────────
test('isManager mengembalikan true jika role adalah manager', function () {

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
test('isManager mengembalikan false jika role adalah karyawan', function () {

    // Arrange
    $user = new User(['role' => 'karyawan']);

    // Act
    $hasil = $user->isManager();

    // Assert
    expect($hasil)->toBeFalse();

});

### Step 6 — Jalankan Test

```bash
./vendor/bin/pest tests/Unit/UserModelTest.php
```

Output yang diharapkan:

```
   PASS  Tests\Unit\UserModelTest
  - Bryan mengembalikan true jika role adalah manager
  - Bryan mengembalikan false jika role adalah karyawan

  Tests:    2 passed (2 assertions)
  Duration: 0.11s
```

---

### Ringkasan Studi Kasus

```
isManager() → 1 IF → V(G) = 2 → + 1 boundary → 2 test case

Path 1 : role = 'manager'   → true   
Path 2 : role = 'karyawan'  → false 
```

---

## Latihan Mandiri

Terapkan alur yang sama untuk method `isKaryawan()`:

```php
public function isKaryawan(): bool
{
    if ($this->role === 'karyawan') {
        return true;
    }
    return false;
}
```

Kerjakan:
1. Gambar flow graph-nya 
2. Hitung V(G) dengan 3 formula
3. Tentukan semua independent path
4. Tulis test case lengkap di PEST

Deadline : Kamis, 14 Mei 2026 : 07.00 WIB 

---

## Penutup

Hari ini kita sudah membuktikan bahwa semua yang dipelajari di kelas — V(G), EP, State Transition — bukan sekadar teori yang dikerjakan di kertas. Semuanya punya padanan langsung di dunia tools yang dipakai industri.

Seorang tester yang baik tidak hanya tahu cara menulis `expect()->toBeTrue()` — tapi tahu **mengapa** dia menulis test itu dan **dari mana** test case itu berasal.

---

*Pengujian dan Implementasi Sistem | BBK2MAB2 | Telkom University Surabaya*
