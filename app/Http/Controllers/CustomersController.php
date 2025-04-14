<?php

namespace App\Http\Controllers;

use App\Models\cr;
use Illuminate\Http\Request;

class CustomersController
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
            'name' => 'required|string|max:255',
            'no_hp' => 'nullable|email|max:20',
            'address' => 'nullable|string|max:500',
            'points' => 'nullable|integer|min:0',
        ]);

        Customer::create([
            'name' => $request->name,
            'no_hp' => $request->no_hp,
            'address' => $request->address,
            'points' => $request->points ?? 0,
        ]);

        return redirect()->route('customers.index')->with('success', 'Customer created successfully!');
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
            'name' => 'required|string|max:255',
        ]);

        $customer->update($request->all());

        return redirect()->route('customers.index')->with('message', 'Customer updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(cr $cr)
    {
        $customer->delete();

        return redirect()->route('customers.index')->with('message', 'Customer deleted.');
    }
}
