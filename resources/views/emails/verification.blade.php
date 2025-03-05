<!-- <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Código de Verificación</title>
</head>
<body>
    <p>Hola,</p>
    <p>Tu código de verificación es: <strong>{{ $code }}</strong></p>
    <p>Este código expirará en 10 minutos.</p>
</body>
</html> -->


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7f9;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .email-container {
            width: 100%;
            max-width: 650px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background-color: #7CB305;
            color: #fff;
            padding: 30px 20px;
            text-align: center;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }
        .header h1 {
            margin: 0;
            font-size: 26px;
        }
        .content {
            padding: 30px 20px;
        }
        .content p {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        .center{
            text-align: center;
        }

        .footer {
            text-align: center;
            font-size: 14px;
            color: #777;
            margin-top: 20px;
            padding: 20px 10px;
            background-color: #f9f9f9;
            border-bottom-left-radius: 12px;
            border-bottom-right-radius: 12px;
        }
        .footer p {
            margin: 0;
        }
        .footer a {
            color: #7CB305;
            text-decoration: none;
        }
        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="email-container">
        <div class="header">
            <h1>Código de verificación</h1>
        </div>

        <div class="content">
            <p class="center">Hola,</p>
            <p class="center">Tu código de verificación para realizar la encuensta es:</p>
            <div class="center">
            <strong>{{ $code }}</strong>
            </div>
            <p class="center">Si no solicitaste este código, puedes ignorar este correo de forma segura.</p>
        </div>

        <div class="footer">
            <p>Saludos,<br>El Equipo de Soporte</p>
            <p>Si necesitas ayuda adicional, por favor <a href="mailto:pruebasfabricaccys@gmail.com">contáctanos</a>.</p>
        </div>
    </div>

</body>
</html>
