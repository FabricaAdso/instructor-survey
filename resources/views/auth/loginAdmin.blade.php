<!-- <!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrador</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
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

                <h1 class="text-2xl font-bold text-center text-gray-700">Administrador</h1>

                <form method="POST" action="{{ route('login.admin.submit') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-600">Usuario</label>
                        <input type="text" id="username" name="username"
                            placeholder="Ingresa tu nombre de usuario o documento"
                            class="block w-full px-4 py-2 mt-1 text-gray-700 bg-gray-100 border border-gray-300 rounded-lg focus:ring focus:ring-green-300 focus:outline-none"
                            required>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-600">Contraseña</label>
                        <div class="relative">
                            <input type="password" id="password" name="password" placeholder="Ingresa tu contraseña"
                                class="block w-full px-4 py-2 mt-1 text-gray-700 bg-gray-100 border border-gray-300 rounded-lg focus:ring focus:ring-green-300 focus:outline-none"
                                required>

                            <span
                                class="absolute top-1/2 right-3 transform -translate-y-1/2 cursor-pointer text-gray-500"
                                id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full px-4 py-2 text-white bg-[#38a901] rounded-lg hover:bg-[#38a901] focus:ring focus:ring-green-300">
                        INGRESAR
                    </button>
                </form>

                @if ($errors->any())
                    <div>
                        <p>{{ $errors->first() }}</p>
                    </div>
                @endif

                <div class="text-center">
                    <p class="text-sm text-gray-500">
                        Ingresar como
                        <a href="{{ route('login') }}" class="text-green-500 hover:underline">Aprendiz</a>
                        <span class="separator">o</span>
                        <a href="{{ route('login.areaLeader') }}" class="text-green-500 hover:underline">Lider de Area</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordField = document.getElementById('password');

        togglePassword.addEventListener('click', function(e) {
            const type = passwordField.type === 'password' ? 'text' : 'password';
            passwordField.type = type;

            if (type === 'password') {
                togglePassword.innerHTML = `<i class="fas fa-eye"></i>`;
            } else {
                togglePassword.innerHTML = `<i class="fas fa-eye-slash"></i>`;
            }
        });
    </script>
</body>

</html> -->


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"> --}}
</head>

<body class="bg-gray-100">
    <div class="flex flex-col lg:flex-row items-center justify-center min-h-screen bg-white">
        <div class="hidden w-2/5 h-full bg-cover lg:block">
            <img src="../img/imagen.png" alt="Imagen ilustrativa" class="w-80 h-auto">
        </div>
        <div class="flex items-center justify-center w-full max-w-md p-8 bg-white rounded-lg shadow-md lg:w-3/5">
            <div class="w-full space-y-6">
                <div class="flex justify-center">
                    @include('auth.logo')
                </div>

                <h1 class="text-2xl font-bold text-center text-gray-700" id="login-title">Iniciar Sesión</h1>

                <div class="flex justify-center mb-4">
                    <div class="inline-flex rounded-md shadow-sm" role="group">
                        <button type="button" id="admin-btn" class="px-4 py-2 text-sm font-medium text-white bg-[#38a901] rounded-l-lg focus:z-10">
                            Administrador
                        </button>
                        <button type="button" id="leader-btn" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white rounded-r-md border-t border-b border-gray-200  focus:z-10">
                            Líder de Área
                        </button>
                    </div>
                </div>

                <form method="POST" action="{{ route('login.admin.submit') }}" id="admin-form" class="space-y-4">
                    @csrf
                    <div>
                        <label for="admin-username" class="block text-sm font-medium text-gray-600">Usuario</label>
                        <input type="text" id="admin-username" name="username"
                            placeholder="Ingresa tu nombre de usuario o documento"
                            class="block w-full px-4 py-2 mt-1 text-gray-700 bg-gray-100 border border-gray-300 rounded-lg focus:ring focus:ring-green-300 focus:outline-none"
                            required>
                    </div>

                    <div>
                        <label for="admin-password" class="block text-sm font-medium text-gray-600">Contraseña</label>
                        <div class="relative">
                            <input type="password" id="admin-password" name="password" placeholder="Ingresa tu contraseña"
                                class="block w-full px-4 py-2 mt-1 text-gray-700 bg-gray-100 border border-gray-300 rounded-lg focus:ring focus:ring-green-300 focus:outline-none"
                                required>
                            <span class="absolute top-1/2 right-3 transform -translate-y-1/2 cursor-pointer text-gray-500 toggle-password" data-target="admin-password">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full px-4 py-2 text-white bg-[#38a901] rounded-lg hover:bg-[#38a901] focus:ring focus:ring-green-300">
                        INGRESAR
                    </button>
                </form>

                <form method="POST" action="{{ route('login.areaLeader.submit') }}" id="leader-form" class="space-y-4 hidden">
                    @csrf
                    <div>
                        <label for="leader-identity" class="block text-sm font-medium text-gray-600">Documento de Identidad</label>
                        <input type="text" id="leader-identity" name="identity_document"
                            placeholder="Ingresa tu número de documento"
                            class="block w-full px-4 py-2 mt-1 text-gray-700 bg-gray-100 border border-gray-300 rounded-lg focus:ring focus:ring-green-300 focus:outline-none"
                            required>
                    </div>

                    <div>
                        <label for="leader-password" class="block text-sm font-medium text-gray-600">Contraseña</label>
                        <div class="relative">
                            <input type="password" id="leader-password" name="password" placeholder="Ingresa tu contraseña"
                                class="block w-full px-4 py-2 mt-1 text-gray-700 bg-gray-100 border border-gray-300 rounded-lg focus:ring focus:ring-green-300 focus:outline-none"
                                required>
                            <span class="absolute top-1/2 right-3 transform -translate-y-1/2 cursor-pointer text-gray-500 toggle-password" data-target="leader-password">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full px-4 py-2 text-white bg-[#38a901] rounded-lg hover:bg-[#38a901] focus:ring focus:ring-green-300">
                        INGRESAR
                    </button>
                </form>

                @if ($errors->any())
                    <div class="p-4 text-sm text-red-700 bg-red-100 rounded-lg">
                        <p>{{ $errors->first() }}</p>
                    </div>
                @endif

                <div class="text-center">
                    <p class="text-sm text-gray-500">
                        Ingresar como
                        <a href="{{ route('login') }}" class="text-green-500 hover:underline">Aprendiz</a>
                    </p>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.getElementById('admin-btn').addEventListener('click', function() {
            document.getElementById('login-title').textContent = 'Administrador';
            document.getElementById('admin-form').classList.remove('hidden');
            document.getElementById('leader-form').classList.add('hidden');
            document.getElementById('admin-btn').classList.add('text-white', 'bg-[#38a901]');
            document.getElementById('admin-btn').classList.remove('text-gray-900', 'bg-white');
            document.getElementById('leader-btn').classList.remove('text-white', 'bg-[#38a901]');
            document.getElementById('leader-btn').classList.add('text-gray-900', 'bg-white');
        });

        document.getElementById('leader-btn').addEventListener('click', function() {
            document.getElementById('login-title').textContent = 'Líder de Área';
            document.getElementById('leader-form').classList.remove('hidden');
            document.getElementById('admin-form').classList.add('hidden');
            document.getElementById('leader-btn').classList.add('text-white', 'bg-[#38a901]');
            document.getElementById('leader-btn').classList.remove('text-gray-900', 'bg-white');
            document.getElementById('admin-btn').classList.remove('text-white', 'bg-[#38a901]');
            document.getElementById('admin-btn').classList.add('text-gray-900', 'bg-white');
        });

        document.querySelectorAll('.toggle-password').forEach(function(toggle) {
            toggle.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const passwordField = document.getElementById(targetId);
                const isPassword = passwordField.type === 'password';

                passwordField.type = isPassword ? 'text' : 'password';
                this.innerHTML = isPassword ? '<i class="fas fa-eye-slash"></i>' : '<i class="fas fa-eye"></i>';
            });
        });
    </script>

</body>
</html>
