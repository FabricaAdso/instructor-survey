<form action="{{ route('import') }}" method="POST" enctype="multipart/form-data" class="import-form">
    @csrf
    <div class="form-group">
        <label for="file" class="form-label">Selecciona el archivo Excel</label>
        <input type="file" name="file" id="file" class="form-input" required>
    </div>
    <button type="submit" class="submit-button">Importar Excel</button>
</form>

<style>
    /* Estilos generales del formulario */
.import-form {
    max-width: 500px;
    margin: 0 auto;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    font-family: Arial, sans-serif;
}

/* Estilo de los campos de entrada */
.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    font-weight: bold;
    margin-bottom: 8px;
    color: #333;
}

.form-input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 16px;
    background-color: #fff;
    color: #333;
    transition: border-color 0.3s ease;
}

.form-input:focus {
    border-color: #4CAF50;
    outline: none;
}

/* Estilo del botón de envío */
.submit-button {
    width: 100%;
    padding: 12px;
    font-size: 18px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.submit-button:hover {
    background-color: #45a049;
}

.submit-button:focus {
    outline: none;
}

/* Estilo de errores (si los hay) */
.has-error {
    color: red;
    font-size: 14px;
    margin-top: 10px;
}

</style>
