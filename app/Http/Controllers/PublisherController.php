<?php

namespace App\Http\Controllers;

use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PublisherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $publishers = Publisher::all();
        return view('publishers.index', compact('publishers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('publishers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'publisher_name' => 'required|string|max:255|unique:publishers,publisher_name',
        ], [
            'publisher_name.required' => 'Publisher Name Is Required',
            'publisher_name.unique' => 'Publisher Name Already Exist'
        ]);
                if ($validator->fails()) {
                    $errors = $validator->errors();
                    return redirect()->route('publishers.index')
                                    ->withErrors($validator)
                                    ->withInput();
                }

        Publisher::create($request->all());

        return redirect()->route('publishers.index')
                        ->with('succes', 'Publisher Added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Publisher $publisher)
    {
        return view('publishers.show', compact('publisher'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Publisher $publisher)
    {
        return view('publishers.edit', compact('publisher'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Publisher $publisher)
    {
        $validator = Validator::make($request->all(), [
            'publisher_name' => 'required|string|max:255|unique:publishers,publisher_name',
        ], [
            'publisher_name.required' => 'Publisher Name Is Required',
            'publisher_name.unique' => 'Publisher Name Already Exist'
        ]);
                if ($validator->fails()) {
                    $errors = $validator->errors();
                    return redirect()->route('publishers.index')
                                    ->withErrors($validator)
                                    ->withInput();
                }


        $publisher->update($request->all());

        return redirect()->route('publishers.index')
                        ->with('success', 'Publisher Updated succesfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Publisher $publisher)
    {
        if ($publisher) {
            if ($publisher->count() > 0) {
                return redirect()->route('publishers.index')
                                ->withErrors('Tidak bisa menghapus data ini karena masih terhubung dengan buku.');
            }

            $publisher->delete();
            return redirect()->route('publishers.index')
                            ->with('success', 'Publisher deleted successfully');
        }

        return redirect()->route('publisher.index')->withErrors('Publisher tidak ditemukan.');
    }
}
