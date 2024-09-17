<?php

namespace Controllers;
use Exception;
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
        // $_POST;
        // $_GET;
        // $_REQUEST;
        // $_SERVER;
        // $_ENV;
        $files = $_FILES['archivo'];
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


        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error mostrando archivo',
                'detalle'
            ]);
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
}