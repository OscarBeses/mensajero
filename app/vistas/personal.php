<?php ob_start() ?>

	<h2>Página personal</h2>
	
	<div class='cuadroBlanco'>
        <form action='index.php'>
        	<input name='orden' type='hidden' value='redactarMensaje'/>
        	<button type='submit'>Redactar</button>
        </form>
        <?php if(empty(MENSAJES_RECIBIDOS)){?>
        <p>No hay mensajes recibidos</p>
        <?php }else{?>
        <table id='mensajes'>
        	<caption>Mensajes recibidos<br/>
        	</caption>
        	<thead>
            	<tr>
            		<th>Asunto</th>
            		<th>Fecha</th>
            		<th colspan='2'>Remitente</th>
            	</tr>
        	</thead>
        	<tbody>
        		<?php 
        		foreach (MENSAJES_RECIBIDOS as $mensaje) {
        		    $pdo =  Modelo::getInstance();
        		    $nombreRemitente = $pdo->obtenerNombreUsuario($mensaje['remitente']);
        		?>
                <tr>
            		<td><?php echo $mensaje['asunto'];?></td>
            		<td><?php echo $mensaje['fecha'];?></td>
            		<td><?php echo $nombreRemitente;?></td>
            		<td>
	                <?php
                        echo "<a href='index.php?orden=redactarMensaje
                                &destinatarioId=" . $mensaje['remitente'] . "
                                &respuestade=" . $mensaje['id'] . "'>responder</a>";
                    ?>                         
                    </td>
            	</tr>
            	<tr>
            		<td colspan='4' class='td-bajo'>
            			<p><?php echo $mensaje['texto'];?></p>
            		</td>
            	</tr>
            	<?php }?>
            </tbody>
        </table>
        <?php }?>
    </div>
    
<?php define('CONTENIDO', ob_get_clean()) ?>
<?php include 'layout.php' ?>