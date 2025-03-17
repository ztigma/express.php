<?php
namespace Express;


class FileSystem
{
  public $conn;
  function __construct($conn)
  {
      $this->conn = $conn;
  }
    function guardarArchivosSubidos($archivos, $rutaDestino) {
        if (!is_array($archivos) || empty($archivos) || !is_string($rutaDestino) || empty($rutaDestino)) {
            return 'parametro fallido'; // Validación de parámetros
        }
    
        if (!is_dir($rutaDestino)) {
            if (!mkdir($rutaDestino, 0777, true)) {
                return 'error al crear la carpeta'; // Error al crear la carpeta
            }
        }
    
        foreach ($archivos['tmp_name'] as $key => $tmpName) 
        {
            $nombreArchivo = basename($archivos['name'][$key]);
            $rutaCompleta = $rutaDestino . '/' . $nombreArchivo;

            if (!move_uploaded_file($tmpName, $rutaCompleta)) 
            {
                return 'error al mover el archivo'; // Error al mover el archivo
            }
        }
    
        return 'subida exitosa'; // Subida exitosa
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
class View {
    public $tag = "div";
    public $attributes = [];
    public $children = [];
    public $style = [];
    private static $contador = 0;

    public function __construct() {
        $this->attributes = [
            "id" => $this->getClassName() . "_" . $this->ID(),
            "_view" => $this->getClassName(),
            "_orientation" => "vertical_center",
            "onclick" => "/*onclick*/",
            "onchange" => "/*onchange*/",
            "_onloading" => 'true'
        ];
        $this->style = [
            "--h" => "auto",
            "--w" => "auto",
            "--h_phone" => "auto",
            "--w_phone" => "auto",
            '--box_shadow' => '2vw',
            '--box_shadow_color' => 'black',
            '--border_radius' => '0px',
            '--border_radius_phone' => '0px',
            '--font_size' => '1vw',
            '--padding' => '0px',
            '--padding_phone' => '0px',
            '--margin' => 'auto',
            '--margin_phone' => 'auto'
        ];
    }

    private function getClassName() {
        return "View";
    }

    private function ID() 
    {
        return ++self::$contador;
    }

    public function SetImg($src) 
    {
        $this->Style(['background' => "url('$src') center center no-repeat"]);
        return $this;
    }

    public function SetImgContain($src) 
    {
        $this->Style(['background' => "url('$src') center center / contain no-repeat"]);
        return $this;
    }

    public function SetBackImgContain($src) 
    {
        $this->Style([
            'background-image' => "url('$src')",
            'background-size' => 'contain',
            'background-repeat' => 'no-repeat',
            'background-position' => 'center'
        ]);
        return $this;
    }

    public function SetImgDefault($src) 
    {
        $this->Style(['background' => "url('$src')"]);
        $this->Style(['background-position' => '0% 83%']);
        return $this;
    }

    public function Tag($n) {
        $this->tag = $n;
        return $this;
    }

    public function Children($n) {
        $this->children = $n;
        return $this;
    }

    public function Children_add($n) {
        foreach ($n as $c) {
            $this->children[] = $c;
        }
        return $this;
    }

    public function Attributes($n) {
        $this->attributes = array_merge($this->attributes, $n);
        return $this;
    }

    public function Style($n) {
        $this->style = array_merge($this->style, $n);
        return $this;
    }

    public function AttributesToHtml() {
        $attributesHtml = "";
        foreach ($this->attributes as $key => $value) {
            $attributesHtml .= "$key=\"$value\" ";
        }
        return trim($attributesHtml);
    }

    public function StyleToHtml() {
        $styleHtml = "";
        foreach ($this->style as $key => $value) {
            $styleHtml .= "$key:$value;";
        }
        return $styleHtml ? "style=\"$styleHtml\"" : "";
    }

    public function __toString() {
        $attributesHtml = $this->AttributesToHtml();
        $styleHtml = $this->StyleToHtml();
        $combinedAttributes = $attributesHtml . ($styleHtml ? ' ' . $styleHtml : '');

        if ($this->tag == 'input' || $this->tag == "img") {
            return "<{$this->tag} {$combinedAttributes}>";
        } else {
            $childrenHtml = is_array($this->children) ? implode('', array_map(function ($child) {
                return (string) $child;
            }, $this->children)) : (string) $this->children;
            return "<{$this->tag} {$combinedAttributes}>{$childrenHtml}</{$this->tag}>";
        }
    }
}

trait StringExtensions {
    public function HTML_TO_TTR() {
        preg_match_all('/([^"]*)="([^"]*)"/', $this, $matches);
        if (empty($matches[0])) {
            return [];
        }
        $list = $matches[0];
        $list[0] = explode(' ', $list[0])[1];
        $ttr = [];
        foreach ($list as $n) {
            $split = explode('=', $n);
            $ttr[$split[0]] = substr($split[1], 1, -1);
        }
        return $ttr;
    }

    public function HTML_TO_CHILDREN() {
        preg_match('/>([\s\S]*)</', $this, $matches);
        return isset($matches[1]) ? $matches[1] : '';
    }

    public function toView() {
        $view = new View();
        $view->attributes = $this->HTML_TO_TTR();
        $view->children = $this->HTML_TO_CHILDREN();
        $view->attributes['id'] = $view->attributes['_view'] . "_" . ++View::$contador;
        return $view;
    }

    public function PROPS($props)
    {
        $result = $this;
        foreach ($props as $key => $value) 
        {
            // Reemplazar tanto __name__ como /*name*/
            $result = preg_replace("/(__" . $key . "__|\/\*" . $key . "\*\/)/", $value, $result);
        }
        return $result;
    }
}

trait ObjectExtensions {
    public function TO_INPUTS() {
        $inputPrefab = "<input id=\"__name__\" name=\"__name__\" value=\"__value__\" style=\"display: none;\">";
        $inputs = "";
        foreach ((array) $this as $key => $value) {
            $inputs .= $inputPrefab->PROPS(['name' => $key, 'value' => $value]);
        }
        return $inputs;
    }
}

// Agregar los traits a las clases String y Object.
String::class;
Object::class;
trait_exists('StringExtensions') && trait_exists('ObjectExtensions') && class_alias('StringExtensions','String');
trait_exists('StringExtensions') && trait_exists('ObjectExtensions') && class_alias('ObjectExtensions','Object');

// Ejemplo de uso:
//$view = new View();
//$view->Tag('p')->Children('¡Hola, mundo!')->Style(['color' => 'blue']);
//echo $view;

//$htmlString = '<div id="test" class="_view" data-attr="value">Child content</div>';
//$viewFromString = $htmlString->toView();
//echo $viewFromString;

//$dataObject = (object) ['name' => 'John', 'age' => 30];
//echo $dataObject->TO_INPUTS();

?>