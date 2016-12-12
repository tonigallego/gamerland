<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tablon extends CI_Model {

	function obtener_total_comentarios($usuario) {
		$res = $this->db->query("select count(*) as total_filas
    											 from comentarios c join usuarios u on c.emisor = u.id 
    											 where c.receptor = ?", 
    											 array($usuario))->row_array();  
  	return $res['total_filas'];
	}
	
	function obtener_comentarios($usuario, $limite, $offset) {
		$res = $this->db->query("select u.nombre as nombre_emisor, 
    															to_char(c.fecha, 'DD-MM-YYYY' || ' a las ' || 'HH24:MI:SS') as fecha, 
    															c.contenido,
    															c.especial,
    															c.emisor,
    															c.receptor,
    															c.id as id_comentario
    											 from comentarios c join usuarios u on c.emisor = u.id 
    											 where c.receptor = ?
    											 order by c.id desc
    											 limit ? offset ?", 
    											 array($usuario, $limite, $offset));    											 
		return $res->result_array();
	}
	
	function existe_comentario($emisor, $receptor, $comentario) {
		$res = $this->db->query('select * from comentarios where emisor = ? and receptor = ? and id = ?',
														 array($emisor, $receptor, $comentario));
		return $res->num_rows() > 0;
	}
	
	function escribir($contenido, $emisor, $receptor, $especial = 'false') {
		$this->db->query('insert into comentarios (contenido, emisor, receptor, especial) values (?, ?, ?, ?)',
											array($contenido, $emisor, $receptor, $especial));
	}
	
	function borrar_comentario($comentario) {
		$this->db->query('delete from comentarios where id = ?', array($comentario));
	}
	
}
