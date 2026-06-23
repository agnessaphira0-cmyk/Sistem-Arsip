<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Box;
use App\Models\Rak;

class BoxController extends Controller
{
    public function index()
    {
        $boxes = Box::with('rak')->withCount('dokumens')->get();
        return view('box.index', compact('boxes'));
    }

    public function create()
    {
        $raks = Rak::all();
        return view('box.create', compact('raks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_box'  => 'required|unique:boxes',
            'rak_id'    => 'required|exists:raks,id',
            'kapasitas' => 'required|integer|min:1',
        ]);

        Box::create($request->all());
        return redirect()->route('box.index')->with('success', 'Box berhasil ditambahkan!');
    }

    public function edit(Box $box)
    {
        $raks = Rak::all();
        return view('box.edit', compact('box', 'raks'));
    }

    public function update(Request $request, Box $box)
    {
        $request->validate([
            'kode_box'  => 'required|unique:boxes,kode_box,' . $box->id,
            'rak_id'    => 'required|exists:raks,id',
            'kapasitas' => 'required|integer|min:1',
        ]);

        $box->update($request->all());
        return redirect()->route('box.index')->with('success', 'Box berhasil diupdate!');
    }

    public function destroy(Box $box)
    {
        $box->delete();
        return redirect()->route('box.index')->with('success', 'Box berhasil dihapus!');
    }

    public function show(Box $box)
    {
        $dokumens = $box->dokumens()->latest()->get();
        return view('box.show', compact('box', 'dokumens'));
    }
}