<?php

namespace App\Http\Controllers;

use App\Models\Items;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemsController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->has('search') && $request->search !== null) {
            $search = strtolower($request->search);
            $items = Items::whereRaw('LOWER(name) LIKE ?', ['%'.$search.'%'])
                ->orderByRaw('LOWER(name) ASC')
                ->paginate(10)
                ->appends($request->only('search'));
        } else {
            $items = Items::orderByRaw('LOWER(name) ASC')->paginate(10);
        }

        return view('items.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('items.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif',
            'stock' => 'required|integer|max:20000',
            'price' => 'required|numeric|max:100000000000',
        ]);
        
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images/items', 'public');
        }

        Items::create([
            'name' => $request->name,
            'image' => $imagePath ?? null,
            'stock' => $request->stock,
            'price' => $request->price,
        ]);

        return redirect()->route('items.index')->with('message', 'Item created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Items $item)
    {
        return view('items.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Items $item)
    {
        return view('items.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Items $item)
    {
        $request->validate([
            'name' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'stock' => 'required|integer|max:20000',
            'price' => 'required|numeric|max:100000000000',
        ]);

        $data = [
            'name' => $request->name,
            'stock' => $request->stock,
            'price' => $request->price,
        ];

        if ($request->hasFile('image')) {
            if ($item->image && Storage::disk('public')->exists($item->image)) {
                Storage::disk('public')->delete($item->image);
            }

            $imagePath = $request->file('image')->store('images/items', 'public');
            $data['image'] = $imagePath;
        }

        $item->update($data);

        return redirect()->route('items.index')->with('success', 'Item updated successfully.');
    }

    /**
     * Updates a specific item stock
     */
    public function updateStock(Request $request, $id)
    {
        $request->validate([
            'stock' => 'required|integer|min:0',
        ]);

        $item = Items::findOrFail($id);
        $item->stock = $request->stock;
        $item->save();

        return redirect()->route('items.index')->with('message', 'Stock updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Items $item)
    {
        if ($item->image && Storage::disk('public')->exists($item->image)) {
            Storage::disk('public')->delete($item->image);
        }

        $item->delete();

        return redirect()->route('items.index')->with('success', 'Item deleted successfully.');
    }
}
