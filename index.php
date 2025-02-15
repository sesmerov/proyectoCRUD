<?php
session_start();
define ('FPAG',10); // Número de filas por página


require_once 'app/helpers/util.php';
require_once 'app/config/configDB.php';
require_once 'app/models/Cliente.php';
require_once 'app/models/AccesoDatosPDO.php';
require_once 'app/controllers/crudclientes.php';

//LOGIN

if (!isset($_SESSION['intentos'])) {
    $_SESSION['intentos'] = 0;
}

if ($_SESSION['intentos']>= 3) {
    $msg = "Intentos de inicio de sesion superados. Reinicia el navegador";
    require_once "app/views/login.php"; 
    exit();
}

if (!isset($_SESSION['rol'])) {
    // Si se envía el formulario de login
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
        $login = $_POST['login'];
        $password = $_POST['password'];

        
        $rol = crudUsuario($login, $password);
        if ($rol !== false) { 
            $_SESSION['rol'] = $rol;
            $_SESSION['intentos'] = 0; 
        } else {
            $_SESSION['intentos']++;
            $msg = "Usuario o contraseña incorrectos. Intento " . $_SESSION['intentos'] . " de 3.";
            require_once "app/views/login.php";
            exit();
        }
    } else {
        require_once "app/views/login.php";
        exit();
    }
}


// ORDEN POR CAMPO

if (!isset($_SESSION['orden'])) {
    $_SESSION['orden'] = 'id';
}

// Cambiar el campo de orden si se solicita
if (isset($_GET['ordenar'])) {
    $camposPermitidos = ['id', 'first_name', 'email', 'gender', 'ip_address', 'telefono'];
    if (in_array($_GET['ordenar'], $camposPermitidos)) {
        $_SESSION['orden'] = $_GET['ordenar'];
    }else{
        $_SESSION['orden'] = 'id';
    }
}

$orden = $_SESSION['orden'];

// Manejar la posición de inicio para la paginación
if (!isset($_SESSION['posini'])) {
    $_SESSION['posini'] = 0;
}

$posini = $_SESSION['posini'];

//---- PAGINACIÓN ----
$midb = AccesoDatos::getModelo();
$totalfilas = $midb->numClientes();
if ( $totalfilas % FPAG == 0){
    $posfin = $totalfilas - FPAG;
} else {
    $posfin = $totalfilas - $totalfilas % FPAG;
}

if ( !isset($_SESSION['posini']) ){
  $_SESSION['posini'] = 0;
}
$posAux = $_SESSION['posini'];
//------------

// Borro cualquier mensaje "
$_SESSION['msg']=" ";

ob_start(); // La salida se guarda en el bufer
if ($_SERVER['REQUEST_METHOD'] == "GET" ){
    
    // Proceso las ordenes de navegación
    if ( isset($_GET['nav'])) {
        switch ( $_GET['nav']) {
            case "Primero"  : $posAux = 0; break;
            case "Siguiente": $posAux +=FPAG; if ($posAux > $posfin) $posAux=$posfin; break;
            case "Anterior" : $posAux -=FPAG; if ($posAux < 0) $posAux =0; break;
            case "Ultimo"   : $posAux = $posfin;
        }
        $_SESSION['posini'] = $posAux;
    }

    //Muestra siguiente usuario en Detalles
    if ( isset($_GET['nav-detalles'])) {
        switch ( $_GET['nav-detalles']) {
            case "Siguiente": 
                crudDetallesSiguiente($_GET["id"]);
                break;
            case "Anterior" : 
                crudDetallesAnterior($_GET["id"]);
                break;
        }
        $_SESSION['posini'] = $posAux;
    }

    //Muestra siguiente usuaio en Modificar
    if ( isset($_GET['nav-modificar'])) {
        switch ( $_GET['nav-modificar']) {
            case "Siguiente": 
                crudModificarSiguiente($_GET["id"]);
                break;
            case "Anterior" : 
                crudModificarAnterior($_GET["id"]);
                break;
        }
        $_SESSION['posini'] = $posAux;
    }

    // Proceso de ordenes de CRUD clientes
    if (isset($_GET['orden'])) {
        switch ($_GET['orden']) {
            case "Nuevo":
                if ($_SESSION['rol'] == 1) {
                    crudAlta();
                } else {
                    $_SESSION['msg-error'] = "No tienes permiso para realizar esta acción.";
                    header("Location: index.php");
                    exit();
                }
                break;
            case "Borrar":
                if ($_SESSION['rol'] == 1) {
                    crudBorrar($_GET['id']);
                } else {
                    $_SESSION['msg-error'] = "No tienes permiso para realizar esta acción.";
                    header("Location: index.php");
                    exit();
                }
                break;
            case "Modificar":
                if ($_SESSION['rol'] == 1) {
                    crudModificar($_GET['id']);
                } else {
                    $_SESSION['msg-error'] = "No tienes permiso para realizar esta acción.";
                    header("Location: index.php");
                    exit();
                }
                break;
            case "Detalles":
                crudDetalles($_GET['id']);
                break;
            case "Terminar":
                crudTerminar();
                break;
        }
    }
    if(isset($_GET)){
        if(isset($_GET['generarPDF'])){
           generarPDF($_GET['id']);
        }
    }
} 
// POST Formulario de alta o de modificación
else {
    if (  isset($_POST['orden'])){
         switch($_POST['orden']) {
             case "Nuevo"    : crudPostAlta(); break;
             case "Modificar": crudPostModificar(); break;
             case "Detalles":; // No hago nada
         }
    }

}


// Si no hay nada en la buffer 
// Cargo genero la vista con la lista por defecto
if ( ob_get_length() == 0){
    $db = AccesoDatos::getModelo();
    $posini = $_SESSION['posini'];
    $tclientes = $db->getClientes($posini, FPAG, $orden);
    require_once "app/views/list.php";    
}
$contenido = ob_get_clean();
$msg = $_SESSION['msg'];
// Muestro la página principal con el contenido generado
require_once "app/views/principal.php";



