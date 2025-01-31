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
        $cli->id = $db->lastInsertId();
        guardarImagen($cli->id);
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
        if ($db->modCliente($cli) || guardarImagen($cli->id)) {
            $_SESSION['msg'] = " El usuario ha sido modificado";
        } else {
            $_SESSION['msg'] = " Error al modificar el usuario ";
        }
    }
}

// (7) Genera PDF
function generarPDF($id)
{
    $db = AccesoDatos::getModelo();
    $cli = $db->getCliente($id);
    $datosPais = obtenerDatosPaisPorIP($cli->ip_address);
    $datosPaisProcesados = procesarClienteConDatosPais($datosPais);
    $imagenCliente = recuperarImagenCliente($cli->id);
    require_once "app/views/pdf.php";
}
