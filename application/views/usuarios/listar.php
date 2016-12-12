<script>
  $(document).ready(function(){
		 $("#siguiente").click(function(evento){
				evento.preventDefault();				
				$("#resultado_listado").load("<?= base_url('index.php/usuarios/listar/'.$tipo.'/'.$id.'/'. ($pagina + 1)) ?>");
		 });
		 
		 $("#anterior").click(function(evento){
				evento.preventDefault();				
				$("#resultado_listado").load("<?= base_url('index.php/usuarios/listar/'.$tipo.'/'.$id.'/'. ($pagina - 1)) ?>");
		 });		 
		 
	});
</script>

<div id='resultado_listado'>
	<?php if (isset($mensaje)): ?>
		<div id="mensaje">
			<p><?= $mensaje ?></p> 
		</div>
	<?php endif; ?>

	<h2><?= $cabecera ?></h2>

	<hr/><br/><br/>

	<div>	
		<?php foreach ($usuarios as $usuario): ?>
			<table>
				<tr>
					<td rowspan=3><?= anchor('usuarios/index/'.$usuario['id'], 
																	 "<img class='avatar' src=".base_url('imagenes/'.$usuario['avatar'])." />") ?></td>
					<td><strong><?= $usuario['nombre'] ?><strong></td>
				</tr>
				<tr><td><em><?= $usuario['email'] ?></em></td></tr>
				<tr><td>
					<?= ($usuario['karma'] < 0) ? "<p class=negativo>" : "<p class=positivo>" ?>
						<?= $usuario['karma'] ?>
						punto<?php if ($usuario['karma'] != 1 && $usuario['karma'] != -1) echo "s"?> de Karma
					</p>
				</td></tr>
				<?php if (isset($usuario['valoracion'])): ?>
					<tr>
						<?php if ($usuario['valoracion'] > 0): ?>
							<td class="positivo" colspan=2>Valoración positiva
						<?php elseif ($usuario['valoracion'] < 0): ?>
							<td class="negativo" colspan=2>Valoración negativa
						<?php endif; ?>
						</td>
					</tr>
				<?php endif; ?>
			</table>
			<br/><br/>								
		<?php endforeach; ?>
	
		<center>
		<?php if (isset($limite) && $total_filas > $limite): ?>
			<br/><br/>
			<?php for ($i = 1; $i <= ceil($total_paginas); $i++): ?>
				<?php if ($i == 1): ?>
					<?php if ($i == $pagina): ?>
						Anterior
					<?php else: ?>
						<?= anchor("usuarios/listar/$tipo/$id/" . ($pagina - 1), 'Anterior', "id='anterior'") ?>
					<?php endif; ?>
				<?php endif; ?>
				<?php if ($i == $pagina): ?>
					<?= $i ?>
				<?php else: ?>
					<?= anchor("usuarios/listar/$tipo/$id/$i", $i, "id='pagina$i'") ?>
					<script>
		   			$("#pagina<?= $i ?>").click(function(evento){
							evento.preventDefault();	
							$("#resultado_listado").load("<?= base_url('index.php/usuarios/listar/'.$tipo.'/'.$id.'/'. $i) ?>");
						});
					</script>
				<?php endif; ?>
				<?php if ($i == ceil($total_paginas)): ?>
					<?php if ($i == $pagina): ?>
						Siguiente
					<?php else: ?>
						<?= anchor("usuarios/listar/$tipo/$id/" . ($pagina + 1), 'Siguiente', "id='siguiente'") ?>
					<?php endif; ?>
				<?php endif; ?>
			<?php endfor; ?>
		<?php endif; ?>
	</center>
	</div>
</div>
