<script>
	$(document).ready(function(){
	
		$("#caratula_juego").click(function(evento){
			evento.preventDefault();
			$("#capa_cambiar_caratula").css("display", "block");
		});
		 
 		$("#cancelar_cambio_caratula").click(function(evento){
			evento.preventDefault();
			$("#capa_cambiar_caratula").css("display", "none");
		});		
		 					 
	});
	
	function enviar_inf(valor){	
		$(function(){
			form = document.forms["form1"];
			juego = form.elements["idjuego"].value;
			destino = "<?= base_url('index.php/juegos/cambiar_estado/'.$id.'/'.$usuario) ?>";		
			$.post(destino,{informacion: valor, idjuego: juego},function(respuesta){
				$("#capa_cont").html(respuesta);
			});
		});		
	}
</script>

<div id="capa_cont">

	<?php if (isset($mensaje)): ?>
		<div id="mensaje">
			<p><?= $mensaje ?></p>
		</div>
	<?php endif; ?>

	<h1><?= $nombre ?></h1>

	<br/>

	<div class="capa">
		<span id="capa_nota">
			<?php if (!is_numeric($nota_media)): ?>
				<h3><?= $nota_media ?></h3>
			<?php else: ?>
				<h3 <?= ($nota_media < 5) ? "class='negativo'" : "class='positivo'" ?>>Nota media: <?= $nota_media ?></h3>
			<?php endif; ?>
		</span>

		<table>
			<tr>
				<td valign=top>			
					<?php if ($this->session->userdata('id')): ?>
						<?= anchor('juegos/cambiar_caratula/'.$id, 
											 "<img id='caratula_juego' class='caratula' src=".base_url("imagenes/$caratula")." />") ?>
					<?php else: ?>
						<?= "<img class='caratula' src=".base_url("imagenes/$caratula")." />" ?>
					<?php endif; ?>
				</td>
				<td>
					Desarrolladora: <strong><?= $desarrolladora ?></strong>
					<br><br>
					Distribuidora: <strong><?= $distribuidora ?></strong>
					<br><br>
					Lanzamiento: <strong><?= date("d-m-Y", strtotime($fecha_lanz)) ?></strong>
					<br><br>
					Género: <strong><?= $genero ?></strong>
					<br><br>
					Sistema: <strong><?= $sistema ?></strong>
					<br><br>
					Descripción: <strong><?= $descripcion ?></strong>
				</td>			
			</tr>
			<tr>
				<td width=300>
					<div id='capa_cambiar_caratula' style='display:none'>
						<?= form_open_multipart('juegos/cambiar_caratula/'.$id)?>
							<input type="file" name="userfile" size="20"/>
							<p style="font-size: small">
								La imagen debe ser de máximo 400kb, 1000x750 píxeles y en un formato compatible (gif, png o jpg)
							</p>
							<?= form_submit('cambio_caratula', 'Cambiar caratula', "class='boton'") ?>
							&nbsp - &nbsp
							<?= anchor('juegos/index/'.$id, 'Cancelar', "id='cancelar_cambio_caratula'") ?>
						<?= form_close() ?>
						<hr/>
					</div>				
				</td>
			</tr>

		</table>
	</div>

	<?php if ($this->session->userdata('id')): ?>
		<center><?= anchor('juegos/editar/'.$id, "- Editar datos de $nombre -") ?></center>
	<?php endif; ?>

	<?php if (isset($estado)): ?>
		Estado: <?= $estado ?><br/>

		<form name="form1">
			<?= form_hidden('idjuego', $id) ?>
			<?php if ($estado == "No lo tienes"): ?>
				<input type="button" class="boton" value = "Lo quiero" onClick="enviar_inf('Lo quiero');"/>
				<input type="button" class="boton" value = "Lo tengo" onClick="enviar_inf('Lo tengo');"/>
			<?php elseif ($estado == "Lo quieres"): ?>
				<input type="button" class="boton" value = "Lo tengo" onClick="enviar_inf('Lo tengo');"/>
				<input type="button" class="boton" value = "Ya no lo quiero" onClick="enviar_inf('Ya no lo quiero');"/>
			<?php elseif ($estado == "Lo tienes pendiente"): ?>
				<input type="button" class="boton" value = "Me lo he pasado" onClick="enviar_inf('Me lo he pasado');"/>
				<input type="button" class="boton" value = "Ya no lo tengo" onClick="enviar_inf('Ya no lo tengo');"/>
			<?php endif; ?>
		</form>

		<?php if ($estado == "Te lo has pasado"): ?>
			<?php if (isset($existe_critica)): ?>
				<?php $accion = "Ver mi"; ?>
				<?php $destino = 'criticas/index'; ?>
			<?php else: ?>
				<?php $accion = "Crear"; ?>
				<?php $destino = 'criticas/crear/'.$id;?>
			<?php endif; ?>		

			<?= form_open($destino) ?>
				<?= form_hidden('idjuego', $id) ?>
					<?= form_submit('critica', $accion . ' crítica', "class='boton'") ?>				
			<?= form_close() ?>
		<?php endif; ?>
	<?php endif; ?>

	<div class="capa">
		<fieldset><legend><h3>Estadísticas</h3></legend>
			<table>
				<tr><td>
					Total críticas: <?= $total_criticas ?>
					<?php if ($total_criticas > 0) echo anchor("criticas/listar/juego/$id", '-Ver-'); ?>
				</td></tr>
				<tr><td>
					Usuarios que se lo han pasado: <?= $pasados ?>
					<?php if ($pasados > 0) echo anchor("usuarios/listar/pasado/$id", '-Ver-'); ?>
				</td></tr>
				<tr><td>
					Usuarios que lo tienen pendiente: <?= $pendientes ?>
					<?php if ($pendientes > 0) echo anchor("usuarios/listar/pendiente/$id", '-Ver-'); ?>
				</td></tr>
				<tr><td>
					Usuarios que lo desean: <?= $deseados ?>
					<?php if ($deseados > 0) echo anchor("usuarios/listar/deseado/$id", '-Ver-'); ?>
				</td></tr>
			</table>
		</fieldset>
	</div>

	<div class="capa">
		<fieldset><legend><h3>Similares a <?=$nombre?></h3></legend>
			<div class="capa">
				<table class="tabla">
					<tr>
					<?php foreach ($juegos_similares as $juego): ?>
						<td width=200px>
							<?=anchor("juegos/index/{$juego['id']}", 
												"<img class='caratula_pequeña' src=".base_url("imagenes/{$juego['caratula']}")." />")?>
							<br/>
							<?= anchor("juegos/index/{$juego['id']}", $juego['nombre']) ?>
						</td>
					<?php endforeach; ?>
					<?php if (sizeof($juegos_similares) == 0) echo "<td>No se han encontrado juegos similares a éste</td>"; ?>
					</tr>
				</table>
			</div>
		</fieldset>
	</div>

</div>
