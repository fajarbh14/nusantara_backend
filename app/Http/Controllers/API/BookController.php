<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Http\Requests\Book\StoreRequest;
use App\Http\Requests\Book\UpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Book;
use ResponseModel;
use Validator;
use DB;

class BookController extends Controller {

  public function getById(Request $request,$id){
    $userId = $request->user()->id;
    $data = Book::find($id);
    if($userId != $data->user_id){
      return ResponseModel::response(400,'Gagal Menampilkan Data',null,null);
    }else{
      $data->where('user_id',$userId);
      return ResponseModel::response(200,"GET",$data,1); 
    }
  }

  public function getAll(Request $request){
    $userId = $request->user()->id;
    if(request()->query('title') != null){
      $data = Book::where(DB::raw('LOWER(title)'), 'LIKE', '%' . strtolower(request()->query('title')) . '%')
        ->where('user_id',$userId)
        ->get();
    }else{
      $data = Book::where('user_id',$userId)->paginate(10);  
    }
    return ResponseModel::response(200,"GET",$data,$data->count());
  }

  public function store(StoreRequest $request){
    DB::beginTransaction();
    $user = $request->user();
    try{
      $data = Book::create([
        'user_id'       => $user->id, 
        'isbn'          => $request['isbn'],
        'title'         => $request['title'],
        'subtitle'      => $request['subtitle'],
        'author'        => $request['author'],
        'published'     => $request['published'],
        'publisher'     => $request['publisher'],
        'pages'         => $request['pages'],
        'description'   => $request['description'],
        'website'       => $request['website']
      ]);
      DB::commit();
      return ResponseModel::response(200,'POST',$data,null);
    }catch(Exception $e){
      return ResponseModel::response(400,'Gagal Menambahkan Data',null,null);
    }
  }

  public function update(UpdateRequest $request,$id){
    DB::beginTransaction();
    $userId = $request->user()->id;
    $data = Book::findOrFail($id);
    try{
      if($userId != $data->user_id){
        return ResponseModel::response(400,'Gagal Mengubah Data',null,null);
      }else{
        $data->update([
          'user_id'       => $userId,
          'isbn'          => $request['isbn'],
          'title'         => $request['title'],
          'subtitle'      => $request['subtitle'],
          'author'        => $request['author'],
          'published'     => $request['published'],
          'publisher'     => $request['publisher'],
          'pages'         => $request['pages'],
          'description'   => $request['description'],
          'website'       => $request['website']
        ]);

        DB::commit();
        if($data) return ResponseModel::response(200,'PUT',$data,null);
      }
    }catch(Exception $e){
      return ResponseModel::response(500,'Gagal Mengubah Data',null,null);
    }
  }

  public function delete(Request $request,$id){
    DB::beginTransaction();
    $userId = $request->user()->id;
    $data = Book::findOrFail($id);
    try 
    {
      if($userId != $data->user_id){
        return ResponseModel::response(400,'Gagal Mengubah Data',null,null);
      }else{
        $data = Book::findOrFail($id);
        $data->delete();
        DB::commit();
        return ResponseModel::response(400,"DELETE",null,null);
      }
    }
    catch (Exception $e) 
    {
        DB::rollback();
        return response()->json(["status_code" => 500, "message" => $e->getMessage(), "data" => null]);
    }
  }


}