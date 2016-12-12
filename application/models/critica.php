<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Critica extends CI_Model {
	
	function obtener($usuario, $juego) {
		return $this->db->query('select * from criticas where usuario = ? and juego = ?', array($usuario, $juego));
	}
	
	function obtener_por_id($id) {
		return $this->db->query('select * from criticas where id = ?', array($id));
	}
	
	function obtener_por_usuario($usuario, $limite, $offset) {
		return $this->db->query('select * from criticas where usuario = ? limit ? offset ?', 
														 array($usuario, $limite, $offset));
	}
	
	function obtener_total_por_usuario($usuario) {
		$res = $this->db->query('select count(*) as total_filas
														 from criticas where usuario = ?', array($usuario))->row_array();
		return $res['total_filas'];
	}
	
	function obtener_por_juego($juego, $limite, $offset) {
		return $this->db->query('select * from criticas where juego = ? limit ? offset ?', 
														 array($juego, $limite, $offset));
	}
	
	function obtener_total_por_juego($juego) {
		$res = $this->db->query('select count(*) as total_filas 
														 from criticas where juego = ?', array($juego))->row_array();
	  return $res['total_filas'];
	}
	
	function obtener_nuevos() {
		return $this->db->query('select c.id as id_critica, 
																		u.id as usuario,																		
																		u.nombre as nombre_usuario,
																		u.avatar as avatar,
																		j.id as juego, 
																		j.nombre as nombre_juego,
																		j.caratula as caratula																
														 from criticas c join usuarios u on c.usuario = u.id join juegos j on c.juego = j.id 
														 order by 1 desc')->result_array();
	}
	
	function obtener_top() {
		return $this->db->query('select sum(valor) - count(*), 
																		c.id, 
																		c.usuario as usuario, 
																		c.juego as juego, 
																		u.nombre as nombre_usuario,
																		u.avatar as avatar, 
																		j.nombre as nombre_juego,
																		j.caratula as caratula
														 from criticas c join usuarios u on c.usuario = u.id 
														 								 join juegos j on c.juego = j.id 
														 								 join valoraciones v on c.id = v.critica 
														 group by c.id, c.usuario, c.juego, u.nombre, j.nombre, u.avatar, j.caratula 
														 order by 1 desc limit 3')->result_array();
	}
	
	function contar_por_juego($juego) {
		$res = $this->db->query('select count(*) as total from criticas where juego = ?', array($juego));
		$res = $res->row_array();
		return $res['total'];
	}
	
	function obtener_datos($usuario, $juego) {
		return $this->db->query('select c.id, c.contenido, c.nota, c.fecha, u.nombre as nombreusuario, j.nombre as nombrejuego 
														 from criticas c join usuarios u on c.usuario = u.id 
																						 join juegos j on c.juego = j.id
														 where usuario = ? and juego = ?', array($usuario, $juego));
	}
	
	function crear($usuario, $juego, $contenido, $nota) {
		$this->db->query('insert into criticas (usuario, juego, contenido, nota)
											values (?, ?, ?, ?)', array($usuario, $juego, $contenido, $nota));
	}
	
	function contar_por_usuario($usuario) {
		$res = $this->db->query('select count(*) as total_criticas from criticas where usuario = ?',
														 array($usuario));
		$res = $res->row_array();
		return $res['total_criticas'];
	}
	
	function actualizar($nota, $contenido, $critica) {
		$this->db->query('update criticas set nota = ?, contenido = ? where id = ?',
											array($nota, $contenido, $critica));
	}
	
	function nota_media_juego($juego) {
		$res = $this->db->query('select round(avg(nota),2) as media from criticas where juego = ?',
														 array($juego));
		$res = $res->row_array();
		return $res['media'];
	}
	
	function media_votos_positivos($id) {
		$res = $this->db->query("select count(cast(valor as integer)) as votos
														 from valoraciones
														 where critica = ? and valor = '1'", array($id));
		$res = $res->row_array();
		$positivos = $res['votos'];
		$res = $this->db->query("select count(cast(valor as integer)) as votos
														 from valoraciones
														 where critica = ? and valor = '-1'", array($id));
		$res = $res->row_array();
		$negativos = $res['votos'];
		$totales = $positivos + $negativos;
		if ($totales == 0) {
			return $totales;
		} else {
			return round(($positivos * 100) / $totales, 2);
		}
	}
	
	function contar_votos($id, $tipo_valor = "") {
		$where = '';
		if ($tipo_valor == 'positivos') {
			$where = "and valor = '1'";
		} elseif ($tipo_valor == 'negativos') {
			$where = "and valor = '0'";
		}
		$res = $this->db->query("select count(*) as votos from valoraciones where critica = ? $where",
														 array($id, ));
		$res = $res->row_array();
		return $res['votos'];
	}
	
	function obtener_valoracion($usuario, $critica) {
		return $this->db->query('select valor from valoraciones where usuario = ? and critica = ?',
														 array($usuario, $critica));
	}
	
	function crear_valoracion($critica, $usuario, $valor) {
		$this->db->query("insert into valoraciones (critica, usuario, valor) values (?, ?, ?)",
											array($critica, $usuario, $valor));
	}
	
	function actualizar_valoracion($critica, $usuario, $valor) {
		$this->db->query("update valoraciones set valor = ? where critica = ? and usuario = ?",
											array($valor, $critica, $usuario));
	}
	
	function eliminar_valoracion($critica, $usuario) {
		$this->db->query('delete from valoraciones where critica = ? and usuario = ?', 
											array($critica, $usuario));
	}

}
