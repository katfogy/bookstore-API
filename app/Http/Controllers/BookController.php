<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::with('author')->get();
        return response()->json([
            'status' => 'success',
            'data' => $books,
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|unique:books',
            'author_id' => 'required|exists:authors,id',
            'description' => 'nullable|string',
        ]);

        $book = Book::create($validated);
        return response()->json([
            'status' => 'success',
            'data' => $book,
            'message'=>'Book Added Successfully'
        ], 201);
    }

    public function show($id)
    {
        $book = Book::with('author')->find($id);

        if (!$book) {
            return response()->json([
                'message' => 'Book not found.'
            ], 404);
        }

        return response()->json($book);
    }

    public function update(Request $request, $id)
    {
    $book = Book::find($id);

    // Check if the book exists
    if (!$book) {
        return response()->json([
            'message' => 'Book not found.'
        ], 404);
    }

    // Validate incoming request
    $validateddata = $request->validate([
            'title' => 'required|string',
            'author_id' => 'required|integer',
            'description' => 'nullable|string',
    ]);

    // Update the book with validated data
    $book->update($validateddata);


    // Return the updated book data
    return response()->json([
        'data' => $book,
        'message' => 'Book updated successfully',
    ]);
    }

    public function destroy($id)
    {
        $book = Book::find($id);
        $book->delete();
        return response()->json(null, 204);
    }


    public function searchBook(Request $request)
    {
        $request->validate([
            'query' => 'required|string|max:255',
        ]);

        $searchTerm = $request->input('query');

        $books = Book::where('title', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('description', 'LIKE', '%' . $searchTerm . '%')
                    ->get();
        if($books){
            return response()->json(['data'=>$books]);
        }
        return response()->json(['message'=>'Book not found']);

    }
}
