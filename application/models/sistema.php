<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sistema extends CI_Model {

	function obtener_sistemas_de_usuario($usuario) {
		$res = $this->db->query("select distinct s.nombre 
														from sistemas s join pertenece p on s.id = p.sistema 
																						join juegos j on p.juego = j.id 
																						join tiene t on t.juego = j.id 
														where t.usuario = ? and (informacion = 'Lo tienes pendiente' or informacion = 'Te lo has pasado')", 
														array($usuario));
		return $res->result_array();
	}

}
