<?php if (isset($mensajes)): ?>
	<?php foreach($mensajes as $mensaje): ?>
		<div class="error">
			<p><?= $mensaje ?></p> 
		</div>
	<?php endforeach; ?>
<?php endif; ?>

<?= form_open('usuarios/crear') ?>
  <table class="tabla">
  	<tr>
    	<td style="text-align: left;"><?= form_label('Nombre de usuario:', 'nombre') ?></td>
    	<td><input type="text" name="nombre" value="<?=$nombre?>" maxlength="25" /></td>
    </tr>
    <tr>
    	<td style="text-align: left;"><?= form_label('Constraseña:', 'password') ?></td>
    	<td><input type="password" name="password" maxlength="25" /></td>
    </tr>
    <tr>
    	<td style="text-align: left;"><?= form_label('Confirmar contraseña:', 'confirm_password') ?></td>
    	<td><input type="password" name="confirm_password" maxlength="25" /></td>
    </tr>
    <tr>
    	<td style="text-align: left;"><?= form_label('Email:', 'email') ?></td>
    	<td><input type="text" name="email" maxlength="50" /></td>
  	</tr>
  </table>
	<br/>
  <div>
  	<?= form_submit('crear', 'Crear usuario', "class='boton'") ?>
    <?= form_submit('cancelar', 'Cancelar', "class='boton'") ?>
  </div>
<?= form_close() ?>
