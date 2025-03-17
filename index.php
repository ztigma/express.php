<?php

session_start();

require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/Express.php';

use Express\App;
use Express\View;

require_once __DIR__ . '/../app/views/UploadFiles.php';
use Express\UploadFiles;

require_once __DIR__ . '/../app/views/Head.php';
use Express\Head;


$app = new App($conn);

$app->add_rute
(
  'GET'
  ,
  '/'
  ,
  function($req, $conn, $fs)
  {
    return $fs->controlador("homeController.php");
  }
);
$app->add_rute
(
  'GET'
  ,
  '/login'
  ,
  function($req, $conn, $fs)
  {
    return $fs->controlador("loginController.php");
  }
);
$app->add_rute
(
  'POST'
  ,
  '/login'
  ,
  function($req, $conn, $fs)
  {
    return $fs->controlador("loginController.php");
  }
);
$app->add_rute
(
  'GET'
  ,
  '/registro'
  ,
  function($req, $conn, $fs)
  {
    return $fs->controlador("registroController.php");
  }
);
$app->add_rute
(
  'POST'
  ,
  '/registro'
  ,
  function($req, $conn, $fs)
  {
    return $fs->controlador("registroController.php");
  }
);
$app->add_rute
(
  'POST'
  ,
  '/closeSesion'
  ,
  function($req, $conn, $fs)
  {
    return $fs->controlador("closeSesion.php");
  }
);
$app->add_rute
(
  'GET'
  ,
  '/verificarMail'
  ,
  function($req, $conn, $fs)
  {
    return $fs->controlador("emails/verificarMailController.php");
  }
);
$app->add_rute
(
  'GET'
  ,
  '/verArchivos'
  ,
  function($req, $conn, $fs)
  {
    return $fs->controlador("verArchivosController.php");
  }
);
$app->add_rute
(
  'GET'
  ,
  '/arduino'
  ,
  function($req, $conn, $fs)
  {
    return $fs->controlador("arduinoController.php");
  }
);
$app->add_rute
(
  'GET'
  ,
  '/cargar_archivos'
  ,
  function($req, $conn, $fs)
  {
    return "" . new Head() . new UploadFiles();
    //return json_encode($_SESSION);{"usuario": "romel"}
  }
);
$app->add_rute
(
  'POST'
  ,
  '/cargar_archivos'
  ,
  function($req, $conn, $fs)
  {
    $archivosSubidos = $req['files']['archivos'];
    $rutaDestino = __DIR__ . '/../app/private/users/' . $_SESSION['usuario'];
    
    return $fs->guardarArchivosSubidos($archivosSubidos, $rutaDestino);
  }
);
$app->add_rute
(
  'GET'
  ,
  '/descargar_archivos'
  ,
  function($req, $conn, $fs)
  {
      $rutaDestino = __DIR__ . '/../app/private/users/' . $_SESSION['usuario'] . '/' . $req['form']['file_name'];
      $archivo = $fs->cargarArchivoBinario($rutaDestino);
      if($archivo)
      {
        $tipoMime = mime_content_type($rutaDestino);

        // Configurar las cabeceras
        header('Content-Type: ' . $tipoMime);
        header('Content-Disposition: attachment; filename="' . $req['form']['file_name'] . '"');
        header('Content-Length: ' . strlen($archivo));
        header('Content-Transfer-Encoding: binary');
        header('Accept-Ranges: bytes');
        
        return $archivo;
      }
      else
      {
        return 'error no hay archivo'; 
      }
  }
);
echo $app->call_rute();
?>