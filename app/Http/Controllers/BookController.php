<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookModel;
use App\Traits\ApiResponse;
use App\Http\Resources\BookResource;
use App\Http\Requests\CreateBookRequest;


class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use ApiResponse;

    public function index(Request $request)
    {
        try {
            $limit = ($request->limit)?$request->limit:5;
            $books = BookModel::select('*');
            if ($request->srch)
                $books->where('title','ilike', $request->srch);

            $bookCollection = BookResource::collection(
                $books->paginate($limit)->appends(request()->only(['limit','srch']))
            );
            return $this->setListResponse(
                'books',
                $bookCollection,
                200,
                "Book List"
            );

        } catch (\Exception $e) {
            return $this->setResponse(null, $e->getCode(), $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateBookRequest $request)
    {
        try {
            $book = BookModel::create($request->all());
            return $this->setResponse($book, 200, "Success Create Book");
        } catch (\Exception $e) {
            return $this->setResponse(null, $e->getCode(), $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $book = BookModel::find($id);

            if(!$book) return $this->setResponse(array("id"=>$request->id), 404, "Data Not Found");

            $book->update($request->all());

            return $this->setResponse($book, 200, "Success Update Book id:".$id);

        } catch (\Exception $e) {
            dd($e->getMessage());
            return $this->setResponse(null, $e->getCode(), $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $book = BookModel::findOrFail($id);
            $book->delete();
            return $this->setResponse(null, 200, "Success delete book id:".$id);
        } catch (\Exception $e) {
            return $this->setResponse(null, 400, "Book Not Found");
        }
    }
}
