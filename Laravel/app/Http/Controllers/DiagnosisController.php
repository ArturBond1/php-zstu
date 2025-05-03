<?php

namespace App\Http\Controllers;

use App\Models\Diagnosis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class DiagnosisController extends Controller
{
    protected $perPageOptions = [10, 25, 50, 100];
    protected $defaultPerPage = 10;

    public function index(Request $request)
    {
        $perPage = $request->input('perPage', $this->defaultPerPage);
        $diagnoses = Diagnosis::query()
            ->when($request->filled('name'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->input('name') . '%');
            })
            ->when($request->filled('description'), function ($query) use ($request) {
                $query->where('description', 'like', '%' . $request->input('description') . '%');
            })
            ->paginate($perPage)
            ->appends($request->query());

        $perPageOptions = $this->perPageOptions;
        $defaultPerPage = $this->defaultPerPage;

        return view('diagnoses.index', compact('diagnoses', 'perPageOptions', 'defaultPerPage')); // Додайте $defaultPerPage в compact()
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
