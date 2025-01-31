<?php

/*
 *  Funciones para limpiar la entrada de posibles inyecciones
 */

function limpiarEntrada(string $entrada): string
{
    $salida = trim($entrada); // Elimina espacios antes y después de los datos
    $salida = strip_tags($salida); // Elimina marcas
    return $salida;
}
// Función para limpiar todos elementos de un array
function limpiarArrayEntrada(array &$entrada)
{

    foreach ($entrada as $key => $value) {
        $entrada[$key] = limpiarEntrada($value);
    }
}


// (5 y 10) Funciones para obtener el datos del país a partir de una dirección IP
function obtenerDatosPaisPorIP($ip)
{
    $url = "http://ip-api.com/json/$ip"; // Endpoint 
    $json = file_get_contents($url);
    $datosPais = json_decode($json, true); // Decodifica la respuesta JSON a un array asociativo

    return $datosPais;
}

function procesarClienteConDatosPais($datosPais)
{
    $codigoPaisMinusculas = $datosPais['countryCode'] ?? 'default'; // Bandera predeterminada
    $codigoPaisMinusculas = strtolower($codigoPaisMinusculas);

    $nombrePais = $datosPais['country'] ?? 'pais desconocido';

    $latitudPais = $datosPais['lat'] ?? 0; // Coordenadas predeterminadas
    $longitudPais = $datosPais['lon'] ?? 0;

    $zoomMapa = ($latitudPais == 0 && $longitudPais == 0) ? 2 : 13;
    $mensajeMapa = ($latitudPais == 0 && $longitudPais == 0)
        ? "No se ha podido obtener la ubicación del cliente."
        : "Ubicación del cliente.";

    return [
        'codigoPais' => $codigoPaisMinusculas,
        'nombrePais' => $nombrePais,
        'latitud' => $latitudPais,
        'longitud' => $longitudPais,
        'zoomMapa' => $zoomMapa,
        'mensajeMapa' => $mensajeMapa,
    ];
}

// (2) Funciones para chequear correos electrónicos, IPs y teléfonos
function comprobarEmail($email, $id)
{

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { //Compruebo que el formato del email es correcto
        return false;
    }
    $db = AccesoDatos::getModelo();
    return $db->existeClienteEmail($email, $id); //Compruebo que no existe otro cliente con el mismo email

}

function comprobarIP($ip)
{
    return filter_var($ip, FILTER_VALIDATE_IP);
}

function comprobarTelefono($telefono)
{
    return (preg_match("/^\d{3}-\d{3}-\d{4}$/", $telefono));
}

function validarTodosLosCampos($cli)
{
    return comprobarEmail($cli->email, id: $cli->id)
        && comprobarIP($cli->ip_address)
        && comprobarTelefono($cli->telefono);
}

// (3) Función para recuperar imagen de cliente
function recuperarImagenCliente($id)
{
    $idFormateado = str_pad($id, 8, '0', STR_PAD_LEFT);
    $rutaImagenJPG = "app/uploads/$idFormateado.jpg";
    $rutaImagenPNG = "app/uploads/$idFormateado.png";

    if (file_exists($rutaImagenJPG)) {
        $imagenBase64 = 'data:image/jpg;base64,' . base64_encode(file_get_contents($rutaImagenJPG));
    } else if (file_exists($rutaImagenPNG)) {
        $imagenBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($rutaImagenPNG));
    } else {
        $imagenBase64 = "https://robohash.org/$id";
    }

    return $imagenBase64;
}

// (4) Funcion para guardar imagen de cliente
function guardarImagen($id)
{
    //Restricciones
    $formatosPermitidos = ['image/jpeg', 'image/png'];
    $tamanioMaximo = 500 * 1024; // 500 KB
    $directorioDestino = 'app/uploads/';

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {

        $archivo = $_FILES['imagen'];
        $nombreArchivo = str_pad($id, 8, '0', STR_PAD_LEFT);;
        $tipoArchivo = $archivo['type'];
        $tamañoArchivo = $archivo['size'];
        $rutaTemporal = $archivo['tmp_name'];

        if (!in_array($tipoArchivo, $formatosPermitidos)) return false;

        if ($tamañoArchivo > $tamanioMaximo) return false;

        // Determinar la extensión del archivo
        $extension = $tipoArchivo === 'image/jpeg' ? '.jpg' : '.png';
        $nombreArchivo = str_pad($id, 8, '0', STR_PAD_LEFT) . $extension;

        // Ruta de destino
        $rutaDestino = $directorioDestino . $nombreArchivo;

        if (move_uploaded_file($rutaTemporal, $rutaDestino)) {
            return true;
        } else {
            return false;
        }
    }
}