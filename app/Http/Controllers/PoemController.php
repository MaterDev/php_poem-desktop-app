<?php

namespace App\Http\Controllers;

use App\Models\Poem;
use Illuminate\Http\Request;

class PoemController extends Controller
{

    /**
     * Show the list of poems.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $poems = Poem::all();
        return view('poems.index', ['poems' => $poems]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $poem = Poem::create([
            'title' => $request->title,
            'content' => $request->content,
            'position_x' => $request->position_x ?? 0,
            'position_y' => $request->position_y ?? 0,
        ]);

        return response()->json($poem);
    }

    /**
     * Update the position of the given poem.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Poem  $poem
     * @return \Illuminate\Http\Response
     */
    public function updatePosition(Request $request, Poem $poem)
    {

        $validate = $request->validate([
            'x' => 'required|numeric',
            'y' => 'required|numeric',
        ]);

        $poem->update([
            'position_x' => $validate['x'],
            'position_y' => $validate['y'],
        ]);

        return response()->json([
            'success' => true,
            'poem' => $poem
        ]);
    }
}
