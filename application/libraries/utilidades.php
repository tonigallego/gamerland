<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Utilidades {

  function recoger_datos_tablon($usuario, $pagina) {
  	$limite = 10;
  	if ($pagina == 0) $pagina = 1;
		$offset = ($limite * $pagina) - $limite;
		$data = array('pagina' => $pagina, 'limite' => $limite, 'usuario' => $usuario);  	
  	$CI =& get_instance();
  	$CI->load->model('Tablon');
  	$total_filas = $CI->Tablon->obtener_total_comentarios($usuario);
  	$data['total_paginas'] = $total_filas / $limite;
		$data['total_filas'] = $total_filas;		 											 
		$data['comentarios'] = $CI->Tablon->obtener_comentarios($usuario, $limite, $offset);
		return $data;
  }
  
  function transformar_texto($texto) {
  	return nl2br(htmlentities($texto, ENT_COMPAT, 'UTF-8'));
  }
  
  function _escribir_actualizacion($comentario, $emisor) {
		if ($emisor != '' && trim($comentario) != '') {
			$CI =& get_instance();
  		$CI->load->model('Tablon');
			$CI->Tablon->escribir($comentario, $emisor, $emisor, 'true');
			$CI->load->model('Usuario');
			$seguidores = $CI->Usuario->obtener_seguidores($emisor)->result_array();
			foreach ($seguidores as $seguidor) {
				$CI->Tablon->escribir($comentario, $emisor, $seguidor['id'], 'true');
			}			
		}
	}

}

