<h1 class="text-center">Formulario para cargar archivos</h1>

<div class="row justify-content-center">
    <form id="formArchivo" class="col-lg-4 border bg-light p-3" enctype="multipart/form-data">
        <div class="row mb-3">
            <div class="col">
                <label for="archivo">Archivo</label>
                <input type="file" accept=".pdf" name="archivo" id="archivo" class="form-control">
            </div>
        </div>
        <div class="row">
            <div class="col">
                <button type="submit" class="btn btn-primary w-100">
                    Subir
                </button>
            </div>
        </div>
    </form>
    <script src="<?= asset('./build/js/ftp/index.js') ?>"></script>
</div>