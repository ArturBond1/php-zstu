<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class DoctorController extends Controller
{

    public function index()
    {
        $doctors = Doctor::all();
        return view('doctors.index', compact('doctors'));
    }

    public function create()
    {
        return view('doctors.create');
    }

    public function store(Request $request)
    {
        Doctor::create($request->all());
        return Redirect::route('doctors.index')->with('success', 'Лікаря додано.');
    }

    public function show(Doctor $doctor)
    {
        return view('doctors.show', compact('doctor'));
    }

    public function edit(Doctor $doctor)
    {
        return view('doctors.edit', compact('doctor'));
    }

    public function update(Request $request, Doctor $doctor)
    {
        $doctor->update($request->all());
        return Redirect::route('doctors.index')->with('success', 'Інформацію про лікаря оновлено.');
    }

    public function destroy(Doctor $doctor)
    {
        $doctor->delete();
        return Redirect::route('doctors.index')->with('success', 'Лікаря видалено.');
    }
}
