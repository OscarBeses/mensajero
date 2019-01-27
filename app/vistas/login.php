<?php ob_start() ?>

    <h2>Login</h2>
    
    <div class='estrecho'>
    	<form action='index.php' method='POST' class='cuadroBlanco'>
    		<input name='orden' type='hidden' value='iniciarSesion'>
    		<input name='usuario' type='text' placeholder='usuario'/>
    		<input name='clave' type='password' placeholder='clave'/>
    		<button type='submit'>Iniciar sesión</button>
    	</form>
    	<form action='index.php' method='GET' class='cuadroBlanco'>
    		<input name='orden' type='hidden' value='abrirRegistro'>
    		<button type='submit'>Registrar usuario</button>
    	</form>
    </div>

<?php define('CONTENIDO', ob_get_clean()) ?>
<?php include 'layout.php' ?>