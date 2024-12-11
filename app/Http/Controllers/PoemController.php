<?php

namespace App\Http\Controllers;

use App\Models\Poem;
use Illuminate\Http\Request;

class PoemController extends Controller
{
    public function index()
    {
        $poems = Poem::all();
        return view('poems.index', ['poems' => $poems]);
    }

    // Create new record for poem when submitted
    public function store(Request $request) {
        $poem = Poem::create([
            'title' => $request->title,
            'content' => $request->content,
            'position_x' => $request->position_x ?? 0,
            'position_y' => $request->position_y ?? 0,
        ]);

        return response()->json($poem);
    }
}
