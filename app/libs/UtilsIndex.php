<?php

/**
 * UTILIDADES DEL INDEX
 */
class UtilsIndex
{

    /**
     * La acción por defecto es 'abrirLogin'.
     * Si la posición 'orden' del array de accionesDisponibles existe:
     * - El valor correspondiente a esa clave se devuleve como la acción acción elegida.
     * Y sino:
     * - Se muestra la ventana de error.
     */
    function obtenerAccionElegida(array $accionesDisponibles): array
    {
        $cadenaRecogida = Utils::recoger('orden');
        if (empty($cadenaRecogida)) {
            $accionElegida = $accionesDisponibles['abrirLogin'];
        } else {
            if (isset($accionesDisponibles[$cadenaRecogida]))
                $accionElegida = $accionesDisponibles[$cadenaRecogida];
            else
                UtilsIndex::mostrarPantallaError("Error 404: No existe la ruta <i>$cadenaRecogida</i>");
        }

        return $accionElegida;
    }

    /**
     * Lleva a cabo la acción elegida si existe en el array de accionesDisponibles.
     * Si no existe, mostramos una pantalla de error 404.
     */
    function ejecutarAccion(array $accion): void
    {
        $clase = $accion['clase'];
        $metodo = $accion['metodo'];

        if (method_exists($clase, $metodo))
            // Se llama a la función Clase->metodo() que se le pase
            call_user_func(array(new $clase(), $metodo));
        else
            UtilsIndex::mostrarPantallaError("Error 404: La función <i>$clase -> $metodo</i> no existe");
    }

    /**
     * Muestra una pantalla de error con el mensaje pasado
     */
    function mostrarPantallaError(string $mensaje): void
    {
        header('Error');
        echo "<html><body><h1>$mensaje</h1></body></html>";
        exit();
    }
}
?>