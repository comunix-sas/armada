<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class LoginCover extends Controller
{

  public function register(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:users',
      'password' => 'required|string|min:8'
    ]);
    if ($validator->fails()) {
      return response()->json($validator->errors(), 422);
    }

    $user = User::create([
      'name' => $request->name,
      'email' => $request->email,
      'password' => Hash::make($request->password)
    ]);
    $token = $user->createToken('auth_token')->plainTextToken;
    return response()
      ->json(['data' => $user, 'access_token' => $token, 'token_type' => 'bearer'], 201);
  }

  public function index()
  {
    $pageConfigs = ['myLayout' => 'blank'];
    return view('content.authentications.auth-login-cover', ['pageConfigs' => $pageConfigs]);
  }

  public function login(Request $request)
  {
    $request->validate([
      'email-username' => 'required',
      'password' => 'required',
    ]);

    $fieldType = filter_var($request->input('email-username'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
    $credentials = [
      $fieldType => $request->input('email-username'),
      'password' => $request->input('password'),
    ];

    if (Auth::attempt($credentials, $request->filled('remember'))) {
      $request->session()->regenerate();

      $user = Auth::user();
      $token = $user->createToken('auth_token')->plainTextToken;

      return redirect('/dashboard/analytics');

    }
    return redirect('/login');
  }

  public function logout(Request $request)
  {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/login');
  }

  public function sendPasswordResetEmail(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'email' => 'required|email|exists:users,email'
    ]);

    if ($validator->fails()) {
      return response()->json([
        'status' => 'error',
        'message' => 'El correo electrónico no es válido o no existe'
      ], 422);
    }

    $token = Str::random(60);

    DB::table('password_reset_tokens')->updateOrInsert(
      ['email' => $request->email],
      [
        'token' => Hash::make($token),
        'created_at' => now()
      ]
    );

    // Enviar el correo con el código
    Mail::send('emails.reset-password', ['token' => $token], function ($message) use ($request) {
      $message->to($request->email);
      $message->subject('Restauración de Contraseña');
    });

    return response()->json([
      'status' => 'success',
      'message' => 'Se ha enviado un correo con las instrucciones para restaurar la contraseña'
    ]);
  }

  public function resetPassword(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'email' => 'required|email|exists:users,email',
      'token' => 'required',
      'password' => 'required|min:8|confirmed'
    ]);

    if ($validator->fails()) {
      return response()->json([
        'status' => 'error',
        'message' => 'Datos inválidos'
      ], 422);
    }

    $resetRecord = DB::table('password_reset_tokens')
      ->where('email', $request->email)
      ->first();

    if (!$resetRecord || !Hash::check($request->token, $resetRecord->token)) {
      return response()->json([
        'status' => 'error',
        'message' => 'Token inválido'
      ], 422);
    }

    User::where('email', $request->email)->update([
      'password' => Hash::make($request->password)
    ]);

    DB::table('password_reset_tokens')->where('email', $request->email)->delete();

    return response()->json([
      'status' => 'success',
      'message' => 'Contraseña actualizada correctamente'
    ]);
  }
}
