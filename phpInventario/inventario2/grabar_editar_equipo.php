<?php
session_start();
require_once 'funciones_bd.php';
require_once 'funciones.php';
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function validarDatosRegistro (){
    // Recuperar datos enviados desde "formulario_nuevo_equipo.php"
    $datos = Array();
    $datos[0] = (isset ($_REQUEST['titulo']))? $_REQUEST['titulo']:"";
    $datos[0] = limpiar($datos[0]);//realiza un limpiar
    $datos[1] = (isset ($_REQUEST['url']))? $_REQUEST['url']:"";
    //................Validar...............
    $errores = Array();//$errores es un array de flag, de true y false
    $errores[0] = !validarTitulo($datos[0]);
    $errores[1] = !validarURL($datos[1]); 
    //.....Asignar a variables de sesión.
    $_SESSION['datos'] = $datos; //datos introducidos
    $_SESSION['errores'] = $errores;//errores concretos que hay
    $_SESSION['hayErrores'] = ($errores[0]||$errores[1]);//Nos dice que errores hay  
}

    //PRINCIPAL
validarDatosRegistro();
if ($_SESSION['hayErrores']) {
    $url = "formulario_editar_software.php";
    header('Location:'.$url);
} else {

    $db = conectaBd();
    $titulo = $_SESSION['datos'][0];
    $url = $_SESSION['datos'][1];    
    $id = $_SESSION['id'];
    
    $consulta = "UPDATE software 
    set titulo = :titulo, 
    url= :url 
    WHERE id= :id";
    
    $resultado = $db->prepare($consulta);
    if ($resultado->execute(array(":titulo" => $titulo,
        ":url" => $url, 
        ":id" => $id))) {
            unset($_SESSION['datos']);
            unset($_SESSION['errores']);
            unset($_SESSION['hayErrores']);
           // $_SESSION['id'] = 0; //esta linea en realidad no seria necesaria
            $url = "listado_software.php";
            header('Location:'.$url);
    } else {
        //print "<p>Error al crear el registro.</p>\n";
        $url = "confirmar_eliminar_software.php";
        header('Location:'.$url);
    }

    $db = null;

}
?>