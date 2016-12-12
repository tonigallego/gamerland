<script>
	
	$(document).ready(function(){
		$("#siguiente").click(function(evento){
			evento.preventDefault();				
			$("#capa_comentarios").load("<?= base_url('index.php/tablones/index/'.$id.'/'. ($pagina + 1)) ?>");
		});

		$("#anterior").click(function(evento){
			evento.preventDefault();				
			$("#capa_comentarios").load("<?= base_url('index.php/tablones/index/'.$id.'/'. ($pagina - 1)) ?>");
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
	
	function escribir(){
		$(function(){		
			form = document.forms["form_escribir"];
			texto = form.elements["contenido"].value;
			destino = "<?= base_url('index.php/tablones/escribir/'.$id) ?>";		
			$.post(destino,{contenido: texto},function(respuesta){
				$("#capa_comentarios").html(respuesta);
			});
		});	
	}
</script>

<div id="capa_comentarios">
	<fieldset><legend><h3>Tablón</h3></legend>
		<div class="capa">
		
			<?php if (isset($error_comentario)): ?>
				<div class="error">
					<p><?= $error_comentario ?></p> 
				</div>
				<br/>
			<?php endif; ?>				
			
			<table class="tabla">
				<tr>
					<td colspan=2>
						<?php if (!$this->session->userdata('id')): ?>
							<strong>Debes iniciar sesión para poder dejar un comentario</strong>
						<?php elseif ($es_seguidor || $this->session->userdata('id') == $id): ?>
							Escribe un nuevo comentario:
							<form name="form_escribir" id="form_escribir">
								<?= form_textarea(array('name'=>'contenido', 'rows'=>'2', 'cols'=>'60', 'maxlength'=>'3000')) ?>
								<br/>
								<input type="button" value="Enviar" class="boton"  onClick="escribir();" />
							</form>
						<?php else: ?>
							<strong>Para poder dejar un comentario, este usuario debe ser seguidor tuyo</strong>
						<?php endif; ?>
					</td>
				</tr>
			</table>

			<br/><br/>
	
			<?php if (!empty($comentarios)): ?>
				<?php $recuento = 0 ?>
				<?php $i = 0 ?>
				<?php foreach ($comentarios as $comentario): ?>
					<?php $i += 1; ?>
					<?php extract($comentario); ?>
					<?php $recuento += 1 ?>
					<div id="capa_tabla_comentarios">
						<?php if ($recuento % 2 == 0): ?>
							<table class="tabla" bgcolor='#F8EEEE'>
						<?php else: ?>
							<table class="tabla" bgcolor='#F6D8CE'>
						<?php endif; ?>				
							<tr>
								<td class="celda_emisor" rowspan=2>
									<strong><?= anchor('usuarios/index/'.$emisor, $nombre_emisor) ?> dice:</strong>
									<?php if ($this->session->userdata('id') && 
														($id == $this->session->userdata('id') || $emisor == $this->session->userdata('id'))): ?>
										<form name="form_borrar<?= $i ?>" id="form_borrar<?= $i ?>">
											<?= form_hidden("comentario", $id_comentario) ?>
											<?= form_hidden("emisor", $emisor) ?>
											<?= form_hidden("receptor", $receptor) ?>
											<input type="button" id="borrar<?=$i?>" value="Borrar" class="boton" />
										</form>
										
										<script>
											$("#borrar<?=$i?>").click(function(evento){
												if (confirm("¿Confirma que desea borrar el comentario?")) {
													form = document.forms["form_borrar<?= $i ?>"];
													c = form.elements["comentario"].value;
													e = form.elements["emisor"].value;
													r = form.elements["receptor"].value;
													destino = "<?= base_url('index.php/tablones/borrar') ?>";		
													$.post(destino,{comentario: c, emisor: e, receptor: r},function(respuesta){
														$("#capa_comentarios").html(respuesta);
													});
												}
								 			});								
										</script>
										
									<?php endif; ?>		
								</td>
								<td class="celda_comentario">
									<?php if ($especial == 'f'): ?>
										<em><?= $this->utilidades->transformar_texto($contenido); ?></em>
									<?php else: ?>
										<?= $contenido ?>
									<?php endif; ?>			
								</td>
							</tr>
							<tr>
								<td class="celda_fecha">	
									<em><?= $fecha ?></em>
								</td>
							</tr>
						</table>
					</div>
				<?php endforeach; ?>
			<?php else: ?>
				<table class="tabla">
					<tr><td>Aún no hay comentarios en el tablón</td></tr>
				</table>
			<?php endif; ?>			
		
			<br/>&nbsp
		
			<center>
				<?php if (isset($limite) && $total_filas > $limite): ?>
					<br/><br/>
					<?php for ($i = 1; $i <= ceil($total_paginas); $i++): ?>
						<?php if ($i == 1): ?>
							<?php if ($i == $pagina): ?>
								Anterior
							<?php else: ?>
								<?= anchor('#', 'Anterior', "id='anterior'") ?>
							<?php endif; ?>
						<?php endif; ?>
						<?php if ($i == $pagina): ?>
							<?= $i ?>
						<?php else: ?>
							<?= anchor('#', $i, "id='pagina$i'") ?>
							<script>
					 			$("#pagina<?= $i ?>").click(function(evento){
									evento.preventDefault();	
									$("#capa_comentarios").load("<?= base_url('index.php/tablones/index/'.$id.'/'.$i) ?>");
								});
							</script>
						<?php endif; ?>
						<?php if ($i == ceil($total_paginas)): ?>
							<?php if ($i == $pagina): ?>
								Siguiente
							<?php else: ?>
								<?= anchor('#', 'Siguiente', "id='siguiente'") ?>
							<?php endif; ?>
						<?php endif; ?>
					<?php endfor; ?>
				<?php endif; ?>
			</center>
		</div>
	</fieldset>
</div>

</div>
