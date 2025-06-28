<?php

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
