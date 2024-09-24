<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index()
    {
        $authors = Author::with('books')->get();
        return response()->json([
            'data'=>$authors,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'bio' => 'nullable|string',
        ]);

        $author = Author::create($validated);
        if($author){
            return response()->json([
                'data'=>$author,
                'message'=>'Data Created Successfully',
            ], 201);
        }
    }

    public function show($id)
    {
        $author = Author::with('books')->find($id);

        if (!$author) {
            return response()->json([
                'message' => 'Author not found.'
            ], 404);
        }

        return response()->json($author);
    }

    public function update(Request $request, $id)
    {
        $author = Author::find($id);

        // Check if the book exists
        if (!$author) {
            return response()->json([
                'message' => 'Book not found.'
            ], 404);
        }

        // Validate incoming request
        $validateddata = $request->validate([
                'name' => 'required|string',
                'bio' => 'required|string',
        ]);

        // Update the book with validated data
        $author->update($validateddata);


        // Return the updated book data
        return response()->json([
            'data' => $author,
            'message' => 'Author updated successfully',
        ]);
    }

    public function destroy($id)
    {
        $author = Author::find($id);
        $author->delete();
        return response()->json(null, 204);
    }
}
