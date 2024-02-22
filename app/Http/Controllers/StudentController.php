<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use App\Models\StudentModel;
use App\Http\Resources\StudentResource;
use App\Http\Requests\StudentRequest;


class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use ApiResponse;

    public function index(Request $request)
    {
        try {
            $limit = ($request->limit)?$request->limit:5;
            $books = StudentModel::select('*');
            if ($request->srch)
                $books->where('name','ilike', $request->srch);

            $bookCollection = StudentResource::collection(
                $books->paginate($limit)->appends(request()->only(['limit','srch']))
            );
            return $this->setListResponse(
                'student',
                $bookCollection,
                200,
                "Student List"
            );

        } catch (\Exception $e) {
            // dd($e->getMessage());
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
    public function store(StudentRequest $request)
    {
        try {
            $book = StudentModel::create($request->all());
            return $this->setResponse($book, 200, "Success create student record");
        } catch (\Exception $e) {
            return $this->setResponse(null, $e->getCode(), $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $book = StudentModel::findOrFail($id);
            return $this->setResponse($book, 200, "Detail student record");
        } catch (\Exception $e) {
            return $this->setResponse(null, 400, "Not found");
        }
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
            $book = StudentModel::findOrFail($id);

            if(!$book) return $this->setResponse(array("id"=>$request->id), 404, "Data Not Found");

            $book->update($request->all());

            return $this->setResponse($book, 200, "Success Update Book id:".$id);

        } catch (\Exception $e) {
            return $this->setResponse(null, $e->getCode(), $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $book = StudentModel::findOrFail($id);
            $book->delete();
            return $this->setResponse(null, 200, "Success delete student record id:".$id);
        } catch (\Exception $e) {
            return $this->setResponse(null, 400, "Student data not found");
        }
    }
}
