<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BodaBoda;
use Illuminate\Http\Request;

class BodaBodaController extends Controller
{
    public function index()
    {
        $bodabodas = BodaBoda::latest()->paginate(10);
        return view('admin.bodabodas.index', compact('bodabodas'));
    }

    public function create()
    {
        return view('admin.bodabodas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('/images/bodas', 'public');
        }

        BodaBoda::create($data);

        return redirect()->route('admin.bodabodas.index')
            ->with('success', 'BodaBoda created successfully.');
    }

    public function show(BodaBoda $bodaboda)
    {
        return view('admin.bodabodas.show', compact('bodaboda'));
    }

    public function edit(BodaBoda $bodaboda)
    {
        return view('admin.bodabodas.edit', compact('bodaboda'));
    }

    public function update(Request $request, BodaBoda $bodaboda)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('bodabodas', 'public');
        }

        $bodaboda->update($data);

        return redirect()->route('admin.bodabodas.index')
            ->with('success', 'BodaBoda updated successfully.');
    }

    public function destroy(BodaBoda $bodaboda)
    {
        $bodaboda->delete();
        return redirect()->route('admin.bodabodas.index')
            ->with('success', 'BodaBoda deleted successfully.');
    }
}
