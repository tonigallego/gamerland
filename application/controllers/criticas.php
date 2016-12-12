<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Criticas extends CI_Controller {

  function __construct() {
    CI_Controller::__construct();
    $this->load->model('Critica');
		$this->load->model('Juego');
		$this->load->model('Usuario');
		$this->load->model('Tablon');
  }

	public function index($juego = "", $usuario = "") {
		if ($usuario == "" && $this->session->userdata('id')) $usuario = $this->session->userdata('id');
		if (($juego != "" || $this->input->post('idjuego')) && $usuario != "") {
			if ($juego == "") $juego = $this->input->post('idjuego');
			$res = $this->Juego->obtener($juego);
			if ($res->num_rows() == 1) {
				if ($this->input->post('idjuego')) $juego = $this->input->post('idjuego');
				$data = $this->_recoger_datos_critica($usuario, $juego);
				if ($data != false) {
					$this->load->library('Utilidades');
					$data['contenido'] = $this->utilidades->transformar_texto($data['contenido']);
					$this->template->load('template', 'criticas/index', $data);
				} else {
					if ($usuario == $this->session->userdata('id')) {
						$this->session->set_flashdata('mensaje', 'Aún no has realizado la crítica de este juego');
						redirect('criticas/crear/' . $juego);
					} else {
						$this->session->set_flashdata('mensaje', 'El usuario indicado no ha realizado ninguna crítica a este juego');
						redirect('juegos/index/' . $juego);
					}
				}
			} else {
				$this->session->set_flashdata('mensaje', 'No existe el juego indicado');
				redirect('juegos/index');
			}
		} else {
			if (!$this->input->post('idjuego') && $juego == "") 
				$this->session->set_flashdata('mensaje', 'No se ha indicado el juego de la crítica');
			if (!$this->input->post('usuario') && $usuario == "") 
				$this->session->set_flashdata('mensaje', 'No se ha indicado el usuario de la crítica');
			redirect('juegos/index');
		}
	}
	
	public function crear($juego = "") {
		if ($this->session->flashdata('mensaje')) $data['mensaje'] = $this->session->flashdata('mensaje');
		if (!$this->input->post('cancelar')) {
			if ($this->session->userdata('usuario')) {
				$usuario = $this->session->userdata('id');
				if ($juego != "") {
					$res = $this->Juego->obtener($juego);
					if ($res->num_rows() == 1) {
						$res = $this->Juego->obtener_relacion_juego_usuario($juego, $this->session->userdata('id'));
						if ($res->num_rows() == 1) {
							$res = $res->row_array();
							if ($res['informacion'] == 'Te lo has pasado') {
								$res = $this->Critica->obtener($usuario, $juego);
								if ($res->num_rows() == 0) {
									$data['juego'] = $juego;
									$data['contenido'] = '';
									$data ['nota'] = '';
									$data['notas'] = $this->_recoger_notas();
									if (!$this->input->post('confirmar')) {
										$this->template->load('template', 'criticas/crear', $data);
									} elseif ($this->input->post('contenido') == "") {
										$data['mensaje'] = "La crítica no puede estar vacía";
										$data['nota'] = $this->input->post('nota');
										$this->template->load('template', 'criticas/crear', $data);
									} elseif (strlen($this->input->post('contenido')) > 30000) {
										$data['mensaje'] = "El contenido de la crítica no puede ser mayor a 30000 caracteres";
										$data['contenido'] = $this->input->post('contenido');
										$data['nota'] = $this->input->post('nota');
										$this->template->load('template', 'criticas/crear', $data);										
									} else {
										$contenido = $this->input->post('contenido');
										$nota = $this->input->post('nota');
										$this->Critica->crear($usuario, $juego, $contenido, $nota);
										if ($this->db->affected_rows() == 1) {
											$res = $this->Juego->obtener($juego)->row_array();
											$nombre_juego = $res['nombre'];
											$link_critica = anchor('criticas/index/'.$juego.'/'.$usuario, "crítica a $nombre_juego");
											$comentario = "Actualización: he realizado una $link_critica";
											$this->load->library('Utilidades');
											$this->utilidades->_escribir_actualizacion($comentario, $usuario);
											redirect('criticas/index/'.$juego."/".$usuario);		
										} else {											
											$this->session->set_flashdata('mensaje', 'No se ha podido crear la crítica');
											redirect('juegos/index/'.$juego);		
										}						
									}
								} else {
									redirect('criticas/index/'.$juego."/".$usuario);
								}
							} else {
								$this->session->set_flashdata('mensaje', 'Debes haberte pasado el juego para poder realizar la crítica');
								redirect('juegos/index/' . $juego);
							}
						} else {
							$this->session->set_flashdata('mensaje', 'Debes tener el juego y terminarlo para poder realizar la crítica');
							redirect('juegos/index/' . $juego);	
						}
					} else {
						$this->session->set_flashdata('mensaje', 'El juego indicado no existe');
						redirect('juegos/index');		
					}
				} else {
					$this->session->set_flashdata('mensaje', 'No has indicado el juego para el que quieres realizar la crítica');
					redirect('juegos/index');		
				}
			} else {
				$this->session->set_flashdata('mensaje', 'Para poder realizar una crítica debes estar logueado');
				redirect('usuarios/login');		
			}
		} else {
			redirect('juegos/index/' . $juego);
		}
	}
	
	public function puntuar() {
		if ($this->session->userdata('usuario')) {
			if ($this->input->post('confirmar') && $this->input->post('critica') && $this->input->post('usuario')
					&& $this->input->post('juego')) {
				$valoracion = $this->input->post('confirmar');
				$usuario = $this->input->post('usuario');
				$juego = $this->input->post('juego');
				$critica = $this->input->post('critica');
				$res = $this->Critica->obtener_valoracion($this->session->userdata('id'), $critica);
				if ($valoracion == 'Dar punto positivo' || $valoracion == 'Dar punto negativo') {
					if ($valoracion == 'Dar punto positivo') {
						$valor = 1;
					} else {
						$valor = -1;
					}
					if ($res->num_rows() > 0) {
						$this->Critica->actualizar_valoracion($critica, $this->session->userdata('id'), $valor);
					} else {
						$this->Critica->crear_valoracion($critica, $this->session->userdata('id'), $valor);
					}		
				} elseif ($valoracion == 'Retirar punto') {
					if ($res->num_rows() > 0) {
						$this->Critica->eliminar_valoracion($critica, $this->session->userdata('id'));
					}
				}						
			}
			if (isset($juego) && isset($usuario)) {
				$data = $this->_recoger_datos_critica($usuario, $juego);
				if ($data != false) {					
					$this->load->view('criticas/index', $data);
				} else {
					if ($usuario == $this->session->userdata('id')) {
						$this->session->set_flashdata('mensaje', 'Aún no has realizado la crítica de este juego');
						redirect('criticas/crear/' . $juego);
					} else {
						$this->session->set_flashdata('mensaje', 'El usuario indicado no ha realizado ninguna crítica a este juego');
						redirect('juegos/index/' . $juego);
					}
				}
			} else {
				redirect('criticas/index');
			}
		} else {
			$this->session->set_flashdata('mensaje', 'Debes estar logueado primero');
			redirect('usuarios/login');
		}
	}
	
	public function editar($juego) {
		if ($juego != "") {
			if ($this->session->userdata('id')) {
				$usuario = $this->session->userdata('id');
				if (!$this->input->post('cancelar')) {					
					$res = $this->Critica->obtener($usuario, $juego);
					if ($res->num_rows() == 1) {
						if ($this->input->post('confirmar')) {
							$contenido = $this->input->post('contenido');
							$nota = $this->input->post('nota');
							$critica = $this->input->post('critica');
							if ($contenido == '') {
								$data = $this->Critica->obtener($usuario, $juego)->row_array();
								$data['nota'] = $nota;
								$data['notas'] = $this->_recoger_notas();
								$data['mensaje'] = 'El cuerpo de la crítica no puede estar vacío';
								$this->template->load('template', 'criticas/editar', $data);
							} elseif (strlen($contenido) > 30000) {
								$data = $this->Critica->obtener($usuario, $juego)->row_array();
								$data['nota'] = $nota;
								$data['notas'] = $this->_recoger_notas();
								$data['mensaje'] = 'El cuerpo de la crítica no puede tener más de 30000 caracteres';
								$this->template->load('template', 'criticas/editar', $data);
							} else {
								$this->Critica->actualizar($nota, $contenido, $critica);
								if ($this->db->affected_rows() != 1) 
									$this->session->set_flashdata('mensaje', 'La modificación no ha sido posible');
								redirect('criticas/index/'.$juego.'/'.$usuario);
							}						
						} else {
							$res = $this->Critica->obtener($usuario, $juego);
							$data = $res->row_array();
							$data['notas'] = $this->_recoger_notas();
							$this->template->load('template', 'criticas/editar', $data);
						}					
					} else {
						$this->session->set_flashdata('mensaje', 'Aún no has creado la crítica');
						redirect('criticas/crear/'.$juego);
					}	
				} else {
					redirect('criticas/index/'.$juego.'/'.$usuario);
				}		
			} else {
				$this->session->set_flashdata('mensaje', 'Primero debes estar logueado');
				redirect('usuarios/login');
			}
		} else {
			redirect('juegos/index');
		}
	}
	
	function listar($tipo, $id, $pagina = 0) {
		$limite = 10;
		if ($pagina == 0) {
			$pagina = 1;
			$comienzo_listado = true;
		}		
		$offset = ($limite * $pagina) - $limite;
		$data = array('pagina' => $pagina, 'limite' => $limite, 'tipo' => $tipo, 'id_lista' => $id);
		if ($tipo == 'usuario') {		
			$total_filas = $this->Critica->obtener_total_por_usuario($id);
			$data['total_paginas'] = $total_filas / $limite;
			$data['total_filas'] = $total_filas;
			$res = $this->Critica->obtener_por_usuario($id, $limite, $offset);
			if ($res->num_rows() > 0) {
				$data = array_merge($data, $this->_lista_por_usuario($res));
				if (isset($comienzo_listado) && $comienzo_listado)
					$this->template->load('template', 'criticas/listar', $data);
				else
					$this->load->view('criticas/listar', $data);
			} else {
				$this->session->set_flashdata('mensaje', 'El usuario indicado no tiene ninguna crítica');
				redirect('usuarios/index/'.$id);
			}
		} elseif ($tipo == 'juego') {		
			$total_filas = $this->Critica->obtener_total_por_juego($id);
			$data['total_paginas'] = $total_filas / $limite;
			$data['total_filas'] = $total_filas;
			$res = $this->Critica->obtener_por_juego($id, $limite, $offset);
			if ($res->num_rows() > 0) {
				$data = array_merge($data, $this->_lista_por_juego($res));			
				if (isset($comienzo_listado) && $comienzo_listado)
					$this->template->load('template', 'criticas/listar', $data);
				else
					$this->load->view('criticas/listar', $data);
			} else {
				$this->session->set_flashdata('mensaje', 'El juego indicado no tiene ninguna crítica');
				redirect('juegos/index/'.$id);
			}
		} else {
			$this->session->set_flashdata('mensaje', 'El tipo indicado es erróneo');
			redirect('portadas/index');
		}
	}
	
	private function _recoger_datos_critica($usuario, $juego) {
		$res = $this->Critica->obtener_datos($usuario, $juego);
		if ($res->num_rows() > 0) {
			$data = $res->row_array();
			$data['usuario'] = $usuario;
			$res = $this->Usuario->obtener_datos($usuario)->row_array();
			$data['avatar'] = $res['avatar'];
			$data['juego'] = $juego;
			$res = $this->Juego->obtener($juego)->row_array();
			$data['caratula'] = $res['caratula'];
			$data['karma'] = $this->Usuario->calcular_karma($usuario);
			$data['media'] = $this->Critica->nota_media_juego($juego);
			$data['positivos'] = $this->Critica->media_votos_positivos($data['id']);
			$data['votos'] = $this->Critica->contar_votos($data['id']);
			if ($usuario != $this->session->userdata('id')) {
				$res = $this->Critica->obtener_valoracion($this->session->userdata('id'), $data['id']);
				if ($res->num_rows() > 0) {
					$res = $res->row_array();
					$data['valoracion'] = $res['valor'];
				}
			}
			return $data;
		} else {
			return false;
		}
	}
	
	function _lista_por_usuario($res) {
		$data['criticas'] = $res->result_array();
		$endfor = count($data['criticas']) - 1;
		for ($i = 0; $i <= $endfor; $i++) {
			$critica = $data['criticas'][$i];
			$juego = $this->Juego->obtener($critica['juego'])->row_array();
			$data['criticas'][$i]['titulo'] = "Crítica de ".anchor("juegos/index/{$critica['juego']}", $juego['nombre']);
			$data['criticas'][$i]['caratula'] = $juego['caratula'];
			$data['criticas'][$i]['votos'] = $this->Critica->contar_votos($critica['id']);
			$data['criticas'][$i]['votos_positivos'] = $this->Critica->contar_votos($critica['id'], 'positivos');
			$usuario = $this->Usuario->obtener_datos($critica['usuario'])->row_array();
			$data['criticas'][$i]['avatar']	= $usuario['avatar'];
			$data['cabecera'] = "Criticas realizadas por ".anchor("usuarios/index/".$usuario['id'],$usuario['nombre']);			
		}
		return $data;
	}
	
	function _lista_por_juego($res) {
		$data['criticas'] = $res->result_array();
		$endfor = count($data['criticas']) - 1;
		for ($i = 0; $i <= $endfor; $i++) {
			$critica = $data['criticas'][$i];
			$usuario = $this->Usuario->obtener_datos($critica['usuario'])->row_array();
			$data['criticas'][$i]['titulo'] = "Crítica por ".anchor("usuarios/index/{$critica['usuario']}", $usuario['nombre']);
			$data['criticas'][$i]['avatar'] = $usuario['avatar'];
			$data['criticas'][$i]['votos'] = $this->Critica->contar_votos($critica['id']);
			$data['criticas'][$i]['votos_positivos'] = $this->Critica->contar_votos($critica['id'], 'positivos');
			$juego = $this->Juego->obtener($critica['juego'])->row_array();
			$data['criticas'][$i]['caratula']	= $juego['caratula'];
			$data['cabecera'] = "Criticas realizadas a ".anchor("juegos/index/".$juego['id'],$juego['nombre']);
		}
		return $data;
	}
	
	function _recoger_notas() {
		$notas = array();
		for ($i=1; $i<=10; $i++) {
			$notas[$i] = $i;
		}
		return $notas;
	}
	
}
