<?php

namespace App\Http\Controllers;

use App\Models\cr;
use Illuminate\Http\Request;

class ItemsController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif',
            'stock' => 'required|integer',
            'price' => 'required|numeric',
        ]);
        
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images/items', 'public');
        }

        Item::create([
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
    public function show(cr $cr)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(cr $cr)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, cr $cr)
    {
        $request->validate([
            'name' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'stock' => 'required|integer',
            'price' => 'required|numeric',
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

        $item = Item::findOrFail($id);
        $item->stock = $request->stock;
        $item->save();

        return redirect()->route('items.index')->with('message', 'Stock updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(cr $cr)
    {
        if ($item->image && Storage::disk('public')->exists($item->image)) {
            Storage::disk('public')->delete($item->image);
        }

        $item->delete();

        return redirect()->route('items.index')->with('success', 'Item deleted successfully.');
    }
}
