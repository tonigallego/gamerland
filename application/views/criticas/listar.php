<script>
  $(document).ready(function(){
		 $("#siguiente").click(function(evento){
				evento.preventDefault();				
				$("#resultado_listado").load("<?= base_url('index.php/criticas/listar/'.$tipo.'/'.$id_lista.'/'. ($pagina + 1)) ?>");
		 });
		 
		 $("#anterior").click(function(evento){
				evento.preventDefault();				
				$("#resultado_listado").load("<?= base_url('index.php/criticas/listar/'.$tipo.'/'.$id_lista.'/'. ($pagina - 1)) ?>");
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

	<hr/>

	<div class="capa">	
		<?php foreach ($criticas as $critica): ?>
			<?php extract($critica); ?>
			<br/><br/>
			<table class="tabla">
				<tr>
					<td rowspan=4>
						<?=anchor("criticas/index/$juego/$usuario", 
											"<img id='avatar_critica' class='avatar' src=".base_url("imagenes/$avatar")." />
											 <img id='caratula_critica' class='caratula_pequeña' src=".base_url("imagenes/$caratula")." />")?>
					</td>
					<td class="celda_descripcion">
						<strong>
							<?= $titulo ?>
						</strong>
					</td>
				</tr>
				<tr><td class="celda_descripcion">
					<?= ($nota < 5) ? "<p class='negativo'>" : "<p class='positivo'>" ?>
						Nota: <?= $nota ?>
					</p>
				</td></tr>
				<tr><td class="celda_descripcion">
					<?= ($votos_positivos >= $votos/2) ? '<em class="positivo">' : '<em class="negativo">' ?>
						Valoraciones positivas: <?=$votos_positivos?> de <?=$votos?> voto<?= ($votos_positivos != 1) ? 's' : ''?>			
					</em>
				</td></tr>
				<tr><td class="celda_descripcion" width=300>
					<?= substr($critica['contenido'], 0, 100) ?>
					<?php if (strlen($critica['contenido']) > 30) echo "...";?>
				</td></tr>
			</table>
		<?php endforeach; ?>
	
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
								$("#resultado_listado").load("<?= base_url('index.php/criticas/listar/'.$tipo.'/'.$id_lista.'/'. $i) ?>");
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
</div>
