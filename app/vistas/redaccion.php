<?php ob_start() ?>

    <h2>Redacción de mensaje</h2>
    
    <?php
    if (! empty(Utils::recogerDeSesion('respuestade'))) {
        $destinatarioId = Utils::recogerDeSesion('destinatarioId');
        $pdo = Modelo::getInstance();
        $destinatario = $pdo->obtenerNombreUsuario($destinatarioId);
        echo "<h3>Este mensaje es un mensaje de respuesta para $destinatario</h3>";
    }
    ?>
    
   	<form action='index.php' method='POST' class='cuadroBlanco'>
		<input name='orden' type='hidden' value='enviarMensaje'>
		<input name='asunto' type='text' placeholder='asunto'/>
		<?php if(empty($destinatarioId)){?>
		<input name='destinatario' type='text' placeholder='destinatario'/><br/>
		<?php }?>
		<textarea name='texto' placeholder='escriba aquí el mensaje'
			class='textGrande' rows='7' cols='30'></textarea>
		<button type='submit'>Enviar mensaje</button>
   	</form>

<?php define('CONTENIDO', ob_get_clean()) ?>
<?php include 'layout.php' ?>