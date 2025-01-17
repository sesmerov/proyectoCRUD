<?php


function crudBorrar ($id){    
    $db = AccesoDatos::getModelo();
    $resu = $db->borrarCliente($id);
    if ( $resu){
         $_SESSION['msg'] = " El usuario ".$id. " ha sido eliminado.";
    } else {
         $_SESSION['msg'] = " Error al eliminar el usuario ".$id.".";
    }

}

function crudTerminar(){
    AccesoDatos::closeModelo();
    session_destroy();
}
 
function crudAlta(){
    $cli = new Cliente();
    $orden= "Nuevo";
    include_once "app/views/formulario.php";
}

function crudDetalles($id){
    $db = AccesoDatos::getModelo();
    $cli = $db->getCliente($id);
    include_once "app/views/detalles.php";
}

function crudDetallesSiguiente($id){
    $db = AccesoDatos::getModelo();
    $cli = $db->getClienteSiguiente($id);
    if(!$cli){
        $msg = "No hay mas clientes disponibles";
        $cli = $db->getUltimoCliente();
    }
    include_once "app/views/detalles.php";
}

function crudDetallesAnterior($id){
    $db = AccesoDatos::getModelo();
    $cli = $db->getClienteAnterior($id);
    if(!$cli){
        $msg = "No hay mas clientes disponibles";
        $cli = $db->getPrimerCliente();
    }
    include_once "app/views/detalles.php";
}


function crudModificar($id){
    $db = AccesoDatos::getModelo();
    $cli = $db->getCliente($id);
    $orden="Modificar";
    include_once "app/views/formulario.php";
}

function crudModificarSiguiente($id){
    $db = AccesoDatos::getModelo();
    $cli = $db->getClienteSiguiente($id);
    $orden="Modificar";
    if(!$cli){
        $msg = "No hay mas clientes disponibles";
        $cli = $db->getUltimoCliente();
    }
    include_once "app/views/formulario.php";
}

function crudModificarAnterior($id){
    $db = AccesoDatos::getModelo();
    $cli = $db->getClienteAnterior($id);
    $orden="Modificar";
    if(!$cli){
        $msg = "No hay mas clientes disponibles";
        $cli = $db->getUltimoCliente();
    }
    include_once "app/views/formulario.php";
}

function crudPostAlta(){
    limpiarArrayEntrada($_POST); //Evito la posible inyección de código
    
    $cli = new Cliente();
    $cli->id            =$_POST['id'];
    $cli->first_name    =$_POST['first_name'];
    $cli->last_name     =$_POST['last_name'];
    $cli->email         =$_POST['email'];	
    $cli->gender        =$_POST['gender'];
    $cli->ip_address    =$_POST['ip_address'];
    $cli->telefono      =$_POST['telefono'];

    if(!validarTodosLosCampos($cli)){
        $msg = " Revise email, ip y teléfono ";
        $orden="Nuevo";
        include_once "app/views/formulario.php";
    }else{
        $db = AccesoDatos::getModelo();
    if ( $db->addCliente($cli) ) {
           $_SESSION['msg'] = " El usuario ".$cli->first_name." se ha dado de alta ";
        } else {
            $_SESSION['msg'] = " Error al dar de alta al usuario ".$cli->first_name."."; 
        }
    }
}

function crudPostModificar(){
    limpiarArrayEntrada($_POST); //Evito la posible inyección de código
    $cli = new Cliente();

    $cli->id            =$_POST['id'];
    $cli->first_name    =$_POST['first_name'];
    $cli->last_name     =$_POST['last_name'];
    $cli->email         =$_POST['email'];	
    $cli->gender        =$_POST['gender'];
    $cli->ip_address    =$_POST['ip_address'];
    $cli->telefono      =$_POST['telefono'];

    if(!validarTodosLosCampos($cli)){
        $msg = " Revise email, ip y teléfono ";
        $orden="Modificar";
        include_once "app/views/formulario.php";
    }else{
        $db = AccesoDatos::getModelo();
        if ( $db->modCliente($cli) ){
            $_SESSION['msg'] = " El usuario ha sido modificado";
        } else {
            $_SESSION['msg'] = " Error al modificar el usuario ";
        }
    }
}

// (5) Función para obtener el código de país a partir de una dirección IP
function obtenerDatosPaisPorIP($ip)
{
    $url = "http://ip-api.com/json/$ip"; // Endpoint 
    $json = file_get_contents($url); 
    $decodedJSON = json_decode($json, true); // Decodifica la respuesta JSON a un array asociativo

    return $decodedJSON;
}


// (2) Funciones para chequear correos electrónicos, IPs y teléfonos
function comprobarEmail($email,$id){

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { //Compruebo que el formato del email es correcto
        return false;
    }
    $db = AccesoDatos::getModelo();
    return $db->existeClienteEmail($email,$id); //Compruebo que no existe otro cliente con el mismo email

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
    return comprobarEmail($cli->email,id: $cli->id) && comprobarIP($cli->ip_address) && comprobarTelefono($cli->telefono);
}