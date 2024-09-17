<?php

namespace Controllers;
use Exception;
use Model\ActiveRecord;
use Model\Producto;
use MVC\Router;
use phpseclib3\Net\SFTP;

class FTPController
{
    public static function subir(Router $router)
    {
        $router->render('ftp/index');
    }
    public static function subirAPI()
    {
        $db = Producto::getDB();

        $db->beginTransaction();
        $files = $_FILES['archivo'];
        try {

            $producto = new Producto([
                'nombre' => "prueba",
                'precio' => 500
            ]);

            $producto->crear();

            $ftpServer = $_ENV['FILE_SERVER'];
            $ftpUsername = $_ENV['FILE_USER'];
            $ftpPassword = $_ENV['FILE_PASSWORD'];
            $remoteFilePath = $_ENV['FILE_DIR'];

            $sftp = new SFTP($ftpServer);
            $conectado = $sftp->login($ftpUsername, $ftpPassword);

            if (!$conectado) {
                throw new Exception('No se pudo conectar', 500);
            }

            // echo json_encode('conectado');

            $nombre = uniqid();
            $partes = explode('.', $files['name']);
            $extension = $partes[1];
            $ruta = $remoteFilePath . $nombre . ".$extension";

            $subido = $sftp->put($ruta, $files['tmp_name'], SFTP::SOURCE_LOCAL_FILE);

            if ($subido) {
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Archivo subido correctamente',
                ]);
            } else {
                throw new Exception("No se subio el archivo: " . error_get_last()['message'] . $files['name'], 500);
            }
            $sftp->disconnect();


            $db->rollBack();

        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error subiendo archivo',
                'detalle'
            ]);

            $db->rollBack();
        }
    }

    public static function subirLocalAPI()
    {
        $files = $_FILES['archivo'];
        $nombre = uniqid();
        $partes = explode('.', $files['name']);
        $extension = $partes[1];
        $ruta = __DIR__ . "/../storage/" . $nombre . ".$extension";

        $subido = move_uploaded_file($files['tmp_name'], $ruta);
        if ($subido) {
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Archivo subido correctamente',
            ]);
        } else {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'No se subio el archivo',
            ]);
        }
        echo json_encode($ruta);
    }

    public static function mostrarLocal()
    {
        $ruta = __DIR__ . '/../storage/66e9e71176e1a.pdf';
        if (file_exists($ruta)) {
            $mimeType = mime_content_type($ruta);
            $fileData = file_get_contents($ruta);
            $base64 = base64_encode($fileData);
            $dataUrl = 'data:' . $mimeType . ';base64,' . $base64;

            echo '<iframe src="' . $dataUrl . '" width="100%" height="600px"></iframe>';
        } else {
            echo "El archivo no existe.";
        }
    }

    public static function mostrar()
    {
        try {
            $ftpServer = $_ENV['FILE_SERVER'];
            $ftpUsername = $_ENV['FILE_USER'];
            $ftpPassword = $_ENV['FILE_PASSWORD'];
            $remoteFilePath = $_ENV['FILE_DIR'];

            $sftp = new SFTP($ftpServer);
            $conectado = $sftp->login($ftpUsername, $ftpPassword);

            if (!$conectado) {
                throw new Exception('No se pudo conectar', 500);
            }

            $ruta = $remoteFilePath . "66e9e4e91bd10.pdf";

            $fileData = $sftp->get($ruta);

            // $mimeType = mime_content_type($fileData);


            if ($fileData != false) {
                $base64 = base64_encode($fileData);
                $dataUrl = 'data:' . "application/pdf" . ';base64,' . $base64;

                echo '<iframe src="' . $dataUrl . '" width="100%" height="600px"></iframe>';
            } else {
                throw new Exception('No se pudo obtener el archivo', 500);
            }

        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error mostrando archivo',
                'detalle'
            ]);
        }
    }
}