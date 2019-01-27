<!DOCTYPE html>
<html lang='es'>

    <head>
        <title>Mensajero Oscar</title>
        <meta charset='utf-8' />
        <link rel='stylesheet' type='text/css' 
        	href='<?php echo('css/'.Config::$mvc_vis_css);?>' />
    </head>
    
    <body>
    	<header>
		<h1>Mensajero Oscar 
			<?php $idUsuario =Utils::recogerDeSesion('idUsuario');
			if(!empty($idUsuario)){
			    $pdo =  Modelo::getInstance();
			    $nombre = $pdo->obtenerNombreUsuario($idUsuario);
			    echo "<small> -> Bienvenido $nombre </small>";
			}?>
		</h1>
		<?php //Con esto se muestra la foto de perfil subida, si la hubiera
        $userId = Utils::recogerDeSesion('idUsuario');
        if ($userId != 0) {
            $pdo =  Modelo::getInstance();
            $imagen = $pdo->obtenerImagenPorUsuarioId($userId);
            if ($imagen != false)
                echo "<img alt='Foto de usuario' src='fotosPerfil/$imagen' />";
        }?>
    	</header>
    	<main>
    		<!-- Antes del contenido mostramos los posibles errores o notificaciones -->
    		<?php if(!empty($errores))Servicio::mostrarMensaje('Errores',$errores);?>
    		<?php if(!empty($notif))Servicio::mostrarMensaje('Correcto',$notif);?>
    		<!-- Y después cargamos el contenido que corresponda -->
        	<?php echo CONTENIDO;?>
    	</main>
    	<footer>
    		<!-- Muestro el botón de Logout cuando haya un userId en sesión -->
    		<?php if($userId != 0){?>
    		<form action='index.php'>
        		<button type='submit'>LogOut</button>
        		<input type='hidden' name='orden' value='logout'>
    		</form>
    		<?php }?>
    	</footer>
    </body>

</html>