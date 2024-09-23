<?php

namespace App\Http\Controllers;

use App\Livewire\Tables\LabourTable;
use Illuminate\Http\Request;
use App\Models\Labour;

class LabourController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $labours = Labour::all();

        return view('labours.index', [
            'labours' => $labours,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('labours.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Labour::create([
            "name" => $request->name,
            "email" => $request->email,
            "phone" => $request->phone,
        ]);

        return redirect()
            ->route('labours.index')
            ->with('success', 'Labour has been created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Labour $labour)
    {
        return view('labours.show', [
            'labour' => $labour
        ]);
    }

    public function edit(Labour $labour)
    {
        return view('labours.edit', [
            'labour' => $labour
        ]);
    }

    public function update(UpdateLabourRequest $request, Labour $labour)
    {
        $labour->update([
            "name" => $request->name,
            "email" => Str::slug($request->name)
        ]);

        return redirect()
            ->route('labours.index')
            ->with('success', 'Labour has been updated!');
    }

    public function destroy(Labour $labour)
    {
        $labour->delete();

        return redirect()
            ->route('labours.index')
            ->with('success', 'Labour has been deleted!');
    }
}
