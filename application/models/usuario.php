<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usuario extends CI_Model {
	
	function obtener($nombre, $password) {
		return $this->db->query("select * from usuarios where nombre = ? and password = md5(?)", 
											       array($nombre, $password));
	}
	
	function obtener_datos($id) {
		return $this->db->query("select * from usuarios where id = ?", array($id));
	}
	
	function obtener_por_valoraciones($id, $limite, $offset) {
		return $this->db->query('select * 
														 from usuarios u join valoraciones v on u.id = v.usuario 
														 where v.critica = ? 
														 order by valor
														 limit ? offset ?',
														 array($id, $limite, $offset));
	}
	
	function obtener_total_por_valoraciones($id) {
		$res = $this->db->query('select count(*) as total_filas 
														 from usuarios u join valoraciones v on u.id = v.usuario 
														 where v.critica = ?',
														 array($id))->row_array();
		return $res['total_filas'];
	}
	
	function obtener_por_juego($juego, $limite, $offset) {
		return $this->db->query('select * 
														 from usuarios u join tiene t on u.id = t.usuario 
														 where t.juego = ?
														 limit ? offset ?',
														 array($juego, $limite, $offset));
	}
	
	function obtener_total_por_juego($juego) {
		$res = $this->db->query('select count(*) as total_filas
														 from usuarios u join tiene t on u.id = t.usuario 
														 where t.juego = ?',
														 array($juego))->row_array();
		return $res['total_filas'];
	}
	
	function obtener_top() {
		return $this->db->query('select * from karma natural join usuarios where karma > 0 order by karma limit 5')->result_array();
	}
	
	function existe($id) {
		$res = $this->db->query("select * from usuarios where id = ?", array($id));
		return $res->num_rows() == 1;
	}
	
	function obtener_nombre($id) {
		$res = $this->db->query("select nombre from usuarios where id = ?", array($id))->row_array();
		return $res['nombre'];
	}
	
	function buscar($valor, $limite, $offset) {
		return $this->db->query("select * from usuarios where lower(nombre) like lower(?) limit ? offset ?", 
														 array('%'.$valor.'%', $limite, $offset));
	}
	
	function buscar_total_filas($valor) {
		$res = $this->db->query("select count(*) as total_filas from usuarios where lower(nombre) like lower(?)",
														 array('%'.$valor.'%'));
		$res = $res->row_array();
		return $res['total_filas'];
	}
	
	function crear($nombre, $password, $email) {
		$this->db->query("insert into usuarios (nombre, password, email, avatar) values (?, md5(?), ?, ?)",
											array($nombre, $password, $email, 'usuarios/avatar.png'));
	}
	
	function contar_juegos($id, $estado = "") {
		$where = "";
		if ($estado != "") {
			$where = " and informacion like ?";
		}
		return $this->db->query("select count(*) as total_juegos
														 from tiene 
														 where usuario = ?" . $where,
														 array($id, $estado))->row_array();
	}
	
	function calcular_karma($id) {
		$res = $this->db->query("select karma from karma where id = ?", array($id))->row_array();
		return (isset($res['karma'])) ? $res['karma'] : 0;
	}
	
	function total_votos($id, $tipo = "") {
		$where = "valor = '".$tipo."' and ";
		$res = $this->db->query('select count(cast(valor as integer)) as votos
														 from valoraciones 
														 where ' . $where . '
																	 critica in (select id 
																							 from criticas 
																							 where usuario = ?)',
														 array($id));
		$res = $res->row_array();
		return $res['votos'];
	}
	
	function hay_seguimiento($seguidor, $seguido) {
		$res = $this->db->query('select * from seguimientos where id_seguidor = ? and id_seguido = ?',
														 array($seguidor, $seguido));
		return $res->num_rows() > 0;
	}
	
	function obtener_seguidores($id, $limit = 0, $offset = 0) {
		$limit = '';
		if ($limit > 0)	$limit = " limit ? offset ?";
		return $this->db->query("select * 
														 from usuarios u join seguimientos s on u.id = s.id_seguidor 
														 where s.id_seguido = ?
														 order by fecha desc $limit",
														 array($id, $limit, $offset));
	}
	
	function obtener_total_seguidores($id) {
		$res = $this->db->query("select count(*) as total_filas
														 from usuarios u join seguimientos s on u.id = s.id_seguidor 
														 where s.id_seguido = ?",
														 array($id))->row_array();
		return $res['total_filas'];
	}
	
	function obtener_seguidos($id, $limit = 0, $offset = 0) {
		$limit = '';
		if ($limit > 0)	$limit = " limit ? offset ?";
		return $this->db->query("select * 
														 from usuarios u join seguimientos s on u.id = s.id_seguido 
														 where s.id_seguidor = ?
														 order by fecha desc $limit",
														 array($id, $limit, $offset));
	}
	
	function obtener_total_seguidos($id) {
		$res = $this->db->query("select count(*) as total_filas
														 from usuarios u join seguimientos s on u.id = s.id_seguido 
														 where s.id_seguidor = ?",
														 array($id))->row_array();
		return $res['total_filas'];
	}
	
	function es_seguidor($seguidor, $seguido) {
		$res = $this->db->query('select * from seguimientos where id_seguidor = ? and id_seguido = ?',
														 array($seguidor, $seguido));
		return $res->num_rows > 0;
	}
	
	function crear_seguimiento($seguidor, $seguido) {
		$this->db->query('insert into seguimientos (id_seguidor, id_seguido) values (?, ?)',
											array($seguidor, $seguido));
	}
	
	function borrar_seguimiento($seguidor, $seguido) {
		$this->db->query('delete from seguimientos where id_seguidor = ? and id_seguido = ?',
											array($seguidor, $seguido));
	}
	
	function actualizar_avatar($avatar, $usuario) {
		$this->db->query("update usuarios set avatar = ? where id = ?",
											array($avatar, $usuario));
	}
	
	function modificar_password($password, $usuario) {
		$this->db->query('update usuarios set password = md5(?) where id = ?',
											array($password, $usuario));
	}
	
}
