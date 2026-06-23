<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rak;

class RakController extends Controller
{
    public function index()
    {
        $raks = Rak::withCount('boxes')->get();
        return view('rak.index', compact('raks'));
    }

    public function create()
    {
        return view('rak.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_rak' => 'required|unique:raks',
            'lokasi'   => 'required',
        ]);

        Rak::create($request->all());
        return redirect()->route('rak.index')->with('success', 'Rak berhasil ditambahkan!');
    }

    public function edit(Rak $rak)
    {
        return view('rak.edit', compact('rak'));
    }

    public function update(Request $request, Rak $rak)
    {
        $request->validate([
            'kode_rak' => 'required|unique:raks,kode_rak,' . $rak->id,
            'lokasi'   => 'required',
        ]);

        $rak->update($request->all());
        return redirect()->route('rak.index')->with('success', 'Rak berhasil diupdate!');
    }

    public function destroy(Rak $rak)
    {
        $rak->delete();
        return redirect()->route('rak.index')->with('success', 'Rak berhasil dihapus!');
    }

    public function show(Rak $rak)
    {
        $boxes = $rak->boxes()->withCount('dokumens')->get();
        return view('rak.show', compact('rak', 'boxes'));
    }
}