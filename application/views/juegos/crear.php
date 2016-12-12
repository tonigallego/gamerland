<?php if (isset($mensaje)): ?>
	<div id="mensaje">
		<?php if (!is_array($mensaje)): ?>
			<p><?= $mensaje ?></p> 
		<?php else: ?>
			<?php foreach($mensaje as $m): ?>
				<p><?= $m ?></p>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
<?php endif; ?>

<div class="capa">
	<h3>Creación de juego</h3>
	
	<hr/>
	
	<div class="capa">
		<?= form_open('juegos/crear') ?>
			<table>
				<tr>
					<td><?= form_label('Nombre:', 'nombre') ?></td>
					<td><input type="text" name="nombre" value="<?=$nombre?>" maxlength="35" /></td>
				</tr>
				<tr>
					<td><?= form_label('Desarrolladora:', 'desarrolladora') ?></td>
					<td><input type="text" name="desarrolladora" value="<?=$desarrolladora?>" maxlength="35" /></td>
				</tr>
				<tr>
					<td><?= form_label('Distribuidora:', 'distribuidora') ?></td>
					<td><input type="text" name="distribuidora" value="<?=$distribuidora?>" maxlength="35" /></td> 
				</tr>
				<tr>
					<td><?= form_label('Fecha de lanzamiento:', '') ?></td>
					<td>
						<input type="text" name="dia" value="<?= $dia?>" size="1" maxlength="2"/>&nbsp/
						<input type="text" name="mes" value="<?= $mes?>" size="1" maxlength="2"/>&nbsp/
						<input type="text" name="anio" value="<?= $anio?>" size="2" maxlength="4"/>
					</td> 
				</tr>
				<tr>   
					<td><?= form_label('Genero:', 'genero') ?></td>
					<td><?= form_dropdown('genero', $generos, $genero) ?></td>
				</tr>
				<tr>
					<td><?= form_label('Sistema:', 'sistema') ?></td>
					<td><?= form_dropdown('sistema', $sistemas, $sistema) ?></td>
				</tr>
				<tr>
					<td><?= form_label('Descripción:', 'descripcion') ?></td>
					<td>
						<?= form_textarea(array('name'=>'descripcion', 
																		'value'=>$descripcion,
																		'maxlength'=>'3000')) ?>
					</td>					
				</tr>
			</table>
			<p>
				<?= form_submit('confirmar', 'Confirmar', "class='boton'") ?>
				<?= form_submit('cancelar', 'Cancelar', "class='boton'") ?>
			</p>
		<?= form_close() ?>
	</div>
</div>
