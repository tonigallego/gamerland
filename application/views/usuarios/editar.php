<?php if (isset($mensaje)): ?>
	<div id="mensaje">
		<p><?= $mensaje ?></p> 
	</div>
<?php endif; ?>

<div>
	<?= form_open('usuarios/cambiar_password') ?>
		<h3>Cambio de contraseña</h3>
		<hr/>
		<br/>
		<table>
  		<tr>
  			<td><?= form_label('Introduce la contraseña anterior:', 'password') ?></td>
				<td><?= form_password('password', '') ?></td>
			</tr>
			<tr><td>&nbsp</td></tr>
			<tr>
				<td><?= form_label('Introduce la nueva contraseña:', 'npassword') ?></td>
				<td><?= form_password('npassword', '') ?></td>
			</tr>
			<tr>
				<td><?= form_label('Confirmar nueva contraseña:', 'confirm_password') ?></td>
				<td><?= form_password('confirm_password', '') ?></td>
			</tr>
		</table>
		<br/> 
		<p>
			<?= form_submit('editar', 'Cambiar contraseña', "class='boton'") ?>
			<?= form_submit('cancelar', 'Cancelar', "class='boton'") ?>
		</p>		
	<?= form_close() ?>
</div>
