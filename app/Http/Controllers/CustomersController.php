<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use Illuminate\Http\Request;

class CustomersController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->has('search') && $request->search !== null) {
            $search = strtolower($request->search);
            $customers = Customers::whereRaw('LOWER(name) LIKE ?', ['%'.$search.'%'])
                ->paginate(10)
                ->appends($request->only('search'));
        } else {
            $customers = Customers::paginate(10);
        }

        return view('customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'no_hp' => 'nullable|string|min:10|max:20',
            'address' => 'nullable|string|max:500',
            'points' => 'nullable|integer|min:0|max:1000000000',
        ]);

        if(Customers::where('no_hp', $request->no_hp)->exists()) {
            return redirect()->route('customers.index')->with('error', 'Customer with this Phone Number already exists!');
        }

        Customers::create([
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
    public function show(Customers $customer)
    {
        return view('customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customers $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customers $customer)

    {
        $request->validate([
            'name' => 'required|string|max:255',
            'no_hp' => 'nullable|string|min:10|max:20',
            'address' => 'nullable|string|max:500',
            'points' => 'nullable|integer|min:0|max:1000000000',
        ]);

        if ($request->filled('no_hp') && Customers::where('no_hp', $request->no_hp)->where('id', '!=', $customer->id)->exists()) {
            return redirect()->route('customers.index')->with('error', 'Customer with this Phone Number already exists!');
        }

        $data = $request->only(['name', 'address', 'points']);

        $data['no_hp'] = $request->filled('no_hp') ? $request->no_hp : $customer->no_hp;

        $customer->update($data);

        return redirect()->route('customers.index')->with('message', 'Customer updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customers $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index')->with('message', 'Customer deleted.');
    }
}
