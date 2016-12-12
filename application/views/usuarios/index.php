<script>

	$(document).ready(function(){
		 $("#avatar").click(function(evento){
				evento.preventDefault();
				$("#capa_cambiar_avatar").css("display", "block");
		 });	
		 
		 $("#cancelar_cambio_avatar").click(function(evento){
				evento.preventDefault();
				$("#capa_cambiar_avatar").css("display", "none");
		 });
		 
		 $("#cambio_estado").click(function(evento){
				evento.preventDefault();
				$("#ver_estado").css("display", "none");
				$("#cambiar_estado").css("display", "block");
		 });	
		 
		 $("#cancelar_cambiar_estado").click(function(evento){
				evento.preventDefault();
				$("#ver_estado").css("display", "block");
				$("#cambiar_estado").css("display", "none");
		 });
		 
 		$(".boton").mouseover(function(event){					
			$(event.currentTarget).css("position", "relative");
			$(event.currentTarget).css("top", "1px");
			$(event.currentTarget).css("left", "1px");
			$(event.currentTarget).css("bottom", "1px");    		
		});
		
		$(".boton").mouseout(function(event){
			$(event.currentTarget).css("top", "0px");
			$(event.currentTarget).css("left", "0px");
			$(event.currentTarget).css("bottom", "0px"); 
		});						 
	});
	
	function seguimiento(valor){	
		$(function(){		
			form = document.forms["form1"];
			id_usuario = form.elements["id"].value;
			destino = "<?= base_url('index.php/usuarios/cambiar_seguimiento') ?>";		
			$.post(destino,{confirmar: valor, id: id_usuario},function(respuesta){
				$("#contenido_usuario").html(respuesta);
			});
		});		
	}
	
	function cambiar_estado(){
		$(function(){		
			form = document.forms["form_cambio_estado"];
			e = form.elements["estado"].value;
			destino = "<?= base_url('index.php/usuarios/cambiar_estado') ?>";		
			$.post(destino,{estado: e, hay_estado: true},function(respuesta){
				$("#contenido_usuario").html(respuesta);
			});
		});	
	}
	
</script>

<div id="contenido_usuario">

	<?php if (isset($mensaje)): ?>
		<div id="mensaje">
			<p><?= $mensaje ?></p> 
		</div>
	<?php endif; ?>

	<h1><?= ($id == $this->session->userdata('id')) ? "Estás en tu perfil, $nombre" : "Perfil de $nombre"?></h1>

	<br/>

	<div class="capa">
		<span id="capa_karma">
			<h3 <?= ($karma < 0) ? "class='negativo'" : "class='positivo'" ?>>
				Karma: <?= $karma ?> punto<?php if ($karma != 1 && $karma != -1) echo "s"; ?>
			</h3>		
		</span>

		<table>
			<tr>
				<td rowspan=3 width=100 height=100>
					<?php if ($id == $this->session->userdata('id')): ?>
						<?= anchor('', "<img id='avatar' class='avatar' src=".base_url("imagenes/$avatar")." />") ?>
					<?php else: ?>
						<?= "<img id='avatar' class='avatar' src=".base_url("imagenes/$avatar")." />" ?>
					<?php endif; ?>		
				</td>
				<td>
					<?php if ($id == $this->session->userdata('id')): ?>
					
						<div id='cambiar_estado' style='display:none'>
							<form name="form_cambio_estado" id="form_cambio_estado">
								<input type="text" name="estado" value="<?= $estado ?>" maxlength="60"/>
								<input type="button" id="boton_cambio_estado" value="Cambio estado" class="boton" onclick="cambiar_estado();" />
								&nbsp - &nbsp
								<?= anchor("#", 'Cancelar', "id='cancelar_cambiar_estado'") ?>
							</form>
						</div>
						
						<div id='ver_estado' style='display: block;'>
							<?php if (is_null($estado) == true || $estado == ''): ?>
								<?= anchor("#", 'Sin estado', "id='cambio_estado'") ?>
							<?php else: ?>
								<em><?= anchor("#", '"'.$estado.'"', "id='cambio_estado'") ?></em>
							<?php endif; ?>
						</div>
					
					<?php elseif (is_null($estado) == true || $estado == ''): ?>
						Sin estado
					<?php else: ?>
						<em><?= '"'.$estado.'"' ?></em>
					<?php endif; ?>				
				</td>
			</tr>
			<tr><td><h3><em><?= $email ?></em></h3></td></tr>
		</table>
		<?php if ($id == $this->session->userdata('id')): ?>		
			<div id='capa_cambiar_avatar' style="display: none">
				<?= form_open_multipart('usuarios/cambiar_avatar')?>
					<input type="file" name="userfile" size="20"/>
					<p style="font-size: small">
						La imagen debe ser de máximo 200kb, 80x80 píxeles y en un formato compatible (gif, png o jpg)
					</p>
					<?= form_submit('cambio_avatar', 'Cambiar avatar', "class='boton'") ?>
					&nbsp - &nbsp
					<?= anchor('#', 'Cancelar', "id='cancelar_cambio_avatar'") ?>
				<?= form_close() ?>
				<br/>
			</div>
			<?= anchor('usuarios/cambiar_password', 'Cambiar contraseña') ?>
		<?php elseif (isset($seguimiento)): ?>
			<div id='capa_cambiar_seguimiento'>
				<form name="form1" id="form1">
					<?php if ($seguimiento == true): ?>
						Sigues a este usuario
						<input type="button" name="confirmar" class="boton" value="Dejar de seguir" 
									 onClick="seguimiento('Dejar de seguir');"/>
					<?php else: ?>
						No sigues a este usuario
						<input type="button" name="confirmar" class="boton" value="Seguir" 
									 onClick="seguimiento('Seguir');"/>
					<?php endif; ?>
					<?= form_hidden('id', $id) ?>
				</form>
			</div>
		<?php endif; ?>
	</div>

	<div class="capa">
		<fieldset><legend><h3>Información de <?= ($id == $this->session->userdata('id') ? 't' : 's') ?>us juegos</h3></legend>
			<table>
			<?php if (!empty($sistemas)): ?>
				<tr>
					<td>Sistemas que posee<?= ($id == $this->session->userdata('id') ? 's' : '') ?>:</td>
					<td>
						<?php foreach ($sistemas as $clave => $sistema): ?>
							<?= "<strong>" . $sistema['nombre'] . "</strong>" ?>
							<?php if ($clave < count($sistemas) - 1) echo "&nbsp&nbsp-&nbsp&nbsp"; ?>
						<?php endforeach; ?>
					</td>
				</tr>
			<?php endif; ?>
			<tr>
				<td>Juegos pasados:</td>
				<td>
					<?= $pasados ?>&nbsp
					<?php if ($pasados > 0) echo anchor('juegos/listar/'.$id."/pasados", '-Ver-'); ?>
				</td>
			</tr>
			<tr>
				<td>Juegos pendientes:</td>
				<td>
					<?= $pendientes ?>&nbsp
					<?php if ($pendientes > 0) echo anchor('juegos/listar/'.$id."/pendientes", '-Ver-'); ?>
				</td>
			</tr>
			<tr>
				<td>Juegos deseados:</td>
				<td>
					<?= $deseados ?>&nbsp
					<?php if ($deseados > 0) echo anchor('juegos/listar/'.$id."/deseados", '-Ver-'); ?>
				</td>
			</tr>
			<tr>
				<td>Críticas realizadas:</td>
				<td>
					<?= $total_criticas ?>&nbsp
					<?php if ($total_criticas > 0) echo anchor('criticas/listar/usuario/'.$id, '-Ver-'); ?>
				</td>
			</tr>
			</table>
		</fieldset>
	</div>

	<div class="capa">
		<fieldset><legend><h3>Contactos</h3></legend>
			<table class="tabla" width= 800px>
				<?php
					if ($total_seguidos == 0): $limite_seguidos = 1;
					elseif ($total_seguidos > 3):	$limite_seguidos = 3;
					else:	$limite_seguidos = $total_seguidos;
					endif;				
					if ($total_seguidores == 0): $limite_seguidores = 1;
					elseif ($total_seguidores > 3):	$limite_seguidores = 3;
					else:	$limite_seguidores = $total_seguidores;
					endif;
				?>
				<thead>
					<th colspan="<?= $limite_seguidos ?>" width=50%>
						Usuarios a los que sigue<?= ($id == $this->session->userdata('id') ? 's' : '') ?>
					</th>
					<th colspan="<?= $limite_seguidores ?>" width=50%>
						Usuarios que <?= ($id == $this->session->userdata('id') ? 't' : 'l') ?>e siguen
					</th>
				</thead>
				<tbody>
					<tr>
						<td colspan="<?= $limite_seguidos ?>" width=50%><hr/></td>
						<td colspan="<?= $limite_seguidores ?>" width=50%><hr/></td>
					</tr>			
					<tr>		
					<?php for ($i=0; $i < 3; $i++): ?>			
						<?php if (isset($seguidos[$i])): ?>
							<td>
								<?= anchor("usuarios/index/".$seguidos[$i]['id'], 
										"<img class='avatar' src=".base_url("imagenes/".$seguidos[$i]['avatar'])." />") ?>
								<br/>
								<?= anchor("usuarios/index/".$seguidos[$i]['id'], $seguidos[$i]['nombre']) ?><br/><br/>
							</td>
						<?php	elseif ($i == 0): ?>
							<td colspan="<?= $limite_seguidos ?>" width=50%>
								Aún no sigue<?= ($id == $this->session->userdata('id')) ? 's' : ''?> a ningún usuario
							</td>
						<?php endif; ?>
					<?php endfor; ?>
					<?php for ($i=0; $i < 3; $i++): ?>	
						<?php if (isset($seguidores[$i])): ?>
							<td>
								<?= anchor("usuarios/index/".$seguidores[$i]['id'], 
										"<img class='avatar' src=".base_url("imagenes/".$seguidores[$i]['avatar'])." />") ?>
								<br/>
								<?= anchor("usuarios/index/".$seguidores[$i]['id'], $seguidores[$i]['nombre']) ?><br/><br/>
							</td>
						<?php	elseif ($i == 0): ?>
							<td colspan="<?= $limite_seguidores ?>" width=50%>
								Aún no tiene<?= ($id == $this->session->userdata('id')) ? 's' : ''?> seguidores
							</td>
						<?php endif; ?>	
					<?php endfor; ?>
					</tr>
					<tr>
						<td colspan="<?= $limite_seguidos ?>">
							<?php if ($total_seguidos > 3) echo anchor('usuarios/listar/seguidos/'.$id, "Ver todos ($total_seguidos)");?>
						</td>
						<td colspan="<?= $limite_seguidores ?>">	
							<?php if ($total_seguidores > 3) echo anchor('usuarios/listar/seguidores/'.$id, "Ver todos ($total_seguidores)");?>
						</td>		
					</tr>
				</tbody>
			</table>
		</fieldset>
	</div>

</div>

<div class="capa">
