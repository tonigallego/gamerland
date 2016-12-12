<script>
  $(document).ready(function(){
		 $("#siguiente").click(function(evento){
				evento.preventDefault();				
				$("#resultado_busqueda").load("<?= base_url('index.php/juegos/buscar/'.$criterio.'/'.$valor.'/'. ($pagina + 1)) ?>");
		 });
		 
		 $("#anterior").click(function(evento){
				evento.preventDefault();				
				$("#resultado_busqueda").load("<?= base_url('index.php/juegos/buscar/'.$criterio.'/'.$valor.'/'. ($pagina - 1)) ?>");
		 });		 
		 
	});
</script>

<div id='resultado_busqueda'>
	<fieldset>
		<legend>Resultado de la búsqueda</legend>
		<div class="capa">	
			<?php if (isset($resultado)): ?>
				<em class="negativo"><?= $resultado ?></em>
			<?php elseif (isset($juegos)): ?>
				<?php foreach ($juegos as $juego): ?>
					<br/><br/>
					<table>
						<tr>
							<td rowspan=4>
								<?=anchor('juegos/index/'.$juego['id'], 
													"<img class='caratula_pequeña' src=".base_url("imagenes/{$juego['caratula']}")." />")?>
							</td>
							<td>Nombre: <strong><?= $juego['nombre'] ?></strong></td>
						</tr>
						<tr><td>Distribuidora: <strong><?= $juego['distribuidora'] ?></strong></td></tr>
						<tr><td>Desarrolladora: <strong><?= $juego['desarrolladora'] ?></strong></td></tr>
						<tr><td>Género: <strong><?= $juego['genero'] ?></strong></td></tr>
					</table>
				<?php endforeach; ?>
			<?php endif; ?>
			<br/>
			<center>
				<?php if (isset($limite) && $total_filas > $limite): ?>
					<?php for ($i = 1; $i <= ceil($total_paginas); $i++): ?>
						<?php if ($i == 1): ?>
							<?php if ($i == $pagina): ?>
								Anterior
							<?php else: ?>
								<?= anchor("#", 'Anterior', "id='anterior'") ?>
							<?php endif; ?>
						<?php endif; ?>
						<?php if ($i == $pagina): ?>
							<?= $i ?>
						<?php else: ?>
							<?= anchor("#", $i, "id='pagina$i'") ?>
							<script>
					 			$("#pagina<?= $i ?>").click(function(evento){
									evento.preventDefault();	
									$("#resultado_busqueda").load("<?= base_url('index.php/juegos/buscar/'.$criterio.'/'.$valor.'/'.$i) ?>");
								});
							</script>
						<?php endif; ?>
						<?php if ($i == ceil($total_paginas)): ?>
							<?php if ($i == $pagina): ?>
								Siguiente
							<?php else: ?>
								<?= anchor("#", 'Siguiente', "id='siguiente'") ?>
							<?php endif; ?>
						<?php endif; ?>
					<?php endfor; ?>
				<?php endif; ?>
			</center>
		</div>
	</fieldset>
</div>
