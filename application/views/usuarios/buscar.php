<script>
  $(document).ready(function(){
  
		 $("#buscar_usuarios").click(function(evento){
				evento.preventDefault();
				
				form = document.forms[0];
				fValor = form.elements["valor"];
				valor = fValor.value;
				if (valor == '') {
					valor = '0';
				}
				
				$("#capa_resultado").load("<?= base_url('index.php/usuarios/buscar/" + valor + "/1') ?>");
		 });
		 
	});
</script>

<?php if (isset($mensaje)): ?>
	<div id="mensaje">
		<p><?= $mensaje ?></p> 
	</div>
<?php endif; ?>

<h2>Búsqueda de usuarios</h2><br/>

<div>
	<fieldset>
		<legend>Introduce el valor en el campo de texto </legend>
		<br/>
		<div class="capa">
			<form name="form_buscar_usuarios" id="form_buscar_usuarios">
				<?= form_input('valor', $valor) ?> &nbsp - &nbsp
				<input type="submit" value="Buscar" id='buscar_usuarios' class='boton'>
			</form>
		</div>
	</fieldset>
</div>

<br/>

<div id="capa_resultado"></div>
