<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookModel;
use App\Traits\ApiResponse;
use App\Http\Resources\BookResource;
use App\Http\Requests\CreateBookRequest;


class BookV2Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use ApiResponse;

    public function index(Request $request)
    {
        try {
            $limit = ($request->limit)?$request->limit:5;
            $books = BookModel::select('*')->paginate($limit);
            
            // $bookCollection = BookResource::collection(
            //     $books->paginate($limit)->appends(request()->only(['limit','srch']))
            // );
            return response()->xml($books);

        } catch (\Exception $e) {
            $resp = [
                'status'=>'Bad Request',
                'code'=>'400',
                'message'=>$e->getMessage()
            ];
            return response()->xml($resp);
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
    public function store(Request $request)
    {
        try {
            $data = $this->convertXMLtoArray($request->getContent());
            $array = BookModel::create($data);
            // return $this->setResponse($array, 200, "Success Create Book");
            return response()->xml($array);

        } catch (\Exception $e) {
            $resp = [
                'status'=>'Bad Request',
                'code'=>'400',
                'message'=>$e->getMessage()
            ];
            return response()->xml($resp);
        }
    }

    public function convertXMLtoArray($xml)
    {
        $data = simplexml_load_string($xml);
        $json = json_encode($data);
        $array = json_decode($json,TRUE);   
        return $array;
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
            $resp = [
                'status'=>'Bad Request',
                'code'=>'404',
                'message'=>'Data Not Found'
            ];
            if(!$book) return response()->xml($resp);;

            $data = $this->convertXMLtoArray($request->getContent());
            $book->update($data);

            return response()->xml($book);

        } catch (\Exception $e) {
            // dd($e->getMessage());
            $resp = [
                'status'=>'Bad Request',
                'code'=>'400',
                'message'=>$e->getMessage()
            ];
            return response()->xml($resp);
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
            return response()->xml($book);
        } catch (\Exception $e) {
            $resp = [
                'status'=>'Bad Request',
                'code'=>'400',
                'message'=>$e->getMessage()
            ];
            return response()->xml($resp);
        }
    }
}
