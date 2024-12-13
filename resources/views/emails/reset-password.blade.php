<!DOCTYPE html>
<html>
<head>
    <title>Recuperación de Contraseña - Armada de Colombia</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="font-family: 'Helvetica Neue', Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f5f5f5;">
    <!-- Contenedor principal -->
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        <!-- Encabezado con logo -->
        <div style="background-color: #003875; padding: 20px; text-align: center;">
            <img src="{{ asset('images/logo-armada.png') }}" alt="Armada de Colombia" style="max-width: 200px; height: auto;">
        </div>

        <!-- Contenido principal -->
        <div style="padding: 40px 30px;">
            <div style="border-left: 4px solid #003875; padding-left: 15px; margin-bottom: 30px;">
                <h2 style="color: #003875; margin: 0;">Hola {{ $user->name }},</h2>
            </div>

            <p style="color: #555; font-size: 16px;">Has solicitado restablecer tu contraseña para tu cuenta en el sistema de la Armada de Colombia. Por favor, haz clic en el siguiente botón para continuar con el proceso:</p>

            <div style="text-align: center; margin: 40px 0;">
                <a href="{{ url('auth/reset-password-cover?token=' . $token . '&email=' . $user->email) }}"
                   style="background-color: #003875; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block; border: 2px solid #003875; transition: all 0.3s;">
                    Restablecer Contraseña
                </a>
            </div>

            <div style="background-color: #f8f9fa; border-left: 4px solid #ffd700; padding: 15px; margin: 20px 0;">
                <p style="margin: 0; color: #666;">
                    <strong>Nota de seguridad:</strong> Este enlace expirará en 60 minutos por motivos de seguridad.
                    Si no has solicitado este cambio, por favor ignora este correo.
                </p>
            </div>
        </div>

        <!-- Pie de página -->
        <div style="background-color: #f8f9fa; padding: 20px; text-align: center; border-top: 1px solid #eee;">
            <img src="{{ asset('images/escudo-armada.png') }}" alt="Escudo Armada" style="max-width: 80px; margin-bottom: 15px;">
            <p style="color: #666; font-size: 14px; margin: 0;">
                Armada de Colombia<br>
                <small style="color: #999;">Este es un correo automático, por favor no responder</small>
            </p>
        </div>
    </div>

    <!-- Nota legal -->
    <div style="max-width: 600px; margin: 20px auto; text-align: center;">
        <p style="color: #999; font-size: 12px;">
            Este correo electrónico y cualquier archivo transmitido con él son confidenciales y destinados únicamente para el uso de la persona o entidad a quien están dirigidos.
        </p>
    </div>
</body>
</html>
