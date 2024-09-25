<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Authors",
 *     description="API endpoints for managing authors"
 * )
 */
class AuthorController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/authors",
     *     tags={"Authors"},
     *     summary="Get all authors",
     *     @OA\Response(response=200, description="List of authors")
     * )
     */
    public function index()
    {
        $authors = Author::with('books')->get();
        return response()->json(['data' => $authors]);
    }

    /**
     * @OA\Post(
     *     path="/api/authors",
     *     tags={"Authors"},
     *     summary="Create a new author",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="bio", type="string")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Author created successfully"),
     *     @OA\Response(response=400, description="Invalid input")
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'bio' => 'nullable|string',
        ]);

        $author = Author::create($validated);
        return response()->json(['data' => $author, 'message' => 'Data Created Successfully'], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/authors/{id}",
     *     tags={"Authors"},
     *     summary="Get an author by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Author details"),
     *     @OA\Response(response=404, description="Author not found")
     * )
     */
    public function show($id)
    {
        $author = Author::with('books')->find($id);

        if (!$author) {
            return response()->json(['message' => 'Author not found.'], 404);
        }

        return response()->json($author);
    }

    /**
     * @OA\Put(
     *     path="/api/authors/{id}",
     *     tags={"Authors"},
     *     summary="Update an author",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "bio"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="bio", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Author updated successfully"),
     *     @OA\Response(response=404, description="Author not found")
     * )
     */
    public function update(Request $request, $id)
    {
        $author = Author::find($id);

        if (!$author) {
            return response()->json(['message' => 'Author not found.'], 404);
        }

        $validateddata = $request->validate([
            'name' => 'required|string',
            'bio' => 'required|string',
        ]);

        $author->update($validateddata);

        return response()->json(['data' => $author, 'message' => 'Author updated successfully']);
    }

    /**
     * @OA\Delete(
     *     path="/api/authors/{id}",
     *     tags={"Authors"},
     *     summary="Delete an author",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Author deleted successfully"),
     *     @OA\Response(response=404, description="Author not found")
     * )
     */
    public function destroy($id)
    {
        $author = Author::find($id);

        if (!$author) {
            return response()->json(['message' => 'Author not found.'], 404);
        }

        $author->delete();
        return response()->json(null, 204);
    }

    /**
     * @OA\Get(
     *     path="/api/authors/search",
     *     tags={"Authors"},
     *     summary="Search for authors",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"query"},
     *             @OA\Property(property="query", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Search results"),
     *     @OA\Response(response=404, description="Author not found")
     * )
     */
    public function searchAuthor(Request $request)
    {
        $request->validate(['query' => 'required|string|max:255']);
        $searchTerm = $request->input('query');

        $authors = Author::where('name', 'LIKE', '%' . $searchTerm . '%')
            ->orWhere('bio', 'LIKE', '%' . $searchTerm . '%')
            ->get();

        if ($authors->isEmpty()) {
            return response()->json(['message' => 'Author not found'], 404);
        }

        return response()->json(['data' => $authors]);
    }
}
