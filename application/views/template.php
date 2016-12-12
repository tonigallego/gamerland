<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>Gamerland</title>
    <!--<?= link_tag('estilos/juegos.css') ?>-->
    <link rel="stylesheet" type="text/css" href="<?= base_url('estilos/juegos.css') ?>">
	  <script type="text/javascript" src="<?= base_url('javascript/jquery-1.7.2.js') ?>" ></script>	  
	  <script>
	  
	  	$(document).ready(function(){
				
				$("#contenido").css("display", "none");		
				$("#contenido").show("slow").delay(100);
				$("#relleno").hide("slow").delay(100);
			 	
				$(".bloque").mouseover(function(event){	
					$(event.currentTarget).css("top", "15px");	
				});
				
				$(".bloque").mouseout(function(event){ 
					$(event.currentTarget).css("top", "20px"); 
				});				
				
				$(".boton").mouseover(function(event){					
					$(event.currentTarget).css("position", "relative");
					$(event.currentTarget).css("top", "1px");
					$(event.currentTarget).css("left", "1px");
					$(event.currentTarget).css("bottom", "1px");    		
				});
				
				$(".boton").mouseout(function(event){
					$(event.currentTarget).css("top", "0px");
					$(event.currentTarget).css("left", "0px");
					$(event.currentTarget).css("bottom", "0px"); 
				});
				
			});
	  </script>
  </head>
  <?php if (isset($login) || isset($crear_usuario)): ?>
	<body id="cuerpo_login">
		<div id="logo">
			<?= "<img src=".base_url('imagenes/logo.png')." />" ?>
		</div>
		<div id="contents2"><?= $contents ?></div>
		<?php if (isset($login)): ?>
			<div id="invitado">
				<?= anchor("usuarios/crear", 'Crear usuario') . ' | ' . anchor("portadas/index", 'Seguir como invitado') ?>
			</div>
		<?php endif; ?>
	<?php else: ?>
	<body>
		<div id="cuerpo">
		<div id="header">
			<div class="bloque" id="bloque0">
				<?= anchor("portadas/index", "<img src=".base_url('imagenes/portada.png')." />") ?>
			</div>
			<?php if ($this->session->userdata('id')): ?>
				<div class="bloque" id="bloque1">
					<?= anchor("usuarios/index/".$this->session->userdata('id'), "<img src=".base_url('imagenes/perfil.png')." />") ?>
				</div>
			<?php else: ?>
				<div class="bloque" id="bloque1">
					<?= anchor("usuarios/login", "<img src=".base_url('imagenes/login.png')." />") ?>
				</div>
			<?php endif; ?>
			<div class="bloque" id="bloque2">
				<?= anchor("juegos/index", "<img src=".base_url('imagenes/juegos.png')." />") ?>
			</div>			
			<div class="bloque" id="bloque3">
				<?= anchor("usuarios/buscar", "<img src=".base_url('imagenes/usuarios.png')." />") ?>
			</div>
			<?php if ($this->session->userdata('id')): ?>	
				<div class="bloque" id="bloque4">
					<?= anchor("usuarios/logout", "<img src=".base_url('imagenes/sesion.png')." />") ?>
				</div>
			<?php endif; ?>
		</div>
		<div id="contents1">			
			<div id="contenido"><?= $contents ?></div>
		</div>	
	<?php endif; ?>
		<div id="footer">
			<p></p>
		</div><br/><br/>
		<!-- La capa relleno sirve para hacer espacio y mostrar el fondo de la capa "cuerpo" antes de hacer el efecto show -->
		<div id="relleno">
			<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
		</div>
		
	</body>
</html>
