<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use App\Models\EmployeeModel;
use App\Http\Resources\EmployeeResource;
use App\Http\Requests\EmployeeRequest;


class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use ApiResponse;

    public function index(Request $request)
    {
        try {
            $limit = ($request->limit)?$request->limit:5;
            $books = EmployeeModel::select('*');
            if ($request->srch)
                $books->where('full_name','ilike', $request->srch);

            $bookCollection = EmployeeResource::collection(
                $books->paginate($limit)->appends(request()->only(['limit','srch']))
            );
            return $this->setListResponse(
                'employees',
                $bookCollection,
                200,
                "Employee List"
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
    public function store(EmployeeRequest $request)
    {
        try {
            $book = EmployeeModel::create($request->all());
            return $this->setResponse($book, 200, "Success create employee record");
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
            $book = EmployeeModel::findOrFail($id);
            return $this->setResponse($book, 200, "Detail employee record");
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
            $book = EmployeeModel::findOrFail($id);

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
            $book = EmployeeModel::findOrFail($id);
            $book->delete();
            return $this->setResponse(null, 200, "Success delete employee record id:".$id);
        } catch (\Exception $e) {
            return $this->setResponse(null, 400, "Employee data not found");
        }
    }
}
