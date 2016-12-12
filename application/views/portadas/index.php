<script>
	$(document).ready(function(){
	
		$("#def_karma").click(function(evento){
	 		evento.preventDefault();
			alert("¿Qué es el Karma? \n \n \tEl Karma es el nivel de prestigio de un usuario en Gamerland. \n\n \t Se mide directamente a través de las valoraciones recibidas en cada crítica que ha realizado el usuario, sumando un punto karma cada valoración positiva, y restando un puntocada valoración negativa. \n\n \t De esta forma podrás distinguir no solo las buenas críticas de las malas antes de leerla, sino un usuario con buen o mal criterio, para saber si puedes o no confiar en sus críticas.");
	 	});
	 	
	 	$("#acerca_de").click(function(evento){
	 		evento.preventDefault();
			alert("Proyecto integrado  -  2º de Desarrollo de Aplicaciones Informáticas \n\n \t\t\t\t\t Curso 2011/2012 \n\n \t\t\tRealizado por: Antonio Gallego Rodríguez");
	 	});
	});
</script>

<?php if (isset($mensaje)): ?>
	<div id="mensaje">
		<p><?= $mensaje ?></p>
	</div>
<?php endif; ?>

<div class="capa">
	<center><?= anchor('#', "<img src=".base_url('imagenes/logo.png')." class='logo' /portada>", "id='acerca_de'") ?></center>
</div>
<br/><br/>

<div class="capa">
	<fieldset><legend><h2>Actualidad</h2></legend>
		<fieldset class='fieldset_interno'><legend><h3>Nuevos juegos</h3></legend>
			<div class="capa">
				<table class="tabla">
					<?php
						if (sizeof($juegos_nuevos) == 0 || sizeof($juegos_nuevos) > 3):
							$limite = 3;
						else:
							$limite = sizeof($juegos_nuevos);
						endif;
					?>
					<tbody>
						<tr>
						<?php $contador = 1; ?>
						<?php foreach ($juegos_nuevos as $juego): ?>
							<td width="266px">
								<?php extract($juego); ?>
								<?= anchor("juegos/index/".$id, 
										"<img class='caratula_pequeña' src=".base_url("imagenes/".$caratula)." /><br/>$nombre") ?>
								<br/><br/>
							</td>
							<?php if ($contador == 3): break; ?>
							<?php else: $contador++ ?>
							<?php endif; ?>
						<?php endforeach; ?>
						<?php if (sizeof($juegos_nuevos) == 0): ?>
							<td colspan="<?=$limite?>">Aún no se han registrado juegos</td>
						<?php endif; ?>
						</tr>
					</tbody>
				</table>
			</div>
		</fieldset>
	
		<br/><hr/><br/>
	
		<fieldset class='fieldset_interno'><legend><h3>Nuevas críticas</h3></legend>
			<div class="capa">
				<table class="tabla">
					<?php
						if (sizeof($criticas_nuevas) == 0 || sizeof($criticas_nuevas) > 3):
							$limite = 3;
						else:
							$limite = sizeof($criticas_nuevas);
						endif;
					?>
					<tbody>	
						<tr>
						<?php $contador = 1; ?>
						<?php foreach ($criticas_nuevas as $critica): ?>				
							<td width="266px">
								<?php extract($critica); ?>
								<?=anchor("criticas/index/$juego/$usuario", 
											"<img class='caratula_pequeña' src=".base_url("imagenes/$caratula")." />
											 <img class='avatar' src=".base_url("imagenes/$avatar")." />")?>
								<br/>
								<?= anchor("juegos/index/$juego", $nombre_juego)." por ".anchor("usuarios/index/$usuario", $nombre_usuario) ?>
								<br/><br/>
							</td>
							<?php if ($contador == 3): break; ?>
							<?php else: $contador++ ?>
							<?php endif; ?>		
						<?php endforeach; ?>
						<?php if (sizeof($criticas_nuevas) == 0): ?>
							<td colspan="<?=$limite?>">Aún no se han realizado criticas</td>
						<?php endif; ?>
						</tr>
					</tbody>
				</table>			
			</div>
		</fieldset>
	</fieldset>
</div>

<div class="capa">
	<fieldset><legend><h2>Top <?= "<img src=".base_url('imagenes/logo.png')." class='logo_pequeño' /portada>" ?></h2></legend>
	
		<fieldset class='fieldset_interno'><legend><h3>Usuarios con más <?= anchor('#', 'Karma', "id='def_karma'") ?></h3> </legend>
			<div class="capa">
				<table class="tabla">
					<tr>
						<?php if (isset($usuarios_top) && !empty($usuarios_top)): ?>
							<?php foreach($usuarios_top as $usuario): ?>
								<td width=110px>
									<?=anchor("usuarios/index/{$usuario['id']}", 
														"<img class='avatar' src=".base_url("imagenes/{$usuario['avatar']}")." />")?>
									<br/>
									<?= anchor("usuarios/index/{$usuario['id']}", $usuario['nombre']) ?>
								</td>
							<?php endforeach; ?>
						<?php else: ?>
							<td>Aún no hay usuarios que tenga un Karma mayor que cero</td>
						<?php endif; ?>
					</tr>
				</table>
			</div>
		</fieldset>
	
		<br/><hr/><br/>
	
		<fieldset class='fieldset_interno'><legend><h3>Juegos con la media más alta</h3></legend>
			<div class="capa">
				<table class="tabla">
					<tr>
						<?php if (isset($juegos_top) && !empty($juegos_top)): ?>
							<?php foreach($juegos_top as $juego): ?>
								<td width=200px>
									<?=anchor("juegos/index/{$juego['id']}", 
														"<img class='caratula_pequeña' src=".base_url("imagenes/{$juego['caratula']}")." />")?>
									<br/>
									<?= anchor("juegos/index/{$juego['id']}", $juego['nombre']) ?>
								</td>
							<?php endforeach; ?>
						<?php else: ?>
							<td>Aún no se han puntuado juegos</td>
						<?php endif; ?>
					</tr>
				</table>
			</div>
		</fieldset>
	
		<br/><hr/><br/>
	
		<fieldset class='fieldset_interno'><legend><h3>Críticas mejor valoradas</h3></legend>
			<div class="capa">
				<table class="tabla">
					<tr>				
						<?php if (isset($criticas_top) && !empty($criticas_top)): ?>
							<?php foreach($criticas_top as $critica): ?>
								<?php extract($critica) ?>
								<td width=200px>
									<?=anchor("criticas/index/$juego/$usuario", 
												"<img class='caratula_pequeña' src=".base_url("imagenes/$caratula")." />
												 <img class='avatar' src=".base_url("imagenes/$avatar")." />")?>
									<br/>
									<?= anchor("juegos/index/$juego", $nombre_juego)." por ".
											anchor("usuarios/index/$usuario", $nombre_usuario) ?>
								</td>	
							<?php endforeach; ?>
						<?php else: ?>
							<td>Aún no se han realizado o no se han valorado críticas</td>	
						<?php endif; ?>					
					</tr>
				</table>
			</div>
		</fieldset>
		
	</fieldset>
</div>

<div class="capa">
	<?php if ($this->session->userdata('id')): ?>
		<fieldset><legend><h2>Recomendaciones para tí</h2></legend>
			
			<fieldset class='fieldset_interno'><legend><h3>Juegos que pueden interesarte</h3></legend>		
				<div class="capa">
					<table class="tabla">
						<tr>				
							<?php if (isset($recomendacion_juegos) && !empty($recomendacion_juegos)): ?>
								<?php foreach($recomendacion_juegos as $juego): ?>
									<?php extract($juego) ?>
									<td width=200px>
										<?=anchor("juegos/index/$id", 
															"<img class='caratula_pequeña' src=".base_url("imagenes/$caratula")." />")?>
										<br/>
										<?= anchor("juegos/index/$id", $nombre) ?>
									</td>	
								<?php endforeach; ?>
							<?php else: ?>
								<td>Aún no tenemos estadísticas suficientes para recomendarte juegos</td>	
							<?php endif; ?>
						</tr>
					</table>
				</div>			
			</fieldset>
			
			<br/><hr><br/>
			
			<fieldset class='fieldset_interno'><legend><h3>Usuarios que comparten tus gustos</h3></legend>		
				<div class="capa">
					<table class="tabla">
						<tr>				
							<?php if (isset($recomendacion_usuarios) && !empty($recomendacion_usuarios)): ?>
								<?php foreach($recomendacion_usuarios as $usuario): ?>
									<?php extract($usuario) ?>
									<td width=110px>
										<?=anchor("usuarios/index/{$usuario['id']}", 
															"<img class='avatar' src=".base_url("imagenes/{$usuario['avatar']}")." />")?>
										<br/>
										<?= anchor("usuarios/index/{$usuario['id']}", $usuario['nombre']) ?>
									</td>
								<?php endforeach; ?>
							<?php else: ?>
								<td>Aún no tenemos estadísticas suficientes para recomendarte usuarios</td>	
							<?php endif; ?>
						</tr>
					</table>
				</div>			
			</fieldset>
			
		</fieldset>
	<?php else: ?>
		<?= anchor('usuarios/crear', '¡Regístrate para poder disfrutar al 100% de Gamerland!') ?>
	<?php endif; ?>
</div>
