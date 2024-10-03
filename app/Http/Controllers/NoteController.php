<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Services\NoteService;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    //

    public function index(Request $request)
    {

        $noteService = new NoteService($request);

        if(!$request->query('sort_by_date')){

            $notes = $noteService->getAll();

            return response()->json([
                'notes' => $notes
            ]);
        }

        $sort_by = $request->query('sort_by_date');

        $notes = $noteService->getAll_ordered_by_date($sort_by);

        return response()->json([
            'notes'     => $notes,
            'order_by'  => $sort_by
        ]);
        
    }
    public function store(Request $request)
    {
        $noteService = new NoteService($request);

        $note = $noteService->create();

        return response()->json([
            'note'   => $note
        ], 201);

    }
    public function show($id, Request $request)
    {

        $noteService = new NoteService($request);
        $note = $noteService->get_note($id);

        return response()->json([
            'note'  => $note
        ]);


    }
    public function update(Note $note, Request $request)
    {
        $noteService = new NoteService($request);
        $note_updated = $noteService->update($note);

        return response()->json([
            'note'  => $note_updated
            ]);


    }
    public function destroy(Note $note, Request $request)
    {
        $noteService = new NoteService($request);
        $noteService->delete($note);

        return response()->noContent(200);

    }
}
