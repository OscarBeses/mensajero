<?php

/**
 * UTILIDADES GENERALES
 */
class Utils
{

    /**
     * Recoge el dato de la sesión si existe.
     */
    public static function recogerDeSesion($clave)
    {
        if(isset($_SESSION[$clave]))
            $valor = intval($_SESSION[$clave]);
        else
            $valor = null;
        return $valor;
    }
    
    /**
     * Setea el dato en la sesión.
     */
    public static function setearEnSesion(string $clave, string $valor) : void
    {
        $_SESSION[$clave] = $valor;
    }
    
    /**
     * Destruye la sesión en el instante. 
     */
    public static function destruirSesion() : void {
        session_destroy();
        unset($_SESSION);
    }
    
    /**
     * Valida el email.
     * Con esta función me ahorro la expresión regular.
     * Devuelve true si se ha validado y false si no.
     */
    public static function validarEmail($email)
    {
        return (false !== filter_var($email, FILTER_VALIDATE_EMAIL));
    }

    /**
     * Devuelve el array de datos con la información de la foto.
     */
    public static function recogerFoto(string $fichero): array
    {
        $foto = null;
        if (isset($_FILES[$fichero]) && is_array($_FILES[$fichero]))
            $foto = $_FILES[$fichero];
        return $foto;
    }

    /**
     * Función para recoger cualquier dato pasado por GET o POST
     */
    public static function recoger(string $var): string
    {
        if (isset($_REQUEST[$var]))
            $tmp = strip_tags(self::sinEspacios($_REQUEST[$var]));
        else
            $tmp = "";

        return $tmp;
    }

    /**
     * Quita los espacios sobrantes a una cadena
     */
    private function sinEspacios($frase)
    {
        $texto = trim(preg_replace('/ +/', ' ', $frase));
        return $texto;
    }
}
?>