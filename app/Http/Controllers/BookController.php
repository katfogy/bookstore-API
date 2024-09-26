<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

/**
 * @OA\Tag(
 *     name="Books",
 *     description="API endpoints for managing books"
 * )
 */
class BookController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/books",
     *     tags={"Books"},
     *     summary="Get all books",
     *     @OA\Response(response=200, description="List of books")
     * )
     */
    public function index()
    {
        $books = Book::with('author')->get();
        return response()->json([
            'status' => 'success',
            'data' => $books,
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/books",
     *     tags={"Books"},
     *     summary="Create a new book",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "author_id"},
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="author_id", type="integer"),
     *             @OA\Property(property="description", type="string")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Book created successfully"),
     *     @OA\Response(response=400, description="Invalid input")
     * )
     */
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
            'message' => 'Book Added Successfully'
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/books/{id}",
     *     tags={"Books"},
     *     summary="Get a book by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Book details"),
     *     @OA\Response(response=404, description="Book not found")
     * )
     */
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

    /**
     * @OA\patch(
     *     path="/api/books/{id}",
     *     tags={"Books"},
     *     summary="Update a book",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "author_id"},
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="author_id", type="integer"),
     *             @OA\Property(property="description", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Book updated successfully"),
     *     @OA\Response(response=404, description="Book not found")
     * )
     */
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
            'author_id' => 'required|integer|exists:authors,id',
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

    /**
     * @OA\Delete(
     *     path="/api/books/{id}",
     *     tags={"Books"},
     *     summary="Delete a book",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Book deleted successfully"),
     *     @OA\Response(response=404, description="Book not found")
     * )
     */
    public function destroy($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json(['message' => 'Book not found.'], 404);
        }

        $book->delete();
        return response()->json(null, 204);
    }

    /**
     * @OA\Get(
     *     path="/api/books/search",
     *     tags={"Books"},
     *     summary="Search for books",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"query"},
     *             @OA\Property(property="query", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Search results"),
     *     @OA\Response(response=404, description="No books found")
     * )
     */
    public function searchBook(Request $request)
    {
        $request->validate([
            'query' => 'required|string|max:255',
        ]);

        $searchTerm = $request->input('query');

        $books = Book::where('title', 'LIKE', '%' . $searchTerm . '%')
                     ->orWhere('description', 'LIKE', '%' . $searchTerm . '%')
                     ->get();

        if ($books->isEmpty()) {
            return response()->json(['message' => 'No books found'], 404);
        }

        return response()->json(['data' => $books]);
    }
}
