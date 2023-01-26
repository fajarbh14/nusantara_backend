<?php

namespace App\Helpers;

class ResponseModel {
  protected static $response = [
    'total_data' => 0,
    'status_code' => 0,
    'message'    => null,
    'data'       => null
  ];

  public static function response($errorCode = 0, $message = null, $data = null, $total_data = -1){
    $total_data = $total_data == -1 ? count($data) : $total_data;
    if($message == "GET"){
      $message = "Data berhasil diambil";
    }else if($message == "POST"){
      $message = "Data berhasil ditambahkan";
    }else if($message == "PUT"){
      $message = "Data berhasil diubah";
    }else if($message == "DELETE"){
      $message = "Data berhasil dihapus";
    }
    self::$response['total_data'] = $total_data;
    self::$response['status_code'] = $errorCode;
    self::$response['message'] = $message;
    self::$response['data'] = $data;
    
    return response()->json(self::$response, $errorCode);
  }
}

?>