<?php

namespace App\Http\Controllers;

use App\Models\Label;
use Illuminate\Http\Request;

class LabelController extends Controller
{
    public function index()
    {
        $labels = auth()->user()->labels()->withCount('tasks')->get();
        return view('labels.index', compact('labels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|min:2|max:50',
            'color' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        auth()->user()->labels()->create($request->only('name', 'color'));

        return redirect()->route('labels.index')
            ->with('success', 'Label criada!');
    }

    public function update(Request $request, Label $label)
    {
        $this->authorize($label);

        $request->validate([
            'name'  => 'required|min:2|max:50',
            'color' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        $label->update($request->only('name', 'color'));

        return redirect()->route('labels.index')
            ->with('success', 'Label atualizada!');
    }

    public function destroy(Label $label)
    {
        $this->authorize($label);
        $label->delete();

        return redirect()->route('labels.index')
            ->with('success', 'Label eliminada!');
    }

    private function authorize(Label $label)
    {
        if ($label->user_id !== auth()->id()) {
            abort(403);
        }
    }
}
