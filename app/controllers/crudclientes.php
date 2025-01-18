<?php
function crudBorrar($id)
{
    $db = AccesoDatos::getModelo();
    $resu = $db->borrarCliente($id);
    if ($resu) {
        $_SESSION['msg'] = " El usuario " . $id . " ha sido eliminado.";
    } else {
        $_SESSION['msg'] = " Error al eliminar el usuario " . $id . ".";
    }
}

function crudTerminar()
{
    AccesoDatos::closeModelo();
    session_destroy();
}

function crudAlta()
{
    $cli = new Cliente();
    $orden = "Nuevo";
    include_once "app/views/formulario.php";
}

function crudDetalles($id)
{

    $db = AccesoDatos::getModelo();
    $cli = $db->getCliente($id);

    $datosPais = obtenerDatosPaisPorIP($cli->ip_address);
    $datosPaisProcesados = procesarClienteConDatosPais($datosPais);
    $codigoPaisMinusculas = $datosPaisProcesados['codigoPais'];
    $nombrePais = $datosPaisProcesados['nombrePais'];
    $latitudPais = $datosPaisProcesados['latitud'];
    $longitudPais = $datosPaisProcesados['longitud'];
    $zoomMapa = $datosPaisProcesados['zoomMapa'];
    $mensajeMapa = $datosPaisProcesados['mensajeMapa'];

    $imagenCliente = recuperarImagenCliente($cli->id);

    include_once "app/views/detalles.php";
}

function crudDetallesSiguiente($id)
{
    $db = AccesoDatos::getModelo();
    $cli = $db->getClienteSiguiente($id);
    if (!$cli) {
        $msg = "No hay mas clientes disponibles";
        $cli = $db->getUltimoCliente();
    }

    $datosPais = obtenerDatosPaisPorIP($cli->ip_address);
    $datosPaisProcesados = procesarClienteConDatosPais($datosPais);
    $codigoPaisMinusculas = $datosPaisProcesados['codigoPais'];
    $nombrePais = $datosPaisProcesados['nombrePais'];
    $latitudPais = $datosPaisProcesados['latitud'];
    $longitudPais = $datosPaisProcesados['longitud'];
    $zoomMapa = $datosPaisProcesados['zoomMapa'];
    $mensajeMapa = $datosPaisProcesados['mensajeMapa'];

    $imagenCliente = recuperarImagenCliente($cli->id);

    include_once "app/views/detalles.php";
}

function crudDetallesAnterior($id)
{
    $db = AccesoDatos::getModelo();
    $cli = $db->getClienteAnterior($id);
    if (!$cli) {
        $msg = "No hay mas clientes disponibles";
        $cli = $db->getPrimerCliente();
    }

    $datosPais = obtenerDatosPaisPorIP($cli->ip_address);
    $datosPaisProcesados = procesarClienteConDatosPais($datosPais);
    $codigoPaisMinusculas = $datosPaisProcesados['codigoPais'];
    $nombrePais = $datosPaisProcesados['nombrePais'];
    $latitudPais = $datosPaisProcesados['latitud'];
    $longitudPais = $datosPaisProcesados['longitud'];
    $zoomMapa = $datosPaisProcesados['zoomMapa'];
    $mensajeMapa = $datosPaisProcesados['mensajeMapa'];

    $imagenCliente = recuperarImagenCliente($cli->id);

    include_once "app/views/detalles.php";
}

function crudModificar($id)
{
    $db = AccesoDatos::getModelo();
    $cli = $db->getCliente($id);
    $orden = "Modificar";
    $imagenCliente = recuperarImagenCliente($cli->id);

    include_once "app/views/formulario.php";
}

function crudModificarSiguiente($id)
{
    $db = AccesoDatos::getModelo();
    $cli = $db->getClienteSiguiente($id);
    $orden = "Modificar";
    if (!$cli) {
        $msg = "No hay mas clientes disponibles";
        $cli = $db->getUltimoCliente();
    }
    $imagenCliente = recuperarImagenCliente($cli->id);

    include_once "app/views/formulario.php";
}

function crudModificarAnterior($id)
{
    $db = AccesoDatos::getModelo();
    $cli = $db->getClienteAnterior($id);
    $orden = "Modificar";
    if (!$cli) {
        $msg = "No hay mas clientes disponibles";
        $cli = $db->getPrimerCliente();
    }
    $imagenCliente = recuperarImagenCliente($cli->id);

    include_once "app/views/formulario.php";
}

function crudPostAlta()
{
    limpiarArrayEntrada($_POST); //Evito la posible inyección de código

    $cli = new Cliente();
    $cli->id            = $_POST['id'];
    $cli->first_name    = $_POST['first_name'];
    $cli->last_name     = $_POST['last_name'];
    $cli->email         = $_POST['email'];
    $cli->gender        = $_POST['gender'];
    $cli->ip_address    = $_POST['ip_address'];
    $cli->telefono      = $_POST['telefono'];

    if (!validarTodosLosCampos($cli)) {
        $msg = " Revise email, ip y teléfono ";
        $orden = "Nuevo";
        include_once "app/views/formulario.php";
    } else {
        $db = AccesoDatos::getModelo();
        if ($db->addCliente($cli)) {
            $_SESSION['msg'] = " El usuario " . $cli->first_name . " se ha dado de alta ";
        } else {
            $_SESSION['msg'] = " Error al dar de alta al usuario " . $cli->first_name . ".";
        }
    }
}

function crudPostModificar()
{
    limpiarArrayEntrada($_POST); //Evito la posible inyección de código
    $cli = new Cliente();

    $cli->id            = $_POST['id'];
    $cli->first_name    = $_POST['first_name'];
    $cli->last_name     = $_POST['last_name'];
    $cli->email         = $_POST['email'];
    $cli->gender        = $_POST['gender'];
    $cli->ip_address    = $_POST['ip_address'];
    $cli->telefono      = $_POST['telefono'];

    if (!validarTodosLosCampos($cli)) {
        $msg = " Revise email, ip y teléfono ";
        $orden = "Modificar";
        include_once "app/views/formulario.php";
    } else {
        $db = AccesoDatos::getModelo();
        if ($db->modCliente($cli)) {
            $_SESSION['msg'] = " El usuario ha sido modificado";
        } else {
            $_SESSION['msg'] = " Error al modificar el usuario ";
        }
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
    if (filter_var($ip, FILTER_VALIDATE_IP)) {
        return true;
    } else {
        return false;
    }
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
    $rutaImagen = "app/uploads/$idFormateado.jpg"; 

if (file_exists($rutaImagen)) {

    $contenidoImagen = file_get_contents($rutaImagen);

    // Codificar la imagen en Base64
    $imagenBase64 = 'data:image/jpg;base64,' . base64_encode($contenidoImagen);
} else {
    $imagenBase64 = null;
}
return $imagenBase64;

}