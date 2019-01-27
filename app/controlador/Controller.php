<?php

  // ------------------------------------ \\
 // --------CONTROLADOR (acciones)-------- \\
// ---------------------------------------- \\

class Controller
{ 
    /**
     * Carga la ventana de inicio de sesión
     */
    public function abrirLogin()
    {
        if(!empty(Utils::recogerDeSesion('idUsuario'))){
            $idUsuario = Utils::recogerDeSesion('idUsuario');
            self::iniciarSesion(array(), $idUsuario);
        } else
            require __DIR__ . '/../vistas/login.php';
    }

    /**
     * Carga la ventana de registro
     */
    public function abrirRegistro($errores = array())
    {
        require __DIR__ . '/../vistas/registro.php';
        //Marco el usuario que se va a registrar como no registrado.
        Utils::setearEnSesion('usuarioRegistrado', false);
    }
    
    /**
     * Recoge, valida y registra a un usuario.
     * Y se le redirige a su pantalla personal.php
     * Recoger la variable usuarioRegistrado lo hago para que no se ejecute de nuevo
     * si se recarga con F5.
     */
    public function registrarUsuario()
    {
        $usuarioRegistrado = Utils::recogerDeSesion('usuarioRegistrado');
        if(!$usuarioRegistrado){
            Utils::setearEnSesion('usuarioRegistrado', true);
            $usuario = Servicio::obtenerUsuario();
            $errores = Servicio::validarAlumno($usuario);
            if (empty($errores)) {
                // La foto solo la subo si el resto de cosas han ido bien.
                Servicio::subirFoto($usuario, $errores);
                if (empty($errores)) {
                    $pdo =  Modelo::getInstance();
                    if ($pdo->registrarUsuario($usuario, $errores)){
                        $notif = array('Te has registrado correctamente!');
                        self::iniciarSesion($notif);
                    }
                }
            }
            if (!empty($errores))
                self::abrirRegistro($errores);
        } else {
            self::iniciarSesion();
        }
    }

    /**
     * Carga la ventana personal del usuario que ha iniciado sesión
     * Si viene desde login se comprueba que exista el usuario:
     *  - Si existe se carga personal.php (cargando antes los mensajes)
     *  - Sino mostramos error.  
     */
    public function iniciarSesion(array $notif = array(), int $idUsuario = null)
    {
        if (!empty(Utils::recogerDeSesion('idUsuario')))
            $idUsuario = Utils::recogerDeSesion('idUsuario');
        else
            $idUsuario = Servicio::obtenerIdUsuarioAutenticado();
        
        if($idUsuario != 0) {
            // Si el id del usuario no está en sesión lo meto
            if (empty(Utils::recogerDeSesion('idUsuario')))
                Utils::setearEnSesion('idUsuario', $idUsuario);
            // Se buscan los mensajes recibidos y abrimos la ventana
            $pdo =  Modelo::getInstance();
            define('MENSAJES_RECIBIDOS', $pdo->obtenerMensajesPorDestinatario($idUsuario));
            require __DIR__ . '/../vistas/personal.php';
        } else {
            $errores = array();
            array_push($errores,'Usuario o contraseña incorrectos.');
            require __DIR__ . '/../vistas/login.php';
        }

    }
    
    /**
     * Si se va a redactar el mensaje como respuesta a otro, los datos destinatarioId 
     * y el mensaje al que se responde (respuestade) vendrán como parámetro y se meten
     * en la sesión para poder utilizarlos cuando se pulse el botón de Enviar Mensaje
     * sin pasarlos otra vez como parámetros.
     * Y si no vienen por parámetro se mostrarán los inputs correspondientes
     * y se cogerán de ahí.
     */
    public function redactarMensaje(array $errores = array()) : void
    {        
        Utils::setearEnSesion('destinatarioId', Utils::recoger('destinatarioId'));
        Utils::setearEnSesion('respuestade', Utils::recoger('respuestade'));
        
        require __DIR__ . '/../vistas/redaccion.php';
        //Marco el mensaje que se va a enviar como no enviado.
        Utils::setearEnSesion('mensajeEnviado', false);
    }

    /**
     * Recoge, valida y envia el mensaje.
     * La comprobación de la variable de sesión mensajeEnviado es para evitar que
     * al recargar la página con F5 reenvie el mensaje.
     */
    public function enviarMensaje() : void
    {
        $msgEnviado = Utils::recogerDeSesion('mensajeEnviado');
        if(!$msgEnviado){
            Utils::setearEnSesion('mensajeEnviado', true);
            $mensaje = Servicio::obtenerMensaje();
            $errores = Servicio::validarMensaje($mensaje);
            if (empty($errores)) {
                $pdo =  Modelo::getInstance();
                if ($pdo->enviarMensaje($mensaje, $errores)){
                    $notif = array('Mensaje enviado correctamente!');
                    self::iniciarSesion($notif);
                }
            }
            if (!empty($errores))
                self::redactarMensaje($errores);
        } else {
            self::iniciarSesion();
        }
    }
    
    /**
     * Destruye la sesión y redirige a la venta de login.
     */
    public function logout() : void {
        Utils::destruirSesion();
        self::abrirLogin();
    }
}
?>
