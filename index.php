<?php

session_start();

require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/Express.php';

use Express\App;
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
  'GET'
  ,
  '/register'
  ,
  function($req, $conn, $fs)
  {
    return $fs->controlador("registroController.php");
  }
);
$app->add_rute
(
  'GET'
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
echo $app->call_rute();
?>