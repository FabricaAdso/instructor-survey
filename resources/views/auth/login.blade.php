<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Encuesta SENA</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="flex flex-col lg:flex-row items-center justify-center min-h-screen bg-white">
        <div class="hidden w-2/5 h-full bg-cover lg:block">
            <img src="../img/imagen.png" alt="Imagen ilustrativa" class="w-80 h-auto">
        </div>

        <div class="flex items-center justify-center w-full max-w-md p-8 bg-white rounded-lg shadow-md lg:w-3/5">
            <div class="w-full space-y-6">
                <div class="flex justify-center">
                    @include('auth.logo') </div>

                <h1 class="text-2xl font-bold text-center text-gray-700">Encuesta de Acompañamiento y Satisfacción</h1>

                <!-- Formulario de inicio de sesión -->
                <form id="loginForm" method="POST" action="{{ route('login.submit') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label for="identity_document" class="block text-sm font-medium text-gray-600">Documento de
                            Identidad</label>
                        <input type="number" id="identity_document" name="identity_document"
                            placeholder="Ingresa tu documento"
                            class="block w-full px-4 py-2 mt-1 text-gray-700 bg-gray-100 border border-gray-300 rounded-lg focus:ring focus:ring-green-300 focus:outline-none"
                            required>
                    </div>
                    <div>
                        <label for="course_code" class="block text-sm font-medium text-gray-600">Ficha</label>
                        <input type="number" id="course_code" name="course_code" placeholder="Ingresa tu ficha"
                            class="block w-full px-4 py-2 mt-1 text-gray-700 bg-gray-100 border border-gray-300 rounded-lg focus:ring focus:ring-green-300 focus:outline-none"
                            required>
                    </div>
                    <button type="submit"
                        class="w-full px-4 py-2 text-white bg-[#38a901] rounded-lg hover:bg-green-600">
                        Enviar Código
                    </button>
                </form>

                <!-- Mostrar errores -->
                @if ($errors->any())
                    <div class="text-red-500 text-sm">
                        <p>{{ $errors->first() }}</p>
                    </div>
                @endif

                <div class="text-center">
                    <p class="text-sm text-gray-500">
                        Ingresar como
                        <a href="{{ route('login.admin') }}" class="text-green-500 hover:underline">Funcinario SENA</a>
                        <!-- <span class="separator">o</span>
                        <a href="{{ route('login.areaLeader') }}" class="text-green-500 hover:underline">Lider de Area</a> -->
                    </p>
                    <br><br>
                    <p class="text-sm text-gray-500">
                        Desarrollado por
                        <a href="{{ route('creditos') }}" class="text-green-500 hover:underline">Fabrica CCyS - Cauca</a>
                    </p>
                </div>
            </div>
        </div>
    </div>



    <!-- Modal de verificación -->
    <div id="verificationModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h2 class="text-lg font-bold text-center">Verificación de Código</h2>
            <p class="text-sm text-center">Se ha enviado un código a tu correo.</p>
            <p id="emailMessage" class="text-sm text-center text-gray-600"></p> <!-- Aquí se mostrará el correo -->

            <!-- Formulario de verificación -->
            <form method="POST" action="{{ route('verification.verify') }}" id="verificationForm">
                @csrf
                <input type="hidden" name="apprentice_id" id="apprenticeId">
                <input type="hidden" name="code" id="fullCode">

                <div class="flex justify-center my-4 space-x-2">
                    <input type="text" class="code-input w-12 h-12 text-center border rounded" maxlength="1"
                        required>
                    <input type="text" class="code-input w-12 h-12 text-center border rounded" maxlength="1"
                        required>
                    <input type="text" class="code-input w-12 h-12 text-center border rounded" maxlength="1"
                        required>
                    <input type="text" class="code-input w-12 h-12 text-center border rounded" maxlength="1"
                        required>
                </div>

                <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded">Verificar</button>
            </form>

            <button id="closeModal" class="mt-4 w-full text-gray-500 text-sm">Cancelar</button>
        </div>
    </div>

    <div id="errorModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h2 class="text-lg font-bold text-center text-red-600">Error</h2>
            <p id="errorMessage" class="text-sm text-center text-gray-600 mt-2"></p>
            <button id="closeErrorModal" class="mt-4 w-full bg-red-500 text-white py-2 rounded hover:bg-red-600">
                Cerrar
            </button>
        </div>
    </div>

    <script>
        const modal = document.getElementById('verificationModal');
        const closeModal = document.getElementById('closeModal');
        const inputs = document.querySelectorAll('.code-input');
        const fullCodeInput = document.getElementById('fullCode');
        const loginForm = document.getElementById('loginForm');
        const apprenticeIdInput = document.getElementById('apprenticeId');
        const emailMessage = document.getElementById('emailMessage');

        // Modal de error
        const errorModal = document.getElementById('errorModal');
        const errorMessage = document.getElementById('errorMessage');
        const closeErrorModal = document.getElementById('closeErrorModal');

        // Cerrar modal de código
        closeModal.addEventListener('click', () => {
            modal.classList.add('hidden');
        });

        // Cerrar modal de error
        closeErrorModal.addEventListener('click', () => {
            errorModal.classList.add('hidden');
        });

        // Manejo de inputs para la verificación
        inputs.forEach((input, index) => {
            input.addEventListener('input', () => {
                if (input.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && index > 0 && input.value === '') {
                    inputs[index - 1].focus();
                }
            });
        });

        // Al enviar, juntar el código en un solo input
        document.getElementById('verificationForm').addEventListener('submit', (e) => {
            let fullCode = '';
            inputs.forEach(input => {
                fullCode += input.value;
            });
            fullCodeInput.value = fullCode;
        });

        // Enviar el formulario de inicio de sesión mediante AJAX
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // Mostrar el modal de código inmediatamente
            modal.classList.remove('hidden');

            const formData = new FormData(this);

            // Enviar la solicitud al servidor en segundo plano
            fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Establecer el apprentice_id en el formulario de verificación
                        apprenticeIdInput.value = data.apprentice_id;

                        // Mostrar el correo en el modal si está presente
                        if (data.email) {
                            emailMessage.textContent = `Correo: ${data.email}`;
                        } else {
                            emailMessage.textContent = 'Correo no disponible';
                        }
                    } else {
                        // Cerrar el modal de código y mostrar el modal de error
                        modal.classList.add('hidden');
                        errorMessage.textContent = data.message || 'Error al enviar el código.';
                        errorModal.classList.remove('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    modal.classList.add('hidden');
                    errorMessage.textContent = 'Ocurrió un error. Por favor, inténtalo de nuevo.';
                    errorModal.classList.remove('hidden');
                });
        });
    </script>

    <style>
        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type="number"] {
            -moz-appearance: textfield;
            appearance: none;
        }

        /* Estilos para el modal de error */
        #errorModal {
            z-index: 1000;
        }
    </style>

</body>

</html>
