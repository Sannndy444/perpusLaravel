<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Author;
use App\Models\Category;
use App\Models\Publisher;
use App\Models\Book;
use App\Models\Types;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $book = Book::with(['author', 'category', 'publisher', 'type'])->get();

        return view('books.index', compact('book'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $author = Author::all();
        $category = Category::all();
        $publisher = Publisher::all();
        $type = Types::all();
        return view('books.create', compact('author', 'category', 'publisher', 'type'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'author_id'=> 'required|exists:authors,id',
            'category_id' => 'required|exists:categories,id',
            'publisher_id' => 'required|exists:publishers,id',
            'type_id' => 'required|exists:types,id',
            'image' => 'required|image|mimes:jpg,png,jpeg|max:2048|unique:books,image',
            'publishedYear' => 'required|string|max:255'
        ], [
            'image.required' => 'Image Cover Book Is Required',
            'image.mimes' => 'File Is Not Support',
            'image.max' => 'Size File Not Support',
            'image.unique' => 'Book Cover Image Already Use'
        ]);
            if($validator->fails()) {
                $errors = $validator->errors();
                return redirect()->route('books.index')
                                ->withErrors($validator)
                                ->withInput();
            }

            Book::create($request->all());

        return redirect()->route('books.index')
                        ->with('success', 'Book Added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        return view('books.show', compact('book'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        // $book = Book::findOrFail($book);
        $author = Author::all();
        $category = Category::all();
        $publisher = Publisher::all();
        $type = Types::all();

        return view('books.edit', compact('book', 'author', 'category', 'publisher', 'type'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {

        $request->validate([
            'title' => 'required|string|max:255',
            'author_id'=> 'required|exists:authors,id',
            'category_id' => 'required|exists:categories,id',
            'publisher_id' => 'required|exists:publishers,id',
            'type_id' => 'required|exists:types,id',
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'publishedYear' => 'required|string|max:255'
        ]);

        $existingBook = Book::where('title', $request->title)
                            ->where('id', '!=', $book->id)
                            ->first();

            if ($existingBook) {
                return redirect()->route('books.index')
                                ->withInput()
                                ->with('error', 'Failed to Edit');
            } else {
                if ($request->hasFile('image')) {
                    if ($book->image && file_exists(storage_path('app/public/books/' . $book->image))) {
                    unlink(storage_path('app/public/books/' . $book->image));
                    }

                    $image = $request->file('image');
                    $imageName = time() . '.' . $image->extension();
                    $image->storeAs('books', $imageName, 'public');
                } else {
                    $imageName = $book->image;
                }

                $book->update([
                    'title' => $request->title,
                    'author_id' => $request->author_id,
                    'category_id' => $request->category_id,
                    'publisher_id' => $request->publisher_id,
                    'type_id' => $request->type_id,
                    'image' => $imageName,
                    'publishedYear' => $request->publishedYear,
                ]);
            }

        return redirect()->route('books.index')
                        ->with('success', 'Book Updated succesfulyy');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        if ($book->image && file_exists(storage_path('app/public/books/' . $book->image))) {
            unlink(storage_path('app/public/books/' . $book->image));
        }

        $book->delete();

        return redirect()->route('books.index')
                        ->with('success', 'Book Deleted successfully');
    }
}