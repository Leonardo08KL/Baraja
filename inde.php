<?php
session_start();

$img = "";
$_SESSION['imagenes'][] = "a";

$miArreglo = $_SESSION['imagenes'];

$i = 1;
while ($i <= 5) {
  $img = getNombreImg($img);
  $repetido = repetidos($img, $miArreglo);

  if ($repetido == false) {
    $_SESSION['imagenes'][] = $img;
    $i++;
  }
}

if (isset($_SESSION['imagenes'])) {
  $miArreglo = $_SESSION['imagenes'];
  $miArregloJSON = json_encode($miArreglo);

  $longituArray = count($_SESSION['imagenes']);
  if ($longituArray < 60) session_destroy();
} else {
  echo "El arreglo de sesión no existe";
}

function repetidos($img, $miArreglo)
{

  if (!in_array($img, $miArreglo)) {
    return false;
  } else {
    return true;
  }
}

function getNombreImg($img)
{
  $min = 1;
  $max = 13;
  $characters = 'CDPT';

  $randomNumber = rand($min, $max);
  $randomChar = $characters[rand(0, strlen($characters) - 1)];

  $image = $randomNumber . "" . $randomChar;

  return $image;
}


?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <title>Page Title</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
  <script type="text/javascript">
    function carga() {
      posicion = 0;
      elMovimiento = null;

      // IE
      if (
        navigator.userAgent.indexOf("MSIE") >= 0 ||
        navigator.userAgent.indexOf("Trident") >= 0
      )
        navegador = 0;
      // Otros
      else navegador = 1;
    }

    function evitaEventos(event) {
      // Funcion que evita que se ejecuten eventos adicionales
      if (navegador == 0) {
        window.event.cancelBubble = true;
        window.event.returnValue = false;
      }
      if (navegador == 1) event.preventDefault();
    }

    function comienzoMovimiento(event, id) {
      elMovimiento = document.getElementById(id);
      if (navegador == 0) {
        cursorComienzoX =
          window.event.clientX +
          document.documentElement.scrollLeft +
          document.body.scrollLeft;
        cursorComienzoY =
          window.event.clientY +
          document.documentElement.scrollTop +
          document.body.scrollTop;

        document.attachEvent("onmousemove", enMovimiento);
        document.attachEvent("onmouseup", finMovimiento);
      }
      if (navegador == 1) {
        cursorComienzoX = event.clientX + window.scrollX;
        cursorComienzoY = event.clientY + window.scrollY;
        document.addEventListener("mousemove", enMovimiento, true);
        document.addEventListener("mouseup", finMovimiento, true);
      }

      elComienzoX = parseInt(elMovimiento.style.left);
      elComienzoY = parseInt(elMovimiento.style.top);
      // Actualizo el posicion del elemento
      elMovimiento.style.zIndex = ++posicion;
      evitaEventos(event);
    }

    function enMovimiento(event) {
      var xActual, yActual;
      if (navegador == 0) {
        xActual =
          window.event.clientX +
          document.documentElement.scrollLeft +
          document.body.scrollLeft;
        yActual =
          window.event.clientY +
          document.documentElement.scrollTop +
          document.body.scrollTop;
      }
      if (navegador == 1) {
        xActual = event.clientX + window.scrollX;
        yActual = event.clientY + window.scrollY;
      }

      elMovimiento.style.left =
        elComienzoX + xActual - cursorComienzoX + "px";
      elMovimiento.style.top = elComienzoY + yActual - cursorComienzoY + "px";
      evitaEventos(event);
    }

    function finMovimiento(event) {
      if (navegador == 0) {
        document.detachEvent("onmousemove", enMovimiento);
        document.detachEvent("onmouseup", finMovimiento);
      }
      if (navegador == 1) {
        document.removeEventListener("mousemove", enMovimiento, true);
        document.removeEventListener("mouseup", finMovimiento, true);
      }
    }

    var imagenesSeleccionadas = [];

    function seleccionarImagen(imagen) {
      // Verificar si la imagen ya está seleccionada
      var index = imagenesSeleccionadas.indexOf(imagen);

      if (index === -1) {
        // Si no está seleccionada, agregarla al arreglo
        if (imagenesSeleccionadas.length < 2) {
          imagenesSeleccionadas.push(imagen);
          imagen.style.border = "2px solid red"; // Cambiar estilo de borde
        }
      } else {
        // Si está seleccionada, quitarla del arreglo
        imagenesSeleccionadas.splice(index, 1);
        imagen.style.border = "none"; // Restaurar estilo de borde
      }
    }

    function getNombreImg() {
      var min = 1;
      var max = 13;
      var characters = 'CDPT';

      var randomNumber = Math.floor(Math.random() * (max - min + 1)) + min;
      var randomChar = characters.charAt(Math.floor(Math.random() * characters.length));

      var image = randomNumber + randomChar;

      return image;
    }

    function repetidos(NombreImg, miArreglo) {
      if (miArreglo.includes(NombreImg)) {
        return true;
      } else {
        return false;
      }
    }

    var nombresBorrados = <?php echo $miArregloJSON; ?>;

    if (nombresBorrados.length > 53){
      nombresBorrados = [];
    }

    function arrayOriginal() {
      var imagenes = document.querySelectorAll('img');
      var nombreImg = <?php echo $miArregloJSON; ?>;
      return nombreImg;
    }

    function NombresNuevos() {
      var NombreImg = getNombreImg();
      var originalArray = arrayOriginal();

      while (repetidos(NombreImg, originalArray)) {
        NombreImg = getNombreImg();
      }
      nombresBorrados.push(NombreImg);

      return NombreImg;
    }


    function borrarSeleccion() {

      // var NombreImg = getNombreImg();
      // var repetidos = repetidos(NombreImg, arrayOriginal());


      for (var i = 0; i < imagenesSeleccionadas.length; i++) {
        var imagen = imagenesSeleccionadas[i].id;
        var NuevosNom = NombresNuevos();
        // imagen.parentNode.removeChild(imagen);
        var imagen = document.getElementById(imagen);
        imagen.src = "./baraja/" + NombresNuevos() + ".jpg";
        imagen.style.border = null;

        // if (repetidos == 0) {
        //   repetidos = 1;
        // }
      }

      // Limpiar el arreglo de imágenes seleccionadas
      imagenesSeleccionadas = [];
    }
  </script>
</head>

<style>
  /* .imagen{
  display: inline-block;
  width: 100px;
  height: 100px;
  background: red;
  color: white;
} */
</style>

<body onLoad="carga();">


  <?php $i = 1;
  $positionLeft = 140;
  while ($i < 6) : ?>
    <div id="div<?php echo $i; ?>" style="
        top: 200px;
        left: <?php echo $positionLeft; ?>px;
        width: 112px;
        height: 158px;
        position: absolute;
        background-color: black;
      " onmousedown="comienzoMovimiento(event, this.id);" onmouseover="this.style.cursor='move'">
      <center>
        <img id="img<?php echo $i; ?>" class="imagen" src="baraja/<?php
                                                                  echo $_SESSION['imagenes'][$i]; ?>.jpg" onclick="seleccionarImagen(this)" />
      </center>
    </div>
  <?php $positionLeft = $positionLeft + 140;
    $i++;
  endwhile; ?>


  </div>
  <input name="enviar" id="enviar" type="button" value="Enviar" onclick="alert('hay!')" />

  <button onclick="borrarSeleccion()">Borrar selección</button>

</body>

</html>