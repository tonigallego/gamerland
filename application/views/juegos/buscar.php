<script>
  $(document).ready(function(){
		 $("#buscar_juegos").click(function(evento){
				evento.preventDefault();
				
				form = document.forms[0];
				fCriterio = form.elements["criterio"];
				criterio = fCriterio.value;
				fValor = form.elements["valor"];
				valor = fValor.value;
				if (valor == '') {
					valor = '0';
				}
				
				if (criterio == '') {
					criterio = '0';
				}
				
				$("#capa_resultado").load("<?= base_url('index.php/juegos/buscar/" + criterio + "/" + valor + "/1') ?>");
		 });					 				 
	});
</script>

<?php if (isset($mensaje)): ?>
	<div id="mensaje">
		<p><?= $mensaje ?></p> 
	</div>
<?php endif; ?>

<h2>Búsqueda de juegos</h2><br/>

<div>
	¿No encuentras el juego que buscas? Puedes 
	<?= anchor("juegos/crear", "agregarlo tú mismo", "id='creacion'") ?>
</div>

<br/>

<div>
	<fieldset>
		<legend>Selecciona el criterio de búsqueda</legend>
		<br/>
		<div class="capa">
			<form name="form_buscar_juegos" id="form_buscar_juegos">
				<?= form_label('Criterio:', 'criterio') ?>
				<?= form_dropdown('criterio', $criterios, $criterio) ?> &nbsp - &nbsp
				<?= form_label('Introduce el valor del criterio:', 'valor') ?>
				<?= form_input('valor', $valor) ?> &nbsp - &nbsp
				<input type="submit" value="Buscar" id='buscar_juegos' class='boton'>
			</form>
		</div>		
	</fieldset>
</div>

<br/>

<div id="capa_resultado"></div>
