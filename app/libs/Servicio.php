<?php

/**
 * En esta clase se concentran todoas las funciones que se encargan 
 * de la mayor parte del negocio aligerando el Controller.
 */
class Servicio
{

    /**
     * Devuelve el id del usuario si existe y la clave es correcta.
     * Los datos usuario y clave vienen de la ventana de login.
     */
    public static function obtenerIdUsuarioAutenticado(): int
    {
        $usuario = Utils::recoger('usuario');
        $clave = Utils::recoger('clave');
        
        if (!empty($usuario) && !empty($clave)) {
            $dto =  Modelo::getInstance();
            $idUsuario = $dto->usuarioClaveCorrectos($usuario, $clave);
        } else 
            $idUsuario = 0;
        return $idUsuario;
    }

    /**
     * Recoge los datos saneados en un array (DATA SANITIZATION).
     * Estos datos vienen de la ventana de registro.
     * La foto se recoge y sube al servidor despues de la validación.
     */
    public static function obtenerUsuario(): array
    {
        $usuario = array(
            'usuario' => Utils::recoger('usuario'),
            'clave' => Utils::recoger('clave'),
            'nombre' => Utils::recoger('nombre'),
            'apellido' => Utils::recoger('apellido'),
            'email' => Utils::recoger('email')
        );
        return $usuario;
    }

    /**
     * Comprueba que todos los datos sean validos (DATA VALIDATION).
     * Devuelve un array con los errores encontrados.
     */
    public static function validarAlumno(array $usuario): array
    {
        $errores = array();
        if (empty($usuario['usuario']))
            array_push($errores,'Usuario vacío.');
        if (empty($usuario['clave']))
            array_push($errores,'Clave vacía.');
        if (empty($usuario['nombre']))
            array_push($errores,'Nombre vacío.');
        if (empty($usuario['apellido']))
            array_push($errores,'Apellidos vacíos.');
        if (empty($usuario['email']) || ! Utils::validarEmail($usuario['email']))
            array_push($errores,'Email erróneo.');
        
        return $errores;
    }

    /**
     * Si se ha adjuntado foto se sube al servidor y se le setea la ruta al usuario
     */
    public static function subirFoto(array &$usuario, array &$errores): void
    {
        $foto = Utils::recogerFoto('foto');
        if($foto['error'] != 4) {
            if ($foto['error'] != 0 || !self::validaFichero($foto)) {
                array_push($errores,'Ha ocurrido un error con la imagen que se intentó subir');
            } else {
                // Para el nombre del fichero lo llamaré igual que el usuario ya que es dato unico
                $nombreFichero = $usuario['usuario'];
                $extension = explode('/', $foto['type'])[1];
                $ruta = $nombreFichero . '.' . $extension;
                // Movemos el fichero a la ubicación definitiva
                if (move_uploaded_file($foto['tmp_name'], Config::$carpetaFotosPerfil.'/'.$ruta))
                    $usuario['foto'] = $ruta;
            }
        }
    }

    /**
     * Devuelve true si el fichero pasado es valido y false si no lo es.
     * Es valido cuando se compruebe que se ha subido.
     */
    private function validaFichero(array $fotoPerfil): bool
    {
        $valido = true;

        if (!is_uploaded_file($fotoPerfil['tmp_name']))
            $valido = false;

        return $valido;
    }

    /**
     * Esta funcion se llama desde la vista para mostrar un cuadro con los errores pasados.
     * (Igual al llamarse desde la vista debería hacerse en otro sitio o de otra manera)
     */
    public static function mostrarMensaje($titulo, $mensajes): void
    {
        echo "<div class='cuadroBlanco estrecho centrado'><h3>$titulo:</h3>";
        foreach ($mensajes as $msg) {
            echo '<p>' . $msg . '</p>';
        }
        echo "</div>";
    }
    
   /**
    * Obtiene el mensaje de la vista redaccion.php
    * Si es respuesta de otro seteo esa propiedad y el destinatario sino 
    * seteo el id del usuario correspondiente. 
    */
    public static function obtenerMensaje(): array
    {
        $mensaje = array();
        $mensaje['idUsuario'] = Utils::recogerDeSesion('idUsuario');
        $mensaje['asunto'] = Utils::recoger('asunto');
        $mensaje['texto'] = Utils::recoger('texto');
        
        $respuestade = Utils::recogerDeSesion('respuestade');
        if(!empty($respuestade)){
            $mensaje['respuestade'] = $respuestade;
            $mensaje['destinatario'] = Utils::recogerDeSesion('destinatarioId');
        } else {
            $destinatario = Utils::recoger('destinatario');
            $pdo =  Modelo::getInstance();
            $destinatarioId = $pdo->obtenerIdPorUsuario($destinatario);
            $mensaje['destinatario'] = $destinatarioId;
        }
        
        return $mensaje;
    }
    
    /**
     * Valida el mensaje y devuelve los errores si tuviera.
     */
    public static function validarMensaje(&$mensaje) : array 
    {
        $errores = array();
        // Se valida el asunto.
        if(empty($mensaje['asunto']))
            array_push($errores,'Debe introducir un asunto');
        // Se valida el texto del mensaje.
        if(empty($mensaje['texto']))
            array_push($errores,'Debe introducir un contenido');
        // Se valida que el ususario se haya 
        // correspondido con alguien de la base de datos
        if(!$mensaje['destinatario'])
            array_push($errores,'Usuario no encontrado, escriba bien el nombre de usuario.');
            
        return $errores;
    }
    
}
?>
