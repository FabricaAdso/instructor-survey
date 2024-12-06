<script src="https://cdn.tailwindcss.com"></script>

<header class="bg-white text-gray-800 border-b border-gray-300 p-4 shadow-lg">
    <div class="container mx-auto flex justify-between items-center">
        <div class="flex justify-center">
            <img src="../img/logo-sena-verde-complementario-svg-2022.svg" alt="Logo SENA" class="w-12 h-12">
        </div>
        <h1 class="text-2xl font-semibold ml-4 flex-grow text-center md:text-left">Encuesta de Acompañamiento</h1>

        <div class="flex justify-end">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-sm text-blue-600 hover:text-blue-800">Cerrar sesión</button>
            </form>
        </div>

    </div>
</header>

<div class="flex justify-center items-center min-h-screen bg-gray-100">
    <button id="open-modal"
        class="px-6 py-3 text-white bg-[#38a901] rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
        Cargue Masivo
    </button>
</div>

<!-- Modal -->
<div id="modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md shadow-lg">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Subir Archivo Excel</h2>

        <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div class="space-y-2">
                <label for="file" class="block font-medium text-gray-700">Selecciona el archivo Excel</label>
                <input type="file" name="file" id="file" required
                    class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-gray-900 focus:outline-none focus:ring-green-500 focus:border-green-500">
            </div>
            <button type="submit"
                class="w-full py-2 px-4 bg-[#38a901] text-white font-medium rounded-lg shadow-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                Importar Excel
            </button>
        </form>

        <button id="close-modal"
            class="mt-4 w-full py-2 px-4 bg-gray-300 text-gray-800 rounded-lg shadow-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-400">
            Cancelar
        </button>
    </div>
</div>

<script>
    const modal = document.getElementById('modal');
    const openModal = document.getElementById('open-modal');
    const closeModal = document.getElementById('close-modal');

    openModal.addEventListener('click', () => {
        modal.classList.remove('hidden');
    });

    closeModal.addEventListener('click', () => {
        modal.classList.add('hidden');
    });

    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.add('hidden');
        }
    });
</script>
