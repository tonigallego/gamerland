<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Juego extends CI_Model {
	
	function obtener($juego) {
		return $this->db->query('select * from juegos where id = ?', array($juego));
	}
	
	function obtener_datos($id) {
		return $this->db->query("select j.*, s.nombre as sistema 
														 from juegos j join pertenece p on (j.id = p.juego) join sistemas s on (p.sistema = s.id) 
														 where j.id = ?", array($id));
	}
	
	function obtener_nuevos() {
		return $this->db->query('select * from juegos order by id desc')->result_array();
	}
	
	function obtener_top() {
		return $this->db->query('select avg(c.nota), j.nombre as nombre, j.id as id, j.caratula as caratula
														 from criticas c join juegos j on c.juego = j.id group by j.id, j.nombre, j.caratula
														 order by 1 desc limit 3')->result_array();
	}
	
	function obtener_similares($juego) {
		$res = $this->Juego->obtener_datos($juego)->row_array();
		$sistema = $res['sistema'];
		$genero = $res['genero'];
		$res = $this->db->query("select j.*
														 from juegos j join pertenece p on j.id = p.juego join sistemas s on p.sistema = s.id
														 where s.nombre like ? and j.genero like ? and j.id != ?
														 order by j.fecha_lanz desc
														 limit 5", array($sistema, $genero, $juego))->result_array();
		return $res;
	}
	
	function obtener_nota_media($id) {
		$res = $this->db->query("select round(avg(nota), 2) as nota_media from criticas where juego = ?", array($id));
		$res = $res->row_array();
		return (empty($res['nota_media'])) ? 'Aún no puntuado' : $res['nota_media'];
	}
	
	function contar($juego, $informacion = '') {
		if ($informacion == 'pasados'): $informacion = 'Te lo has pasado';
		elseif ($informacion == 'pendientes'): $informacion = 'Lo tienes pendiente';
		elseif ($informacion == 'deseados'): $informacion = 'Lo quieres';
		else: $informacion = '';
		endif;
		$where = '';
		if ($informacion != '') $where = " and informacion = '$informacion'";
		
		$res = $this->db->query("select count(*) as total from tiene where juego = ?". $where,
														 array($juego));
		$res = $res->row_array();
		return $res['total'];
	}
	
	function obtener_id_max() {
		$res = $this->db->query('select max(id) as idjuego from juegos')->row_array();
		return $res['idjuego'];
	}
	
	function obtener_sistemas() {
		return $this->db->query("select id, nombre from sistemas")->result_array();
	}
	
	function obtener_relacion_juego_usuario($juego, $usuario) {
		return $this->db->query("select * from tiene where usuario = ? and juego = ?", array($usuario, $juego));
	}
	
	function existe($id) {
		$res = $this->db->query("select * from juegos where id = ?", array($id));
		return $res->num_rows() == 1;
	}
	
	function buscar($criterio, $valor, $limite, $offset) {
		return $this->db->query("select * from juegos where lower($criterio) like lower(?) limit ? offset ?", 
														 array('%'.$valor.'%', $limite, $offset));
	}
	
	function listar($informacion, $usuario, $limite, $offset) {
		return $this->db->query("select * 
														 from juegos j join tiene t on j.id = t.juego 
														 where informacion = ? and usuario = ?
														 limit ? offset ?", 
														 array($informacion, $usuario, $limite, $offset));
	}
	
	function editar($id, $criterio, $valor) {
		$this->db->query("update juegos set $criterio = ? where id = ?", array($valor, $id));
	}
	
	function editar_sistema($juego, $sistema) {
		$this->db->query('update pertenece set sistema = ? where juego = ?', array($sistema, $juego));
	}
	
	function buscar_total_filas($criterio, $valor) {
		$res = $this->db->query("select count(*) as total_filas from juegos where lower($criterio) like lower(?)",
														 array('%'.$valor.'%'));
		$res = $res->row_array();
		return $res['total_filas'];
	}
	
	function buscar_total_filas_listado($informacion, $usuario) {
		$res = $this->db->query("select count(*) as total_filas 
														 from juegos j join tiene t on j.id = t.juego 
														 where informacion = ? and usuario = ?",
														 array($informacion, $usuario))->row_array();
		return $res['total_filas'];
	}
	
	function crear($nombre, $desarrolladora, $distribuidora, $genero, $descripcion, $fecha_lanz) {
		$this->db->query('insert into juegos (nombre, desarrolladora, distribuidora, genero, descripcion, caratula, fecha_lanz)
											values (?, ?, ?, ?, ?, ?, ?)', 
											array($nombre, $desarrolladora, $distribuidora, $genero, $descripcion, 'juegos/avatar.png', $fecha_lanz));
	}
	
	function insertar_relacion_juego_sistema($idjuego, $sistema) {
		$this->db->query('insert into pertenece (juego, sistema) values (?, ?)', array($idjuego, $sistema));
	}
	
	function insertar_relacion_juego_usuario($juego, $usuario, $estado) {
		$this->db->query("insert into tiene (usuario, juego, informacion)
											values (?, ?, ?)", array($usuario, $juego, $estado));
	}
		
	function actualizar_relacion_juego_usuario($juego, $usuario, $estado) {
		$this->db->query("update tiene set informacion = ? where usuario = ? and juego = ?",
											array($estado, $usuario, $juego));
	}
	
	function borrar_relacion_juego_usuario($juego, $usuario) {
		$this->db->query("delete from tiene where usuario = ? and juego = ?", array($usuario, $juego));
	}
	
	function actualizar_caratula($caratula, $juego) {
		$this->db->query("update juegos set caratula = ? where id = ?",
											array('juegos/'.$caratula, $juego));
	}
	
}
