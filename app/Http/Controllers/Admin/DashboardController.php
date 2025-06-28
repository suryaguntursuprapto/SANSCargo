<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Branch;
use App\Models\DetailPengiriman;
use App\Models\Ongkir;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'total_users' => User::count(),
            'total_branches' => Branch::count(),
            'total_pengiriman' => DetailPengiriman::count(),
            'total_ongkir' => Ongkir::count(),
            'users_bulan_ini' => User::whereMonth('created_at', now()->month)->count(),
            'pengiriman_bulan_ini' => DetailPengiriman::whereMonth('created_at', now()->month)->count(),
            'recent_users' => User::latest()->take(5)->get(),
            'recent_pengiriman' => DetailPengiriman::with(['pengirimPenerima'])->latest()->take(5)->get(),
        ];

        return view('admin.dashboard', compact('data'));
    }
}

// app/Http/Controllers/Admin/BranchController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BranchController extends Controller
{
    public function index(Request $request)
    {
        $query = Branch::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_branch', 'like', "%{$search}%")
                  ->orWhere('kode_branch', 'like', "%{$search}%")
                  ->orWhere('kota', 'like', "%{$search}%");
            });
        }

        $branches = $query->paginate(10);
        return view('admin.branches.index', compact('branches'));
    }

    public function create()
    {
        return view('admin.branches.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_branch' => 'required|string|max:255|unique:branches',
            'alamat' => 'required|string',
            'kota' => 'required|string|max:100',
            'provinsi' => 'required|string|max:100',
            'kode_pos' => 'required|string|max:10',
            'telepon' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'status' => 'required|boolean'
        ]);

        // Generate kode branch otomatis
        $validated['kode_branch'] = 'BR' . strtoupper(Str::random(4));

        Branch::create($validated);

        return redirect()->route('admin.branches.index')
            ->with('success', 'Branch berhasil ditambahkan.');
    }

    public function show(Branch $branch)
    {
        $branch->load('users');
        return view('admin.branches.show', compact('branch'));
    }

    public function edit(Branch $branch)
    {
        return view('admin.branches.edit', compact('branch'));
    }

    public function update(Request $request, Branch $branch)
    {
        $validated = $request->validate([
            'nama_branch' => 'required|string|max:255|unique:branches,nama_branch,' . $branch->id,
            'alamat' => 'required|string',
            'kota' => 'required|string|max:100',
            'provinsi' => 'required|string|max:100',
            'kode_pos' => 'required|string|max:10',
            'telepon' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'status' => 'required|boolean'
        ]);

        $branch->update($validated);

        return redirect()->route('admin.branches.index')
            ->with('success', 'Branch berhasil diperbarui.');
    }

    public function destroy(Branch $branch)
    {
        try {
            $branch->delete();
            return redirect()->route('admin.branches.index')
                ->with('success', 'Branch berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.branches.index')
                ->with('error', 'Branch tidak dapat dihapus karena masih memiliki data terkait.');
        }
    }
}

// app/Http/Controllers/Admin/UserController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nama_lengkap', 'like', "%{$search}%");
            });
        }

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->has('branch') && $request->branch !== '') {
            $query->where('branch', $request->branch);
        }

        $users = $query->paginate(10);
        $branches = Branch::active()->pluck('nama_branch', 'nama_branch');

        return view('admin.users.index', compact('users', 'branches'));
    }

    public function create()
    {
        $branches = Branch::active()->pluck('nama_branch', 'nama_branch');
        return view('admin.users.create', compact('branches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'nomor_telepon' => 'required|string|max:20',
            'alamat' => 'required|string',
            'branch' => 'required|string',
            'status' => 'required|in:Admin,Customer,Staff'
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $branches = Branch::active()->pluck('nama_branch', 'nama_branch');
        return view('admin.users.edit', compact('user', 'branches'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'nomor_telepon' => 'required|string|max:20',
            'alamat' => 'required|string',
            'branch' => 'required|string',
            'status' => 'required|in:Admin,Customer,Staff'
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed']);
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        try {
            $user->delete();
            return redirect()->route('admin.users.index')
                ->with('success', 'User berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.users.index')
                ->with('error', 'User tidak dapat dihapus karena masih memiliki data terkait.');
        }
    }
}

// app/Http/Controllers/Admin/OngkirController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ongkir;
use Illuminate\Http\Request;

class OngkirController extends Controller
{
    public function index(Request $request)
    {
        $query = Ongkir::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kota_asal', 'like', "%{$search}%")
                  ->orWhere('kota_tujuan', 'like', "%{$search}%")
                  ->orWhere('jenis_layanan', 'like', "%{$search}%");
            });
        }

        $ongkir = $query->paginate(10);
        return view('admin.ongkir.index', compact('ongkir'));
    }

    public function create()
    {
        return view('admin.ongkir.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kota_asal' => 'required|string|max:100',
            'kota_tujuan' => 'required|string|max:100',
            'jenis_layanan' => 'required|string|max:50',
            'berat_minimum' => 'required|numeric|min:0',
            'berat_maksimum' => 'required|numeric|min:0',
            'harga_per_kg' => 'required|numeric|min:0',
            'harga_minimum' => 'required|numeric|min:0',
            'estimasi_hari' => 'required|integer|min:1',
            'status' => 'required|boolean'
        ]);

        Ongkir::create($validated);

        return redirect()->route('admin.ongkir.index')
            ->with('success', 'Ongkir berhasil ditambahkan.');
    }

    public function edit(Ongkir $ongkir)
    {
        return view('admin.ongkir.edit', compact('ongkir'));
    }

    public function update(Request $request, Ongkir $ongkir)
    {
        $validated = $request->validate([
            'kota_asal' => 'required|string|max:100',
            'kota_tujuan' => 'required|string|max:100',
            'jenis_layanan' => 'required|string|max:50',
            'berat_minimum' => 'required|numeric|min:0',
            'berat_maksimum' => 'required|numeric|min:0',
            'harga_per_kg' => 'required|numeric|min:0',
            'harga_minimum' => 'required|numeric|min:0',
            'estimasi_hari' => 'required|integer|min:1',
            'status' => 'required|boolean'
        ]);

        $ongkir->update($validated);

        return redirect()->route('admin.ongkir.index')
            ->with('success', 'Ongkir berhasil diperbarui.');
    }

    public function destroy(Ongkir $ongkir)
    {
        $ongkir->delete();
        return redirect()->route('admin.ongkir.index')
            ->with('success', 'Ongkir berhasil dihapus.');
    }
}
