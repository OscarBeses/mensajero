<?php ob_start() ?>

    <h2>Registro de usuario</h2>
    
    <!-- Muy importante poner enctype='multipart/form-data' por la foto --> 
   	<form action='index.php' method='POST' enctype='multipart/form-data' class='estrecho cuadroBlanco'>
		<input name='orden' type='hidden' value='registrarUsuario'>
		<input name='usuario' type='text' placeholder='usuario'/>
		<input name='clave' type='password' placeholder='clave'/><br/>
		<input name='nombre' type='text' placeholder='nombre'/>
		<input name='apellido' type='text' placeholder='apellido'/><br/>
		<input name='email' type='email' placeholder='email'/><br/>
		<input type='file' name='foto' placeholder='Suba una foto de perfil'><br/>
		<button type='submit'>Registrarse e iniciar sesión</button>
   	</form>

<?php define('CONTENIDO', ob_get_clean()) ?>
<?php include 'layout.php' ?>