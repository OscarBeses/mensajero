<?php

  // ----------------------------------- \\
 // --------CONTROLADOR FRONTAL---------- \\
// --------------------------------------- \\

// carga de la configuración, el modelo y el controlador
require_once __DIR__ . '/../app/config/Config.php';
require_once __DIR__ . '/../app/modelo/Modelo.php';
require_once __DIR__ . '/../app/controlador/Controller.php';
// carga las librerías
require_once __DIR__ . '/../app/libs/utilsIndex.php';
require_once __DIR__ . '/../app/libs/Utils.php';
require_once __DIR__ . '/../app/libs/Servicio.php';

// Inicio sesión
session_start();

// Acciones que controla mi web con el parámetro 'orden'
$accionesDisponibles = array(
    'abrirLogin' => array('clase' => 'Controller', 'metodo' => 'abrirLogin'),
    'abrirRegistro' => array('clase' => 'Controller', 'metodo' => 'abrirRegistro'),
    'registrarUsuario' => array('clase' => 'Controller', 'metodo' => 'registrarUsuario'),
    'iniciarSesion' => array('clase' => 'Controller', 'metodo' => 'iniciarSesion'),
    'redactarMensaje' => array('clase' => 'Controller', 'metodo' => 'redactarMensaje'),
    'enviarMensaje' => array('clase' => 'Controller', 'metodo' => 'enviarMensaje'),
    'logout' => array('clase' => 'Controller', 'metodo' => 'logout'),
);

$accion = UtilsIndex::obtenerAccionElegida($accionesDisponibles);
UtilsIndex::ejecutarAccion($accion);