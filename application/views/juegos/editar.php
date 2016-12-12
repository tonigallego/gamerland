<script>

	$(document).ready(function(){		 
	
		/* Mostrar cuadros de texto para realizar cambio */

		$("#cambio_nombre").click(function(evento){
			evento.preventDefault();
			$("#ver_nombre").css("display", "none");
			$("#cambiar_nombre").css("display", "table-row");
		});	

		$("#cambio_desarrolladora").click(function(evento){
			evento.preventDefault();
			$("#ver_desarrolladora").css("display", "none");
			$("#cambiar_desarrolladora").css("display", "table-row");
		});	

		$("#cambio_distribuidora").click(function(evento){
			evento.preventDefault();
			$("#ver_distribuidora").css("display", "none");
			$("#cambiar_distribuidora").css("display", "table-row");
		});
		
		$("#cambio_genero").click(function(evento){
			evento.preventDefault();
			$("#ver_genero").css("display", "none");
			$("#cambiar_genero").css("display", "table-row");
		});
		
		$("#cambio_descripcion").click(function(evento){
			evento.preventDefault();
			$("#ver_descripcion").css("display", "none");
			$("#cambiar_descripcion").css("display", "table-row");
		});
		
		$("#cambio_fecha_lanz").click(function(evento){
			evento.preventDefault();
			$("#ver_fecha_lanz").css("display", "none");
			$("#cambiar_fecha_lanz").css("display", "table-row");
		});
		
		$("#cambio_sistema").click(function(evento){
			evento.preventDefault();
			$("#ver_sistema").css("display", "none");
			$("#cambiar_sistema").css("display", "table-row");
		});
		
		
		/* Confirmar intención de cambio */
		
		$("#confirmar_cambio_nombre").click(function(evento){
			if (confirm("¿Confirma que desea modificar el nombre?")) {
				form = document.forms["form_editar_nombre"];
				n = form.elements["nombre"].value;
				destino = "<?= base_url('index.php/juegos/editar/'.$id) ?>";		
				$.post(destino,{nombre: n},function(respuesta){
					$("#capa_cont").html(respuesta);
				});
			}
		});	
		
		$("#confirmar_cambio_desarrolladora").click(function(evento){
			if (confirm("¿Confirma que desea modificar la desarrolladora?")) {
				form = document.forms["form_editar_desarrolladora"];
				d = form.elements["desarrolladora"].value;
				destino = "<?= base_url('index.php/juegos/editar/'.$id) ?>";		
				$.post(destino,{desarrolladora: d},function(respuesta){
					$("#capa_cont").html(respuesta);
				});
			}
		});
		
		$("#confirmar_cambio_distribuidora").click(function(evento){
			if (confirm("¿Confirma que desea modificar el distribuidora?")) {
				form = document.forms["form_editar_distribuidora"];
				d = form.elements["distribuidora"].value;
				destino = "<?= base_url('index.php/juegos/editar/'.$id) ?>";		
				$.post(destino,{distribuidora: d},function(respuesta){
					$("#capa_cont").html(respuesta);
				});
			}
		});
		
		$("#confirmar_cambio_genero").click(function(evento){
			if (confirm("¿Confirma que desea modificar el género?")) {
				form = document.forms["form_editar_genero"];
				g = form.elements["genero"].value;
				destino = "<?= base_url('index.php/juegos/editar/'.$id) ?>";		
				$.post(destino,{genero: g},function(respuesta){
					$("#capa_cont").html(respuesta);
				});
			}
		});	
		
		$("#confirmar_cambio_descripcion").click(function(evento){
			if (confirm("¿Confirma que desea modificar la descripción?")) {
				form = document.forms["form_editar_descripcion"];
				d = form.elements["descripcion"].value;
				destino = "<?= base_url('index.php/juegos/editar/'.$id) ?>";		
				$.post(destino,{descripcion: d},function(respuesta){
					$("#capa_cont").html(respuesta);
				});
			}
		});	
		
		$("#confirmar_cambio_fecha_lanz").click(function(evento){
			if (confirm("¿Confirma que desea modificar la fecha de lanzamiento?")) {
				form = document.forms["form_editar_fecha_lanz"];
				d = form.elements["dia"].value;
				m = form.elements["mes"].value;
				a = form.elements["anio"].value;
				destino = "<?= base_url('index.php/juegos/editar/'.$id) ?>";		
				$.post(destino,{dia: d, mes: m, anio: a},function(respuesta){
					$("#capa_cont").html(respuesta);
				});
			}
		});
		
		$("#confirmar_cambio_sistema").click(function(evento){
			if (confirm("¿Confirma que desea modificar el sistema?")) {
				form = document.forms["form_editar_sistema"];
				s = form.elements["sistema"].value;
				destino = "<?= base_url('index.php/juegos/editar/'.$id) ?>";		
				$.post(destino,{sistema: s},function(respuesta){
					$("#capa_cont").html(respuesta);
				});
			}
		});	


		/* Cancelar intención de cambio */

		$("#cancelar_cambiar_nombre").click(function(evento){
			evento.preventDefault();
			$("#ver_nombre").css("display", "table-row");
			$("#cambiar_nombre").css("display", "none");
		});		

		$("#cancelar_cambiar_desarrolladora").click(function(evento){
			evento.preventDefault();
			$("#ver_desarrolladora").css("display", "table-row");
			$("#cambiar_desarrolladora").css("display", "none");
		});

		$("#cancelar_cambiar_distribuidora").click(function(evento){
			evento.preventDefault();
			$("#ver_distribuidora").css("display", "table-row");
			$("#cambiar_distribuidora").css("display", "none");
		});
		
		$("#cancelar_cambiar_genero").click(function(evento){
			evento.preventDefault();
			$("#ver_genero").css("display", "table-row");
			$("#cambiar_genero").css("display", "none");
		});
		
		$("#cancelar_cambiar_descripcion").click(function(evento){
			evento.preventDefault();
			$("#ver_descripcion").css("display", "table-row");
			$("#cambiar_descripcion").css("display", "none");
		});
		
		$("#cancelar_cambiar_fecha_lanz").click(function(evento){
			evento.preventDefault();
			$("#ver_fecha_lanz").css("display", "table-row");
			$("#cambiar_fecha_lanz").css("display", "none");
		});
		
		$("#cancelar_cambiar_sistema").click(function(evento){
			evento.preventDefault();
			$("#ver_sistema").css("display", "table-row");
			$("#cambiar_sistema").css("display", "none");
		});
		
	});
	
</script>

<div id="capa_cont">
	
	<div class="capa">	
	
		<?php if (isset($mensaje)): ?>
			<div id="mensaje">
				<p><?= $mensaje ?></p> 
			</div>
		<?php endif; ?>

		<h3>Modifica los campos que quieras</h3>
	
		<hr/>
		
		<div class="capa">
		
			<table>
			
				<tr id='ver_nombre' style='display:table-row'>
					<td>Nombre:</td>
					<td style="max-width: 300px;"><strong><?= $nombre ?></strong></td>
					<td><?= anchor('#', '-Cambiar-', "id='cambio_nombre'") ?></td>				
				</tr>			
				<tr id='cambiar_nombre' style='display:none'>				
					<form name="form_editar_nombre" id="form_editar_nombre">
						<td><?= form_label('Nombre: ', 'nombre') ?></td>
						<td>
							<?= form_input('nombre', $nombre) ?>
						</td>
						<td>
							<input type="button" id="confirmar_cambio_nombre" value="Confirmar" class="boton" />
							&nbsp - &nbsp
							<?= anchor('#', 'Cancelar', "id='cancelar_cambiar_nombre'") ?>
						</td>
					</form>				
				</tr>
			
				<tr id='ver_desarrolladora' style='display:table-row'>
					<td>Desarrolladora:</td>
					<td style="max-width: 300px;"><strong><?= $desarrolladora ?></strong></td>
					<td><?= anchor('#', '-Cambiar-', "id='cambio_desarrolladora'") ?></td>				
				</tr>			
				<tr id='cambiar_desarrolladora' style='display:none'>				
					<form name="form_editar_desarrolladora" id="form_editar_desarrolladora">
						<td><?= form_label('Desarrolladora: ', 'desarrolladora') ?></td>
						<td>
							<?= form_input('desarrolladora', $desarrolladora) ?>
						</td>
						<td>
							<input type="button" id="confirmar_cambio_desarrolladora" value="Confirmar" class="boton" />
							&nbsp - &nbsp
							<?= anchor('#', 'Cancelar', "id='cancelar_cambiar_desarrolladora'") ?>
						</td>
					</form>				
				</tr>
			
				<tr id='ver_distribuidora'>
					<td>Distribuidora:</td>
					<td style="max-width: 300px;"><strong><?= $distribuidora ?></strong></td>
					<td><?= anchor('#', '-Cambiar-', "id='cambio_distribuidora'") ?></td>				
				</tr>			
				<tr id='cambiar_distribuidora' style='display:none'>				
					<form name="form_editar_distribuidora" id="form_editar_distribuidora">
						<td><?= form_label('Distribuidora: ', 'distribuidora') ?></td>
						<td>
							<?= form_input('distribuidora', $distribuidora) ?>
						</td>
						<td>
							<input type="button" id="confirmar_cambio_distribuidora" value="Confirmar" class="boton" />
							&nbsp - &nbsp
							<?= anchor('#', 'Cancelar', "id='cancelar_cambiar_distribuidora'") ?>
						</td>
					</form>				
				</tr>
				
				<tr id='ver_genero'>
					<td>Género:</td>
					<td style="max-width: 300px;"><strong><?= $genero ?></strong></td>
					<td><?= anchor('#', '-Cambiar-', "id='cambio_genero'") ?></td>				
				</tr>			
				<tr id='cambiar_genero' style='display:none'>				
					<form name="form_editar_genero" id="form_editar_genero">
						<td><?= form_label('Género:', 'genero') ?></td>
						<td><?= form_dropdown('genero', $generos, $genero) ?></td>
						<td>
							<input type="button" id="confirmar_cambio_genero" value="Confirmar" class="boton" />
							&nbsp - &nbsp
							<?= anchor('#', 'Cancelar', "id='cancelar_cambiar_genero'") ?>
						</td>
					</form>				
				</tr>
				
				<tr id='ver_descripcion'>
					<td>Descripción:</td>
					<td style="max-width: 300px;"><strong><?= $descripcion ?></strong></td>
					<td><?= anchor('#', '-Cambiar-', "id='cambio_descripcion'") ?></td>				
				</tr>			
				<tr id='cambiar_descripcion' style='display:none'>				
					<form name="form_editar_descripcion" id="form_editar_descripcion">
						<td><?= form_label('Descripción:', 'descripcion') ?></td>
						<td><?= form_textarea('descripcion', $descripcion) ?></td>
						<td>
							<input type="button" id="confirmar_cambio_descripcion" value="Confirmar" class="boton" />
							&nbsp - &nbsp
							<?= anchor('#', 'Cancelar', "id='cancelar_cambiar_descripcion'") ?>
						</td>
					</form>				
				</tr>
				
				<tr id='ver_fecha_lanz'>
					<td>Fecha de lanzamiento:</td>
					<td style="max-width: 300px;"><strong><?= date("d-m-Y", strtotime($fecha_lanz)) ?></strong></td>
					<td><?= anchor('#', '-Cambiar-', "id='cambio_fecha_lanz'") ?></td>				
				</tr>			
				<tr id='cambiar_fecha_lanz' style='display:none'>				
					<form name="form_editar_fecha_lanz" id="form_editar_fecha_lanz">
						<td><?= form_label('Fecha de lanzamiento:', '') ?></td>
						<td>
							<input type="text" name="dia" value="<?= date('d',  strtotime($fecha_lanz))?>" size="1" maxlength="2"/>&nbsp/
							<input type="text" name="mes" value="<?= date('m', strtotime($fecha_lanz))?>" size="1" maxlength="2"/>&nbsp/
							<input type="text" name="anio" value="<?= date('Y', strtotime($fecha_lanz))?>" size="2" maxlength="4"/>
						</td> 
						<td>
							<input type="button" id="confirmar_cambio_fecha_lanz" value="Confirmar" class="boton" />
							&nbsp - &nbsp
							<?= anchor('#', 'Cancelar', "id='cancelar_cambiar_fecha_lanz'") ?>
						</td>
					</form>				
				</tr>
				
				<tr id='ver_sistema'>
					<td>Sistema:</td>
					<td style="max-width: 300px;"><strong><?= $sistema ?></strong></td>
					<td><?= anchor('#', '-Cambiar-', "id='cambio_sistema'") ?></td>				
				</tr>			
				<tr id='cambiar_sistema' style='display:none'>	
					<form name="form_editar_sistema" id="form_editar_sistema">
						<td><?= form_label('Sistema:', 'sistema') ?></td>
						<td><?= form_dropdown('sistema', $sistemas, $sistema) ?></td>
						<td>
							<input type="button" id="confirmar_cambio_sistema" value="Confirmar" class="boton" />
							&nbsp - &nbsp
							<?= anchor('#', 'Cancelar', "id='cancelar_cambiar_sistema'") ?>
						</td>
					</form>				
				</tr>
			
			</table>
		
		</div>
		
		<?= form_open("juegos/editar/$id") ?>
			<p>
				<?= form_submit('volver', 'Volver al juego', "class='boton'") ?>
			</p>
		<?= form_close() ?>
	</div>
</div>
