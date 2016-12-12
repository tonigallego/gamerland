<?php if (isset($mensaje)): ?>
	<div id="mensaje">
		<p><?= $mensaje ?></p> 
	</div>
<?php endif; ?>

<div>
	<table class="tabla">
		<?= form_open('usuarios/login') ?>
			<tr>
				<td><?= form_label('Usuario:', 'nombre') ?></td>
				<td><input type="text" name="nombre" maxlength="25" /></td>
			</tr>
			<tr>
				<td><?= form_label('Constraseña:', 'password') ?></td>
				<td><input type="password" name="password" maxlength="25" /></td>
			</tr>
			<tr>
				<td colspan=2><?= form_submit('login', 'Iniciar sesión', "class='boton'") ?></td>
		  </tr>
		<?= form_close() ?>
	</table>
</div>
<br/>
