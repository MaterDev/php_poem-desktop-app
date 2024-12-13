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
    public function store(Request $request)
    {
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
    public function updateIconPosition(Request $request, Poem $poem)
    {

        $validated = $request->validate([
            'x' => 'required|numeric',
            'y' => 'required|numeric',
        ]);

        $poem->update([
            'icon_position_x' => $validated['x'],
            'icon_position_y' => $validated['y'],
        ]);

        return response()->json([
            'success' => true,
            'poem' => $poem
        ]);
    }

    public function updateWindowPosition(Request $request, Poem $poem)

    {
        $validated = $request->validate([
            'x' => 'required|numeric',
            'y' => 'required|numeric',
        ]);

        $poem->update([
            'window_position_x' => $validated['x'],
            'window_position_y' => $validated['y'],
        ]);

        return response()->json([
            'success' => true,
            'poem' => $poem
        ]);
    }

    public function updateWindowSize(Request $request, Poem $poem)
    {
        $validated = $request->validate([
            'width' => 'required|numeric|min:200',
            'height' => 'required|numeric|min:150',
        ]);

        $poem->update([
            'window_width' => $validated['width'],
            'window_height' => $validated['height'],
        ]);
        
        return response()->json([
            'success' => true,
            'poem' => $poem
        ]);
    }
}
