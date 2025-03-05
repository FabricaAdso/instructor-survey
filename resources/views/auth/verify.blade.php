<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Código</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .verification-container {
            text-align: center;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .code-inputs {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }

        .code-inputs input {
            width: 50px;
            height: 50px;
            margin: 0 5px;
            text-align: center;
            font-size: 18px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .code-inputs input:focus {
            outline: none;
            border-color: #007bff;
        }

        button {
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .error-message {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="verification-container">
        <form method="POST" action="{{ route('verification.verify') }}" id="verificationForm">
            @csrf
            <input type="hidden" name="apprentice_id" value="{{ $apprenticeId }}">
            <input type="hidden" name="code" id="fullCode">
            <p>Se ha enviado un código a tu correo. Ingresa el código:</p>
            <div class="code-inputs">
                <input type="text" name="code1" maxlength="1" required>
                <input type="text" name="code2" maxlength="1" required>
                <input type="text" name="code3" maxlength="1" required>
                <input type="text" name="code4" maxlength="1" required>
            </div>
            <button type="submit">Verificar</button>
        </form>
        @if ($errors->any())
            <p class="error-message">{{ $errors->first() }}</p>
        @endif
    </div>

    <script>
        const inputs = document.querySelectorAll('.code-inputs input');
        const fullCodeInput = document.getElementById('fullCode');
        const form = document.getElementById('verificationForm');

        form.addEventListener('submit', (e) => {
            let fullCode = '';
            inputs.forEach(input => {
                fullCode += input.value;
            });
            fullCodeInput.value = fullCode;
        });

        inputs.forEach((input, index) => {
            input.addEventListener('input', (e) => {
                if (e.target.value.length > 0 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && index > 0 && e.target.value.length === 0) {
                    inputs[index - 1].focus();
                }
            });
        });
    </script>
</body>
</html>
