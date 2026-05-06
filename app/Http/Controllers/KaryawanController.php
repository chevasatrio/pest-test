<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KaryawanController extends Controller
{
    public function index()
    {
        if (auth()->user()->isManager()) {
            $karyawans = Karyawan::with('user')->latest()->get();
        } else {
            $karyawans = Karyawan::where('user_id', auth()->id())->get();
        }

        return view('karyawan.index', compact('karyawans'));
    }

    public function create()
    {
        return view('karyawan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|min:8',
            'jabatan'       => 'required|string|max:255',
            'departemen'    => 'required|string|max:255',
            'no_telepon'    => 'nullable|string|max:20',
            'tanggal_masuk' => 'required|date',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'karyawan',
        ]);

        Karyawan::create([
            'user_id'       => $user->id,
            'nama'          => $request->name,
            'jabatan'       => $request->jabatan,
            'departemen'    => $request->departemen,
            'no_telepon'    => $request->no_telepon,
            'tanggal_masuk' => $request->tanggal_masuk,
        ]);

        return redirect()->route('karyawan.index')
            ->with('success', 'Data karyawan berhasil ditambahkan.');
    }

    public function show(Karyawan $karyawan)
    {
        if (auth()->user()->isKaryawan() && $karyawan->user_id !== auth()->id()) {
            abort(403);
        }

        return view('karyawan.show', compact('karyawan'));
    }

    public function edit(Karyawan $karyawan)
    {
        return view('karyawan.edit', compact('karyawan'));
    }

    public function update(Request $request, Karyawan $karyawan)
    {
        $request->validate([
            'jabatan'       => 'required|string|max:255',
            'departemen'    => 'required|string|max:255',
            'no_telepon'    => 'nullable|string|max:20',
            'tanggal_masuk' => 'required|date',
        ]);

        $karyawan->update($request->only([
            'jabatan', 'departemen', 'no_telepon', 'tanggal_masuk'
        ]));

        return redirect()->route('karyawan.index')
            ->with('success', 'Data karyawan berhasil diperbarui.');
    }

    public function destroy(Karyawan $karyawan)
    {
        $karyawan->user->delete();

        return redirect()->route('karyawan.index')
            ->with('success', 'Data karyawan berhasil dihapus.');
    }
}
