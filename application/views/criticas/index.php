<script>

	function puntuar(valor){	
		$(function(){					
		
			form = document.forms["form1"];			
			cr = form.elements["critica"].value;
			ju = form.elements["juego"].value;
			us = form.elements["usuario"].value;
			destino = "<?= base_url('index.php/criticas/puntuar') ?>";
					
			$.post(destino,{critica: cr, juego: ju, usuario: us, confirmar: valor},function(respuesta){
				$("#contenido").html(respuesta);
			});
		});
		
	}
	
</script>

<div id="contenido">

	<?php if (isset($mensaje)): ?>
		<div id="mensaje">
			<p><?= $mensaje ?></p> 
		</div>
	<?php endif; ?>

	<?php if ($usuario == $this->session->userdata('id')): ?>
		<h1>Crítica de <?= anchor('juegos/index/'.$juego, $nombrejuego) ?> por tí</h1>
	<?php else: ?>
		<h1>Crítica de <?= anchor('juegos/index/'.$juego, $nombrejuego) ?> por 
									 <?= anchor("usuarios/index/".$usuario, $nombreusuario) ?></h1>
	<?php endif;?>

	<div class="capa">
		<table>
			<tr>
				<td>
					<?= anchor("usuarios/index/".$usuario, "<img class='avatar' src=".base_url('imagenes/'.$avatar)." />") ?>
				</td>
				<td class="celda_descripcion">
					<h3 <?= ($karma < 0) ? "class='negativo'" : "class='positivo'" ?>>		
						Karma del autor: <?= $karma ?> punto<?php if ($karma != 1 && $karma != -1) echo "s"; ?>
					</h3>
				</td>
			</tr>
			<tr>
				<td>
					<?= anchor('juegos/index/'.$juego, "<img class='caratula_pequeña' src=".base_url('imagenes/'.$caratula)." />") ?> 
				</td>
				<td class="celda_descripcion">
					<h3 <?= ($media < 5) ? "class='negativo'" : "class='positivo'" ?>>		
						Nota media del juego: <?= $media ?>
					</h3>
				</td>
			</tr>
		</table>
	</div>

	<fieldset><legend>Contenido</legend>
	
		<span id="capa_nota">
			<h2	<?= ($nota < 5) ? "class='negativo'" : "class='positivo'" ?>>
				Nota: <?= $nota ?>
			</h2>
		</span>
	
		<br/><br/>

		<div class="capa">
			<?= $contenido ?>
		</div>
	
		<br/>

		<div style="text-align: right;">
			<em style="font-size: small;">Realizado el <?= date("d-m-Y", strtotime($fecha)) ?></em>	<br/>	
		</div>
	</fieldset>

	<div class="capa">
		<br/>	
		<div>
			<?php if ($votos == 0): ?>
				Aún no han valorado esta crítica
			<?php else: ?>
				<div class="caja_de_barra">
					<div id="barra_positiva" style="width: <?= $positivos ?>%;"><?= $positivos ?>%</div>
					Valoraciones positivas
				</div>
				<br/>
				<div class="caja_de_barra">
					<div id="barra_negativa" style="width: <?= (100 - $positivos) ?>%;"><?= (100 - $positivos) ?>%</div>
					Valoraciones negativas
				</div>
				<br/>
				
			<?php endif; ?>
		</div>
		<?php if ($this->session->userdata('id')): ?>
			<?php if ($usuario != $this->session->userdata('id')): ?>
				<div id="capa_valoracion">
					<form name="form1" id="form1">
						<?= form_hidden('critica', $id) ?>
						<?= form_hidden('juego', $juego) ?>
						<?= form_hidden('usuario', $usuario) ?>
						<?php if (!isset($valoracion)): ?>					
							<input type="button" name="confirmar" class="boton" value="Dar punto positivo" 
										 onClick="puntuar('Dar punto positivo');"/>
							<input type="button" name="confirmar" class="boton" value="Dar punto negativo" 
										 onClick="puntuar('Dar punto negativo');"/>
						<?php elseif ($valoracion == 1): ?>
							<em class="positivo">Has puntuado positivamente esta crítica</em>
							<input type="button" name="confirmar" class="boton" value="Retirar punto" 
										 onClick="puntuar('Retirar punto');"/>
							<input type="button" name="confirmar" class="boton" value="Dar punto negativo" 
										 onClick="puntuar('Dar punto negativo');"/>
						<?php elseif ($valoracion == -1): ?>
							<em class="negativo">Has puntuado negativamente esta crítica</em>
							<input type="button" name="confirmar" class="boton" value="Retirar punto" 
										 onClick="puntuar('Retirar punto');"/>
							<input type="button" name="confirmar" class="boton" value="Dar punto positivo" 
										 onClick="puntuar('Dar punto positivo');"/>
						<?php endif; ?>
					</form>
				</div>
			<?php else: ?>
				<?= form_open('criticas/editar/'.$juego) ?>
					<?= form_hidden('critica', $id) ?>
					<?= form_submit('editar', 'Editar crítica', "class='boton'") ?>
				<?= form_close() ?>
			<?php endif; ?>
			<br/>
			<?php if ($votos == 0): ?>
			<?php elseif ($votos == 1 && isset($valoracion)): ?>
				Solamente tú has puntuado esta crítica
			<?php elseif ($votos == 2 && isset($valoracion)): ?>
				<?= anchor('usuarios/listar/critica/'.$id, 'Otra persona') ?> más ha puntuado esta crítica
			<?php elseif ($votos == 1 && !isset($valoracion)): ?>
				<?= anchor('usuarios/listar/critica/'.$id, 'Una persona') ?> ha puntuado esta crítica
			<?php elseif ($votos > 1 && !isset($valoracion)): ?>
				<?= anchor('usuarios/listar/critica/'.$id, "$votos personas") ?> puntuaron esta crítica
			<?php else: ?>
				<?= anchor('usuarios/listar/critica/'.$id, "Otras ".($votos-1)." personas") ?> más puntuaron esta crítica
			<?php endif; ?>
		<?php endif; ?>
	</div>
	
</div>
