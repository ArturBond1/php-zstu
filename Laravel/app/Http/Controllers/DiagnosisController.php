<?php

namespace App\Http\Controllers;

use App\Models\Diagnosis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class DiagnosisController extends Controller
{

    public function index()
    {
        $diagnoses = Diagnosis::all();
        return view('diagnoses.index', compact('diagnoses'));
    }


    public function create()
    {
        return view('diagnoses.create');
    }

    public function store(Request $request)
    {
        Diagnosis::create($request->all());
        return Redirect::route('diagnoses.index')->with('success', 'Діагноз додано.');
    }

    public function show(Diagnosis $diagnosis)
    {
        return view('diagnoses.show', compact('diagnosis'));
    }


    public function edit(Diagnosis $diagnosis)
    {
        return view('diagnoses.edit', compact('diagnosis'));
    }

    public function update(Request $request, Diagnosis $diagnosis)
    {
        $diagnosis->update($request->all());
        return Redirect::route('diagnoses.index')->with('success', 'Інформацію про діагноз оновлено.');
    }

    public function destroy(Diagnosis $diagnosis)
    {
        $diagnosis->delete();
        return Redirect::route('diagnoses.index')->with('success', 'Діагноз видалено.');
    }
}
