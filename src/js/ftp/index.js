import { Toast } from "../funciones";

const formulario = document.getElementById('formArchivo')


const subir = async (e) => {
    e.preventDefault();

    if (!formulario.archivo.files[0]) {
        Toast.fire({
            icon: "warning",
            title: "Debe cargar un archivo",
        })
        return;
    }

    try {
        const body = new FormData(formulario)
        const url = '/ejemplopdf/subir'
        const config = {
            method: 'POST',
            body
        }

        const respuesta = await fetch(url, config)
        const data = await respuesta.json()

        const { codigo, mensaje, detalle } = data

        if (codigo == 1) {
            Toast.fire({
                icon: "success",
                title: mensaje,
            })

            formulario.reset();
        } else {
            Toast.fire({
                icon: "error",
                title: mensaje,
            });
            console.log(detalle);
        }
    } catch (error) {
        console.log(error);
    }
}
formulario.addEventListener('submit', subir)