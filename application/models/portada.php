<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Portada extends CI_Model {
	
	function recomendar_juegos($usuario) {
		$res = $this->Sistema->obtener_sistemas_de_usuario($usuario);
		$sistemas = '';
		$generos = '';
		$and1 = '';
		$and2 = '';
		foreach ($res as $sistema) {
			if ($sistemas != '') $sistemas .= " or ";
			$sistemas .= "s.nombre like '" . $sistema['nombre'] . "'";
		}
		if ($sistemas != '') {
			$and1 = " and ";
			$res = $this->Portada->obtener_generos_de_usuario($usuario);		
			foreach ($res as $genero) {
				if ($generos != '') $generos .= " or ";
				$generos .= "j.genero like '" . $genero['genero'] . "'";
			}
			if ($generos != '') $and2 = " and ";
			$res = $this->db->query("select j.*
															 from juegos_valorados j join pertenece p on j.id = p.juego join sistemas s on p.sistema = s.id
															 where ($sistemas) $and1 ($generos) $and2 nota_media >= 6 
															 			 and j.id not in (select j.id 
															 			 									from juegos j join tiene t on j.id = t.juego
															 			 									where t.usuario = $usuario)
															 order by j.fecha_lanz desc
															 limit 5", array())->result_array();
			return $res;
		} else {
			return array();
		}		
	}
	
	function recomendar_usuarios($usuario) {
		$res = $this->db->query("select count(*), b.id, b.nombre, b.avatar
														 from 	(select t.juego as juego 
														 				 from usuarios u join tiene t on u.id = t.usuario 
														 				 where u.id = ?) 
														 				 as a
														 			join
														 				(select t.juego as juego, u.*
														 				 from karma u join tiene t on u.id = t.usuario 
														 				 where u.id != ? and u.karma > 0 and u.id not in 
														 				 															 (select u.id 
														 																				from usuarios u join seguimientos s on u.id = s.id_seguido 
														 																				where s.id_seguidor = ?)) 
														 				 as b
														 			on a.juego = b.juego
														 	group by b.id, b.nombre, b.avatar
														 	order by 1 desc
														 	limit 5", array($usuario, $usuario, $usuario))->result_array();
		
		return $res;
	}
	
	function obtener_generos_de_usuario($usuario) {
		$generos = $this->db->query("select distinct genero 
														 		 from juegos j join tiene t on j.id = t.juego 
														 		 where t.usuario = ? and t.informacion = 'Te lo has pasado'", 
														 		 array($usuario))->result_array();
		return $generos;
	}
	
}
