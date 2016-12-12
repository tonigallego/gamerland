<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Portadas extends CI_Controller {
	
	function __construct() {		
    CI_Controller::__construct();
    $this->load->model('Juego');
    $this->load->model('Usuario');
    $this->load->model('Critica');
    $this->load->model('Sistema');
    $this->load->model('Portada');
  }
	
	public function index() {
		$data = array();
		if ($this->session->flashdata('mensaje')) $data['mensaje'] = $this->session->flashdata('mensaje');
		$data['juegos_nuevos'] = $this->Juego->obtener_nuevos();
		$data['criticas_nuevas'] = $this->Critica->obtener_nuevos();
		$data['usuarios_top'] = $this->Usuario->obtener_top();
		$data['juegos_top'] = $this->Juego->obtener_top();
		$data['criticas_top'] = $this->Critica->obtener_top();
		if ($this->session->userdata('id')) {
			$usuario = $this->session->userdata('id');
			$data['recomendacion_juegos'] = $this->Portada->recomendar_juegos($usuario);
			$data['recomendacion_usuarios'] = $this->Portada->recomendar_usuarios($usuario);
		}
		$this->template->load('template', 'portadas/index', $data);		
	}
	
}
