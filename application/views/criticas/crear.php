<?php if (isset($mensaje)): ?>
	<div id="mensaje">
		<p><?= $mensaje ?></p> 
	</div>
<?php endif; ?>

<div class="capa">
	
	<h3>Creación de crítica</h3>
	
	<hr/>

	<?= form_open('criticas/crear/' . $juego) ?>
		<div class="capa">
		  <?= form_label('Cuerpo de la crítica:', 'contenido') ?><br/>
		  <?= form_textarea(array('name'=>'contenido', 'value'=>$contenido, 'cols'=>'90', 'maxlength'=>'30000')) ?><br/>
		  <?= form_label('Nota:', 'nota') ?>
		  <?= form_dropdown('nota', $notas, $nota) ?><br/>
		</div> 
		<div>
			<?= form_submit('confirmar', 'Confirmar', "class='boton'") ?>
		  <?= form_submit('cancelar', 'Cancelar', "class='boton'") ?>
		</div>
	<?= form_close() ?>
</div>
