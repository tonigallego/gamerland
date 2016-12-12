<script>
  $(document).ready(function(){
		 $("#siguiente").click(function(evento){
				evento.preventDefault();				
				$("#resultado_busqueda").load("<?= base_url('index.php/usuarios/buscar/'.$valor.'/'. ($pagina + 1)) ?>");
		 });
		 
		 $("#anterior").click(function(evento){
				evento.preventDefault();				
				$("#resultado_busqueda").load("<?= base_url('index.php/usuarios/buscar/'.$valor.'/'. ($pagina - 1)) ?>");
		 });		 
		 
	});
</script>

<div id='resultado_busqueda'>
	<fieldset id="busqueda">
		<legend>Resultado de la búsqueda</legend>
		<br/>
		<div class="capa">
			<?php if (isset($resultado)): ?>
				<em class="negativo"><?= $resultado ?></em><br/><br/>
			<?php elseif (isset($usuarios)): ?>		
				<?php foreach ($usuarios as $usuario): ?>
					<?php extract($usuario) ?>
					<table>
						<tr>
							<td rowspan=3><?= anchor('usuarios/index/'.$id, 
																										 "<img class='avatar' src=".base_url('imagenes/'.$avatar)." />") ?>
							</td>
							<td><strong><?= $nombre ?><strong></td>
						</tr>
						<tr>
							<td>
								<em><?= $email ?></em>
							</td>
						</tr>
						<tr>
							<td>
								<?= ($karma < 0) ? "<p class=negativo>" : "<p class=positivo>" ?>
									<?= $karma ?> punto<?php if ($karma != 1 && $karma != -1) echo "s"?> de Karma
								</p>
							</td>
						</tr>
					</table>
					<br/><br/>												
				<?php endforeach; ?>
			<?php endif; ?>
	
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
							<?= anchor("#", $i, "id='pagina$i'") ?>
							<script>
				   			$("#pagina<?= $i ?>").click(function(evento){
									evento.preventDefault();	
									$("#resultado_busqueda").load("<?= base_url('index.php/usuarios/buscar/'.$valor.'/'.$i) ?>");
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
