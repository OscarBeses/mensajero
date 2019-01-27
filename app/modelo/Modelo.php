<?php

/**
 * El Modelo se encarga de todo lo que tiene que ver con la persistencia de datos.
 */
class Modelo extends PDO
{
    private static $instancia;
    private $conexion;

    public function __construct()
    {
        try {
            $this->conexion = new PDO('mysql:host='.Config::$mvc_bd_hostname.';dbname='.Config::$mvc_bd_nombre,
                                        Config::$mvc_bd_usuario,
                                        Config::$mvc_bd_clave);
            $this->conexion->exec("set names utf8");
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo '<p>Error: No puede conectarse con la base de datos.</p>';
            echo '<p>Error: '.$e->getMessage().'<p>';
        }
    }
    
    /**
     * Para crear el objeto usando SINGLETON se utiliza este método
     * que comprueba si existe una conexión a la BD para aprovecharla, sino
     * existe se llama al constructor para que cree la conexión
     */
    public static function getInstance()
    {
        if (!isset(self::$instancia))
            self::$instancia = new self();
        
        return self::$instancia;
    }
    
    /**
     * Con el usuario y la clave devuelve el id del usuario o 0 si no encuentra.
     */
    public function usuarioClaveCorrectos($usuario, $clave) : int {
        try {
            $consulta = 'select id from usuarios ';
            $consulta .= 'where usuario=:usuario and clave=:clave';
            $stmt = $this->conexion->prepare($consulta);
            $stmt->bindParam('usuario', $usuario);
            $stmt->bindParam('clave', $clave);
            $stmt->execute();

            $arrayResult = $stmt->fetch();
            $idUsuario = $arrayResult != false ? $arrayResult['id'] : 0;
        } catch (PDOException $e) {
            echo "<p>Error: " . $e->getMessage() . '</p>';
        }
        return $idUsuario;
    }

    /**
     * Devuelve un array con todos los mensajes enviados al destinatario pasado.
     */
    public function obtenerMensajesPorDestinatario(int $idDestinatario) : array
    {
        try {
            $consulta = "select * from mensajes where destinatario=:idDest order by fecha desc";
            $stmt = $this->conexion->prepare($consulta);
            $stmt->bindParam('idDest', $idDestinatario);
            $stmt->execute();
            
            $mensajes = $stmt->fetchAll();
            return $mensajes;
           
        } catch (PDOException $e) {
            echo "<p>Error: " . $e->getMessage(). '</p>';
        }
    }
    
    /**
     * Se registra un usuario.
     * Me hubiera gustado utilizar password_hash() para almacenar el hash de la clave
     * en lugar de la clave en la BD pero necesita 60 caracteres y en cambio 
     * es un varchar de 32. 
     */
    public function registrarUsuario(array $usuario, array &$errores) : bool
    {
        $fechaHoy = date('Y-m-d');
        try{
            $consulta = 'insert into usuarios';
            $consulta.= '(usuario, clave, nombre, apellido, email, foto, fecha_alta) ';
            $consulta.= 'values (?, ?, ?, ?, ?, ?, ?)';
                
            $result = $this->conexion->prepare($consulta);
            $result->bindParam(1, $usuario['usuario']);
            $result->bindParam(2, $usuario['clave']);
            $result->bindParam(3, $usuario['nombre']);
            $result->bindParam(4, $usuario['apellido']);
            $result->bindParam(5, $usuario['email']);
            $result->bindParam(6, $usuario['foto']);
            $result->bindParam(7, $fechaHoy);
            $insercionCorrecta = $result->execute();
        } catch(PDOException $e){
            /* El error 23000 salta cuando hay una Integrity constraint violation.
             * Cuando se ha puesto un usuario o email que ya existe en la BD.*/ 
            if (strpos($e->getMessage(), 'SQLSTATE[23000]') !== false)
                array_push($errores,'Nombre de usuario o email repetidos, elija otros.');
            $insercionCorrecta = false;
        }
        return $insercionCorrecta;
    }
    
    /**
     * Busca un usuario y devuelve true o false si existe o no.
     */
    public function existeUsuario($usuario) : bool {
        try {
            $consulta = "select count(id) from usuarios where usuario=:usu";
            $result = $this->conexion->prepare($consulta);
            $result->bindParam('usu', $usuario);
            $result->execute();
            
            $result = $result->fetchAll();
            $existeUsuario = $result[0][0] == 1;
            
        } catch (PDOException $e) {
            $existeUsuario = false;
            echo "<p>Error: " . $e->getMessage() . '</p>';
        }
        return $existeUsuario;
    }
    
    /**
     * Inserta un mensaje en la base de datos.
     */
    public function enviarMensaje($mensaje, &$errores) : bool {
        $fechaHoy = date('Y-m-d');
        try{
            $consulta = 'insert into mensajes';
            $consulta.= '(remitente, destinatario, asunto, texto, fecha, respuestade) ';
            $consulta.= 'values (?, ?, ?, ?, ?, ?)';
            
            $result = $this->conexion->prepare($consulta);
            $result->bindParam(1, $mensaje['idUsuario']);
            $result->bindParam(2, $mensaje['destinatario']);
            $result->bindParam(3, $mensaje['asunto']);
            $result->bindParam(4, $mensaje['texto']);
            $result->bindParam(5, $fechaHoy);
            $result->bindParam(6, $mensaje['respuestade']);
            $insercionCorrecta = $result->execute();
        } catch(PDOException $e){
            array_push($errores,'Ha ocurrido un error enviando el mensaje.');
            $insercionCorrecta = false;
        }
        return $insercionCorrecta;
    }
    
    /**
     * Busca un usuario y devuelve su id o 0 si no lo encuentra.
     */
    public function obtenerIdPorUsuario(string $usuario) : int {
        try {
            $consulta = "select id from usuarios where usuario=:usu";
            $result = $this->conexion->prepare($consulta);
            $result->bindParam('usu', $usuario);
            $result->execute();
            
            $result = $result->fetch();
            $idUsuario = empty($result)?0:$result['id'];
            
        } catch (PDOException $e) {
            echo "<p>Error: " . $e->getMessage() . '</p>';
        }
        return $idUsuario;
    }
    
    /**
     * Obtiene la ruta de la foto de perfil del usuario si la tuviera
     */
    public function obtenerImagenPorUsuarioId($ruta) {
        try {
            $consulta = "select foto from usuarios where id=:idUsu";
            $result = $this->conexion->prepare($consulta);
            $result->bindParam('idUsu', $ruta);
            $result->execute();
            
            $result = $result->fetch();
            $ruta = empty($result)?false:$result['foto'];
                    
        } catch (PDOException $e) {
            echo "<p>Error: " . $e->getMessage() . '</p>';
        }
        return $ruta;
    }
    
    /***
     * Obtiene el nombre y apellido del usuario, no el usuario.
     */
    public function obtenerNombreUsuario(int $idUsuario){
        try {
            $consulta = "select nombre, apellido from usuarios where id=:idUsu";
            $result = $this->conexion->prepare($consulta);
            $result->bindParam('idUsu', $idUsuario);
            $result->execute();
            
            $result = $result->fetch();
            $nombre = $result['nombre'].' '.$result['apellido'];
            
        } catch (PDOException $e) {
            echo "<p>Error: " . $e->getMessage() . '</p>';
        }
        return $nombre;
    }
}
?>
