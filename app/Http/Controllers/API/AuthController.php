<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use Validator;
use ResponseModel;
use Auth;
use Carbon\Carbon;

class AuthController extends Controller {

  public function register(RegisterRequest $request) {
    try{
      if($request->confirm_password != $request->password){
        return ResponseModel::response(400, 'Password tidak sama', null, null);
      }
      $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
      ]);  
      if($user){
        return ResponseModel::response(200, 'Berhasil membuat akun', $user, null);
      }else{
        return ResponseModel::response(400, 'Gagal membuat akun', null, null);
      }
    }catch(\Exception $e){
      return ResponseModel::error($e->getMessage());
    }
  }
  
  public function login(LoginRequest $request) {
      $credentials = request(['email', 'password']);
      if (!Auth::attempt($credentials)) return ResponseModel::response(400, 'Email atau password salah', null, null);

      $user = $request->user();

      $token = $user->tokens()->where('name', 'Personal Access Token')->first();
      if($token != null){
        $token->delete();
        $tokenResult = $user->createToken('Personal Access Token');
      }else{
        $tokenResult = $user->createToken('Personal Access Token');
      }
      
      return response()->json([
        'user' => $user,
        'access_token' => $tokenResult->plainTextToken,
        'token_type' => 'Bearer',        
      ]);
  }
  
  public function logout(Request $request) {
    $request->user()->currentAccessToken()->delete();
    return ResponseModel::response(200, 'Berhasil logout', null, null);
  }
}

?>