<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tablones extends CI_Controller {
	
	function __construct() {
    CI_Controller::__construct();
		$this->load->model('Usuario');
		$this->load->model('Tablon');
  }
  
  function index($id, $pagina = 1) {
  	$this->load->library('Utilidades');
  	$data = $this->utilidades->recoger_datos_tablon($id, $pagina);
  	if ($this->session->userdata('id'))
  		$data['es_seguidor'] = $this->Usuario->es_seguidor($id, $this->session->userdata('id'));
		if ($this->session->userdata('error_comentario'))
			$data['error_comentario'] = $this->session->userdata('error_comentario');
		$data['id'] = $id;		
  	$this->load->view('tablones/index', $data);
  	$this->session->unset_userdata('error_comentario');
  }
	
	function escribir($receptor) {
		if ($this->session->userdata('id')) {
			$emisor = $this->session->userdata('id');
			if ($this->Usuario->hay_seguimiento($receptor, $emisor)	|| $receptor == $emisor) {
				if ($this->input->post('contenido')) {
					$contenido = $this->input->post('contenido');
					if (trim($contenido) != "") {
						if (strlen($contenido) <= 3000) {
							$this->Tablon->escribir($contenido, $emisor, $receptor);
							if (!$this->db->affected_rows() == 1) 
								$this->session->set_userdata('error_comentario', 'Ha habido un error a la hora de añadir el mensaje');
						} else {
							$this->session->set_userdata('error_comentario', 'El comentario no puede tener más de 3000 caracteres');
						}
					} else {
						$this->session->set_userdata('error_comentario', 'El comentario no puede estar vacío');
					}
				} else {
					$this->session->set_userdata('error_comentario', 'No se ha enviado ningún contenido para el comentario');
				}				
			} else {
				$this->session->set_userdata('error_comentario', 'No puedes comentar a un usuario que no te sigue');
			}
			$this->index($receptor);
		} else {
			$this->session->set_flashdata('mensaje', 'Debes iniciar sesión primero');
			redirect('usuarios/login');
		}
	}
	
	function borrar() {
		if ($this->session->userdata('id')) {
			if ($this->input->post('comentario') && $this->input->post('emisor') && $this->input->post('receptor')) {
				$comentario = $this->input->post('comentario');
				$emisor = $this->input->post('emisor');
				$receptor = $this->input->post('receptor');
				if ($this->session->userdata('id') == $emisor || $this->session->userdata('id') == $receptor) {
					if ($this->Tablon->existe_comentario($emisor, $receptor, $comentario)) {
						$this->Tablon->borrar_comentario($comentario);
						if (!$this->db->affected_rows() == 1)
							$this->session->set_userdata('error_comentario', 'Ha habido un error al borrar el comentario');
						$this->index($receptor);
					} else {
						$this->session->set_userdata('error_comentario', 'El comentario especificado no existe');
						$this->index($receptor);
					}
				} else {
					$this->session->set_userdata('error_comentario', 'No tienes permiso para borrar ese comentario');
					$this->index($receptor);
				}
			} else {
				$this->session->set_userdata('error_comentario', 'No se ha indicado el comentario a borrar');
				$this->index($receptor);
			}			
		} else {
			$this->session->set_flashdata('mensaje', 'Debes iniciar sesión primero');
			redirect('usuarios/login');
		}
	}
	
}
