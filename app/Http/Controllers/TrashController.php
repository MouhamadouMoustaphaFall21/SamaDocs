<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TrashController extends Controller
{
    public function index(Request $request)
    {
        $trashed = $request->user()->documents()
            ->onlyTrashed()
            ->with('category')
            ->latest()
            ->get();

        return view('trash.index', compact('trashed'));
    }

    public function restore(Request $request, $id)
    {
        $document = $request->user()->documents()->onlyTrashed()->findOrFail($id);
        $document->restore();

        return redirect()->route('trash.index')->with('success', 'Document restauré');
    }

    public function forceDelete(Request $request, $id)
    {
        $document = $request->user()->documents()->onlyTrashed()->findOrFail($id);
        $document->forceDelete();

        return redirect()->route('trash.index')->with('success', 'Document supprimé définitivement');
    }
}
