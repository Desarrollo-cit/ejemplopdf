<?php

namespace Controllers;
use Exception;
use phpseclib3\Net\SFTP;
use MVC\Router;
class FTPController
{
    public static function subir(Router $router)
    {
        $router->render('ftp/index');
    }
    public static function subirAPI()
    {
        try {
            $ftpServer = $_ENV['FILE_SERVER'];
            $ftpUsername = $_ENV['FILE_USER'];
            $ftpPassword = $_ENV['FILE_PASSWORD'];
            $remoteFilePath = $_ENV['FILE_DIR'];
            $file = $_FILES['archivo'];

            $sftp = new SFTP($ftpServer);
            $sftp->login($ftpUsername, $ftpPassword);

            $nombre = uniqid();
            $ruta = $remoteFilePath . $nombre . ".pdf";

            $subido = $sftp->put($ruta, $file['tmp_name'], SFTP::SOURCE_LOCAL_FILE);
            if ($subido) {
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Archivo subido correctamente',
                ]);
            } else {
                throw new Exception("No se subio el archivo: " . error_get_last()['message'] . $file['name'], 500);
            }

            $sftp->disconnect();
        } catch (Exception $e) {

            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error generando trámite',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function subirLocalAPI()
    {
        $file = $_FILES['archivo'];
        $nombre = uniqid();
        $ruta = __DIR__ . '/../storage/' . $nombre . ".pdf";

        $subido = move_uploaded_file($file["tmp_name"], $ruta);

        if ($subido) {
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Archivo subido correctamente',
            ]);
        } else {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error subiendo archivo',
            ]);
        }
    }

    public static function mostrarLocal()
    {

        $ruta = __DIR__ . '/../storage/66e9c146b87a2.pdf';

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

            if (!$sftp->login($ftpUsername, $ftpPassword)) {
                throw new Exception('Falló la autenticación SFTP');
            }

            $ruta = $remoteFilePath . "66e9bf0d30d3d.pdf";



            $fileData = $sftp->get($ruta);


            if ($fileData != false) {
                $base64 = base64_encode($fileData);
                $dataUrl = 'data:application/pdf;base64,' . $base64;

                echo '<iframe src="' . $dataUrl . '" width="100%" height="600px"></iframe>';
            } else {
                throw new Exception('No se pudo obtener el archivo del servidor SFTP.');
            }


            $sftp->disconnect();
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error mostrando archivo',
                'detalle' => $e->getMessage()
            ]);
        }

    }
}