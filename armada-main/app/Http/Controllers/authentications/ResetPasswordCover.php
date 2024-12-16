<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;

class ResetPasswordCover extends Controller
{
    public function index()
    {
        $pageConfigs = ['myLayout' => 'blank'];
        return view('content.authentications.auth-reset-password-cover', ['pageConfigs' => $pageConfigs]);
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        try {
            // Obtener el usuario
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                Log::warning('Intento de reset de contraseña para usuario no existente', [
                    'email' => $request->email
                ]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Usuario no encontrado'
                ], 404);
            }

            $token = Str::random(60);

            try {
                DB::table('password_reset_tokens')->updateOrInsert(
                    ['email' => $request->email],
                    [
                        'token' => $token,
                        'created_at' => now()
                    ]
                );
            } catch (\Exception $e) {
                Log::error('Error al guardar token de reset de contraseña', [
                    'email' => $request->email,
                    'error' => $e->getMessage()
                ]);
                throw new \Exception('Error al procesar la solicitud de reset de contraseña');
            }

            try {
                Mail::send('emails.reset-password', [
                    'user' => $user,
                    'token' => $token
                ], function($message) use ($user) {
                    $message->to($user->email);
                    $message->subject('Recuperación de Contraseña');
                });
            } catch (\Exception $e) {
                Log::error('Error al enviar correo de reset de contraseña', [
                    'email' => $request->email,
                    'error' => $e->getMessage()
                ]);
                throw new \Exception('Error al enviar el correo de recuperación');
            }

            Log::info('Correo de reset de contraseña enviado exitosamente', [
                'email' => $request->email
            ]);

            return response()->json([
                'status' => 'success',
                'message' => '¡Correo de recuperación enviado exitosamente!'
            ]);

        } catch (\Exception $e) {
            Log::error('Error general en reset de contraseña', [
                'email' => $request->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Error al procesar la solicitud: ' . $e->getMessage()
            ], 500);
        }
    }

    public function showResetForm(Request $request)
    {
        if (!$request->has('token') || !$request->has('email')) {
            abort(404);
        }

        $resetToken = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$resetToken) {
            abort(404, 'Token inválido o expirado');
        }

        $pageConfigs = ['myLayout' => 'blank'];

        return view('content.authentications.auth-reset-password-cover', [
            'pageConfigs' => $pageConfigs,
            'email' => $request->email,
            'token' => $request->token,
            'request' => $request
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        try {
            $resetToken = DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->where('token', $request->token)
                ->first();

            if (!$resetToken) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Token inválido o expirado'
                ], 400);
            }

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Usuario no encontrado'
                ], 404);
            }

            $user->password = bcrypt($request->password);
            $user->save();

            DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->delete();

            return response()->json([
                'status' => 'success',
                'message' => '¡Contraseña actualizada exitosamente!'
            ]);

        } catch (\Exception $e) {
            Log::error('Error al restablecer contraseña', [
                'email' => $request->email,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Error al restablecer la contraseña'
            ], 500);
        }
    }
}
