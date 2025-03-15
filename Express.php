<?php
namespace Express;


class FileSystem
{
  public $conn;
  function __construct($conn)
  {
      $this->conn = $conn;
  }
  function controlador($file_name)
  {
    $controllerName = basename($file_name, ".php"); // '/' -> 'homeController.php' -> 'homeController'
    $controllers_folder = __DIR__ . '/../app/controllers';
    $archivo = $controllers_folder . '/' . $controllerName . '.php'; //Ruta completa del archivo
    
    if (file_exists($archivo)) {
        require_once $archivo;
        $controllerName = ucfirst($controllerName); //Pongo en mayusculas la primera letra ya que es el nombre de la Clase
        $controller = new $controllerName($this->conn); //instancio el controlador, que por la funcion __construct por defecto tambien instancio el modelo (user.php) para poder las funciones que manejan la base de datos
        $controller->handle(); // Metodo del controlador que maneja la logica y manda vista (la funcion se llama igual para todos los controladores)
    }
    else
    {
        http_response_code(404);
        echo "archivo no encontrado";
    }
  }
  //crearDirectorio('/ruta/a/mi/directorio');
  function crearDirectorio($ruta) 
  {
    if (!file_exists($ruta)) 
    {
      if (mkdir($ruta, 0777, true)) 
      {
          return true;
      } 
      else 
      {
          return false;
      }
    } 
    else 
    {
      return false;
    }
  }
  //eliminarDirectorio('/ruta/a/mi/directorio');
  function eliminarDirectorio($ruta) 
  {
    if (file_exists($ruta)) 
    {
        if (rmdir($ruta)) 
        {
            return true;
        }
        else 
        {
            return false;
        }
    } 
    else 
    {
        return false;
    }
  }
  //crearArchivo('/ruta/a/mi/archivo.txt', 'Contenido del archivo');
  function crearArchivo($ruta, $contenido = '')
  {
      if (!file_exists($ruta)) 
      {
          if (file_put_contents($ruta, $contenido) !== false) 
          {
              return true;
          } 
          else
          {
              return false;
          }
      } 
      else 
      {
          return false;
      }
  }
  //leerArchivo('/ruta/a/mi/archivo.txt');
  function leerArchivo($ruta) 
  {//LEER ARCHIVO
    if (file_exists($ruta)) 
    {
        $contenido = file_get_contents($ruta);
        if ($contenido !== false) 
        {
            return $contenido;
        } 
        else 
        {
            return false;
        }
    } 
    else 
    {
        return false;
    }
  }
  //añadir contenido
  //escribirArchivo('/ruta/a/mi/archivo.txt', "\nNuevo contenido", FILE_APPEND);
  //sobre escribir
  //escribirArchivo('/ruta/a/mi/archivo.txt', "Contenido nuevo", 0);
  function escribirArchivo($ruta, $contenido, $modo = FILE_APPEND) 
  {
    if (file_exists($ruta)) 
    {
        if (file_put_contents($ruta, $contenido, $modo) !== false) 
        {
            return true;
        } 
        else
        {
            return false;
        }
    }
    else
    {
        return false;
    }
  }
  //eliminarArchivo('/ruta/a/mi/archivo.txt');
  function eliminarArchivo($ruta) 
  {
    if (file_exists($ruta)) 
    {
      if (unlink($ruta)) 
      {
          return true;
      } 
      else 
      {
          return false;
      }
    } 
    else 
    {
        return false;
    }
  }
  //renombrar('/ruta/a/mi/archivo_antiguo.txt', '/ruta/a/mi/archivo_nuevo.txt');
  function renombrar($rutaAntigua, $rutaNueva) 
  {
    if (file_exists($rutaAntigua)) 
    {
      if (rename($rutaAntigua, $rutaNueva)) 
      {
          return true;
      } 
      else 
      {
          return false;
      }
    } 
    else
    {
        return false;
    }
  }
  //esDirectorio('/ruta/a/mi/directorio');
  function esDirectorio($ruta) 
  {
    if (is_dir($ruta)) 
    {
      return true;
    }
    else
    {
      return false;
    }
  }
  //esArchivo('/ruta/a/mi/archivo.txt');
  function esArchivo($ruta) 
  {
    if (is_file($ruta)) 
    {
        return "Es un archivo: " . $ruta;
    } 
    else
    {
        return "No es un archivo: " . $ruta;
    }
  }
  //obtenerInfoArchivo('/ruta/a/mi/archivo.txt');
  function obtenerInfoArchivo($ruta) 
  {
    if (file_exists($ruta)) 
    {
        return print_r(stat($ruta), true);
    }
    else
    {
        return "El archivo no existe: " . $ruta;
    }
  }
  //guardarArchivoBinario('/ruta/a/tu/copia.mp3', $datosMP3);
  function guardarArchivoBinario($ruta, $datosBinarios) 
  {
      if (file_put_contents($ruta, $datosBinarios, LOCK_EX) !== false) 
      {
          return true;
      } 
      else
      {
          return false;
      }
  }
  //$datosMP3 = cargarArchivoBinario('/ruta/a/tu/copia.mp3');
  function cargarArchivoBinario($ruta) 
  {
    if (file_exists($ruta)) 
    {
      $datosBinarios = file_get_contents($ruta);
      if ($datosBinarios !== false) 
      {
        return $datosBinarios; // Devuelve los datos binarios
      } 
      else
      {
        return false;
      }
    } 
    else
    {
      return false;
    }
  }
}
class App
{
  public $rutas = [];
  public $conn;
  public $fs;
  
  function __construct($conn)
  {
    $this->conn = $conn;
    $this->fs = new FileSystem($conn);
  }  
  function add_rute($method, $rute, $callback)
  {
    $this->rutas[$method][$rute] = $callback;
  }
  function req($method, $ruta)
  {
    $req = [];
    if($method == "GET")
    {
      $req["form"] = $_GET;
    }
    if($method == "POST")
    {
      $req["form"] = $_POST;
      $req["files"] = $_FILES;
    }
    if($method == "PUT")
    {
      $req["form"] = json_decode(file_get_contents('php://input'));
    }
    if($method == "PATCH")
    {
      $req["form"] = json_decode(file_get_contents('php://input'));
    }
    if($method == "DELETE")
    {
      $req["form"] = json_decode(file_get_contents('php://input'));
    }
    return $req;
  }
  function call_rute()
  {
    $metodo = $_SERVER['REQUEST_METHOD'];
    $ruta = strtok($_SERVER['REQUEST_URI'], '?');
    if (isset($this->rutas[$metodo][$ruta])) 
    {
        $callback = $this->rutas[$metodo][$ruta];
        if (is_callable($callback)) 
        {
            return $callback($this->req($metodo, $ruta), $this->conn, $this->fs);
        } 
        else 
        {
            return 'Callback no válido';
        }
    } 
    else 
    {
        return 'Ruta no encontrada';
    }
  }
}
?>