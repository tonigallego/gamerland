<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Juegos extends CI_Controller {

  function __construct() {		
    CI_Controller::__construct();
    $this->load->model('Juego');
    $this->load->model('Usuario');
    $this->load->model('Critica');
    $this->load->model('Tablon');
  }

	public function index($id = null, $cambio = false)	{
		$data = array();	
		if ($this->session->flashdata('mensaje')) $data['mensaje'] = $this->session->flashdata('mensaje');
		if ($id != null) {
			$res = $this->Juego->obtener_datos($id);			
			if ($res->num_rows() == 1) {			
				$data = array_merge($data, $res->row_array());
				$this->load->library('Utilidades');
				$data['descripcion'] = $this->utilidades->transformar_texto($data['descripcion']);
				if ($this->session->userdata('id')) {
					$usuario = $this->session->userdata('id');
					$data = array_merge($data, $this->_preparar_estado($id, $usuario));
					$data['usuario'] = $usuario;
					$data = array_merge($data, $this->_preparar_estado($id, $usuario));
				}
				$data['total_criticas'] = $this->Critica->contar_por_juego($id);
				$data['nota_media'] = $this->Juego->obtener_nota_media($id);
				$data['pasados'] = $this->Juego->contar($id, 'pasados');
				$data['pendientes'] = $this->Juego->contar($id, 'pendientes');
				$data['deseados'] = $this->Juego->contar($id, 'deseados');
				$data['juegos_similares'] = $this->Juego->obtener_similares($id);
				if ($cambio) $this->load->view('juegos/index', $data);
				else $this->template->load('template', 'juegos/index', $data);
			} else {
				$this->session->set_flashdata('mensaje', "El juego solicitado no existe");
				redirect('juegos/buscar');
			}
		} else {					
			redirect('juegos/buscar');
		}
	}
	
	public function crear() {
		if ($this->session->userdata('usuario')) {
			$data = array('nombre' => '', 'desarrolladora' => '', 'distribuidora' => '', 
										'dia' => '', 'mes' => '', 'anio' => '',
										'genero' => '', 'descripcion' => '', 'sistema' => '');
			$data['generos'] = $this->_cargar_generos();
			$data['sistemas'] = $this->_cargar_sistemas();
			if ($this->input->post('confirmar')) {
				$nombre = $this->input->post('nombre');
				$desarrolladora = $this->input->post('desarrolladora');
				$distribuidora = $this->input->post('distribuidora');
				$dia = $this->input->post('dia');
				$mes = $this->input->post('mes');
				$anio = $this->input->post('anio');
				$genero = $this->input->post('genero');
				$sistema = $this->input->post('sistema');
				$descripcion = $this->input->post('descripcion');
				
				$errores = array();
				if ($nombre == '' || $desarrolladora == '' || $distribuidora == '' || 						
						$genero == '' || $sistema == '' || $descripcion == '' || 
						$dia == '' || $mes == '' || $anio == '')	
					$errores[] = 'Deben rellenarse todos los campos';
				if (strlen($nombre) > 30) $errores[] = 'El nombre no puede tener más de 30 caracteres';
				if (strlen($desarrolladora) > 30) $errores[] = 'La desarrolladora no puede tener más de 30 caracteres';
				if (strlen($distribuidora) > 30) $errores[] = 'La distribuidora no puede tener más de 30 caracteres';
				if (strlen($genero) > 30) $errores[] = 'El género no puede tener más de 30 caracteres';
				if (strlen($descripcion) > 500) $errores[] = 'La descripción no puede tener más de 500 caracteres';
				if (strlen($mes) > 0 && strlen($dia) > 0 && strlen($anio) > 0 && !checkdate($mes, $dia, $anio)) 
					$errores[] = 'La fecha introducida no es una fecha válida';				
				if (sizeof($errores) > 0) {					
					$data['nombre'] = $nombre;
					$data['desarrolladora'] = $desarrolladora;
					$data['distribuidora'] = $distribuidora;
					$data['dia'] = $dia;
					$data['mes'] = $mes;
					$data['anio'] = $anio;
					$data['genero'] = $genero;
					$data['sistema'] = $sistema;
					$data['descripcion'] = $descripcion;
					$data['mensaje'] = $errores;
					$this->template->load('template', 'juegos/crear', $data);					
				} else {				
					$fecha_lanz = $dia . "/". $mes . "/" . $anio;
					$this->Juego->crear($nombre, $desarrolladora, $distribuidora, $genero, $descripcion, $fecha_lanz);					
					if ($this->db->affected_rows() == 1) {
					  $idjuego = $this->Juego->obtener_id_max();
					  $this->Juego->insertar_relacion_juego_sistema($idjuego, $sistema);
					  if ($this->db->affected_rows() == 1) {
							redirect('juegos/index/' . $idjuego);
						}
					}
				}
			} elseif ($this->input->post('cancelar')) {
				redirect('juegos/index');
			} else {
				$this->template->load('template', 'juegos/crear', $data);
			} 
		} else {
			$this->session->set_flashdata('mensaje', 'Debes estar logueado primero');
			redirect('usuarios/login');
		}
	}
	
	public function cambiar_caratula($id) {
		if ($this->session->userdata('id')) {
			if ($id != null) {
				$res = $this->Juego->obtener($id);
				if ($res->num_rows() == 1) {
					if ($this->input->post('cambio_caratula')) {				
						$config['upload_path'] = '/home/toni/web/juegos/imagenes/';
						$config['allowed_types'] = 'gif|jpg|png';
						$config['max_size']	= '400';
						$config['max_width'] = '750';
						$config['max_height'] = '1000';
						$this->load->library('upload', $config);
						if (!$this->upload->do_upload()) {
							$this->session->set_flashdata('mensaje', 'Error al subir imagen, compruebe si el tamaño y el tipo son correctos');
						} else {
							$caratula = $this->upload->data();
							$this->Juego->actualizar_caratula($caratula['file_name'], $id);
							if ($this->db->affected_rows() > 0) {
								$this->session->set_flashdata('mensaje', 'La imagen se ha modificado correctamente');
							} else {
								$this->session->set_flashdata('mensaje', 'Ha habido un error a la hora de cambiar la imagen');
							}
						}
						redirect('juegos/index/'.$id);
					} else {
						redirect('juegos/index/'.$id.'/true');
					}
				} else {
					$this->session->set_flashdata('mensaje', 'El juego especificado no se encuentra en la base de datos');
					redirect('juegos/buscar');
				}
			} else {
				$this->session->set_flashdata('mensaje', 'El juego especificado no se encuentra en la base de datos');
				redirect('juegos/buscar');
			}
		} else {
			$this->session->set_flashdata('mensaje', 'Debes estar logueado primero');
			redirect('usuarios/index');
		}
	}
	
	public function cambiar_estado() {
		$juego = $this->input->post("idjuego");
		if ($this->session->userdata('usuario') && $this->input->post('informacion')) {
			$usuario = $this->session->userdata('id');
			$informacion = $this->input->post('informacion');
			if ($informacion == 'Lo quiero') {
				$this->Juego->insertar_relacion_juego_usuario($juego, $usuario, 'Lo quieres');
			} elseif ($informacion == 'Lo tengo') {
				$res = $this->Juego->obtener_relacion_juego_usuario($juego, $usuario);
				if ($res->num_rows() == 0) {
					$this->Juego->insertar_relacion_juego_usuario($juego, $usuario, 'Lo tienes pendiente');
				} else {
					$this->Juego->actualizar_relacion_juego_usuario($juego, $usuario, 'Lo tienes pendiente');
				}
			} elseif ($informacion == 'Me lo he pasado') {
				$this->Juego->actualizar_relacion_juego_usuario($juego, $usuario, 'Te lo has pasado');
				$res = $this->Juego->obtener($juego)->row_array();
				$nombre_juego = $res['nombre'];
				$link_juego = anchor("juegos/index/$juego", $nombre_juego);
				$comentario = "Actualización: me he pasado " . $link_juego;
				$this->load->library('Utilidades');
				$this->utilidades->_escribir_actualizacion($comentario, $usuario);
			} elseif ($informacion == 'Ya no lo quiero' || $informacion == 'Ya no lo tengo') {
				$this->Juego->borrar_relacion_juego_usuario($juego, $usuario);
			} else {
				$this->session->set_flashdata['mensaje'] = 'Error al cambiar el estado del juego';
			}
			$this->index($juego, true);
		} else {
			redirect('juegos/index/'.$juego);
		}
	}
	
	public function editar($juego) {
		if ($this->session->userdata('id')) {
			$res = $this->Juego->obtener_datos($juego);
			if ($res->num_rows() == 1) {
				if (!$this->input->post('volver')) {
					$data = $res->row_array();
					$data['generos'] = $this->_cargar_generos();
					$data['sistemas'] = $this->_cargar_sistemas();
					$mensaje = 'El campo que desees cambiar no puede estar vacío';
					if ($this->input->post('nombre')) {
						$nombre = trim($this->input->post('nombre'));
						if ($nombre != '') {
							if (strlen($nombre) <= 30) {
								$this->Juego->editar($juego, 'nombre', $nombre);
								if ($this->db->affected_rows() == 0) $data['mensaje'] = 'Ha habido un error a la hora de editar el nombre';
							} else {
								$data['mensaje'] = 'El nombre no puede tener más de 30 caracteres';
							}
						} else {
							$data['mensaje'] = $mensaje;
						}
					} elseif ($this->input->post('desarrolladora')) {
						$desarrolladora = trim($this->input->post('desarrolladora'));
						if ($desarrolladora != '') {
							if (strlen($desarrolladora) <= 30) {
								$this->Juego->editar($juego, 'desarrolladora', $desarrolladora);
								if ($this->db->affected_rows() == 0) 
									$data['mensaje'] = 'Ha habido un error a la hora de editar la desarrolladora';
							} else {
								$data['mensaje'] = 'La desarrolladora no puede tener más de 30 caracteres';
							}
						} else {
							$data['mensaje'] = $mensaje;
						}
					} elseif ($this->input->post('distribuidora')) {
						$distribuidora = trim($this->input->post('distribuidora'));
						if ($distribuidora != '') {
							if (strlen($distribuidora) <= 30) {
								$this->Juego->editar($juego, 'distribuidora', $distribuidora);
								if ($this->db->affected_rows() == 0) 
									$data['mensaje'] = 'Ha habido un error a la hora de editar la distribuidora';
							} else {
								$data['mensaje'] = 'La distribuidora no puede tener más de 30 caracteres';
							}
						} else {
							$data['mensaje'] = $mensaje;
						}
					} elseif ($this->input->post('genero')) {
						$genero = $this->input->post('genero');
						if ($genero != '') {
							if (strlen($genero) <= 30) {
								$this->Juego->editar($juego, 'genero', $genero);
								if ($this->db->affected_rows() == 0) $data['mensaje'] = 'Ha habido un error a la hora de editar el género';
							} else {
								$data['mensaje'] = 'El genero no puede tener más de 30 caracteres';
							}
						} else {
							$data['mensaje'] = $mensaje;
						}
					} elseif ($this->input->post('descripcion')){
						$descripcion = $this->input->post('descripcion');
						if ($descripcion != '') {
							if (strlen($descripcion) <= 500) {
								$this->Juego->editar($juego, 'descripcion', $descripcion);
								if ($this->db->affected_rows() == 0) $data['mensaje'] = 'Ha habido un error a la hora de editar la descripción';
							} else {
								$data['mensaje'] = 'La descripción no puede tener más de 500 caracteres';
							}
						} else {
							$data['mensaje'] = $mensaje;
						}		
					} elseif ($this->input->post('dia') && $this->input->post('dia') && $this->input->post('dia')){
						$dia = $this->input->post('dia');
						$mes = $this->input->post('mes');
						$anio = $this->input->post('anio');
						if ($dia != '' && $mes != '' && $anio != '') {
							if (checkdate($mes, $dia, $anio)) {
								$fecha_lanz = $dia . "/". $mes . "/" . $anio;
								$this->Juego->editar($juego, 'fecha_lanz', $fecha_lanz);
								if ($this->db->affected_rows() == 0) $data['mensaje'] = 'Ha habido un error a la hora de editar la fecha';
							} else {
								$data['mensaje'] = 'La fecha introducia no es una fecha válida';
							}
						} else {
							$data['mensaje'] = $mensaje;
						}
					} elseif ($this->input->post('sistema')) {
						$sistema = $this->input->post('sistema');
						if ($sistema != '') {
							$this->Juego->editar_sistema($juego, $sistema);
							if ($this->db->affected_rows() == 0) $data['mensaje'] = 'Ha habido un error a la hora de editar el sistema';
						} else {
							$data['mensaje'] = $mensaje;
						}
					} else {
						$primeravez = true;
						$this->template->load('template', "juegos/editar", $data);
					}
					if (!isset($primeravez)) {
						$data = array_merge($data, $this->Juego->obtener_datos($juego)->row_array());
						$this->load->view("juegos/editar", $data);
					}
				} else {
					redirect("juegos/index/$juego");
				}
			} else {
				$this->session->set_flashdata('mensaje', 'No existe el juego solicitado');
				redirect('juegos/buscar');
			}
		} else {
			$this->session->set_flashdata('mensaje', 'Primero debes iniciar sesión');
			redirect('usuarios/login');
		}
	}
	
	public function buscar($criterio = "", $valor = "", $pagina = 0) {
		$criterios = array('nombre' => 'Nombre', 
											 'distribuidora' => 'Distribuidora', 
											 'desarrolladora' => 'Desarrolladora',
											 'genero' => 'Género');		
		$data = array('criterios' => $criterios, 'criterio' => '', 'valor' => '');
		if ($this->session->flashdata('mensaje')) $data['mensaje'] = $this->session->flashdata('mensaje');
		if ($pagina > 0) {
			if (trim($valor) != '' && trim($criterio) != '' && trim($valor) != '0' && trim($criterio) != '0') {
				$total_filas = $this->Juego->buscar_total_filas($criterio, $valor);
				if ($total_filas > 0) {
					$limite = 10;
					if ($pagina == 0) $pagina = 1;
					$offset = ($limite * $pagina) - $limite;
					$data['total_paginas'] = $total_filas / $limite;
					$data['total_filas'] = $total_filas;					
					$data['pagina'] = $pagina;
					$data['limite'] = $limite;					
					$data['juegos'] = $this->Juego->buscar($criterio, $valor, $limite, $offset)->result_array();
					$data['criterio'] = $criterio;
					$data['valor'] = $valor;					
					$this->load->view('juegos/resultado_busqueda', $data);
				} else {
					$data['resultado'] = 'No se han encontrado juegos con el criterio introducido';
					$this->load->view('juegos/resultado_busqueda', $data);	
				}
			} else {
				$data['resultado'] = 'El criterio de búsqueda no puede estar vacío';
				$this->load->view('juegos/resultado_busqueda', $data);
			}
		} else {
			$this->template->load('template', 'juegos/buscar', $data);
		}
	}
	
	function listar($usuario, $informacion, $pagina = 0) {
		if ($this->Usuario->existe($usuario)) {
			if (trim($informacion) != "") {				
				if ($informacion == 'pasados'):	$informacion_sql = 'Te lo has pasado';
				elseif ($informacion == 'pendientes'): $informacion_sql = 'Lo tienes pendiente';
				elseif ($informacion == 'deseados'): $informacion_sql = 'Lo quieres';
				else:					
					$this->session->set_flashdata('mensaje', 'No se ha especificado un criterio válido');
					$informacion = "";
					redirect('usuarios/index/'.$usuario);	
				endif;				
				if ($informacion != "") {
					$limite = 10;					
					$total_filas = $this->Juego->buscar_total_filas_listado($informacion_sql, $usuario);	
					if ($pagina == 0) {
						$pagina = 1;
						$comienzo_listado = true;
					}				
					$offset = ($limite * $pagina) - $limite;
					$data['total_paginas'] = $total_filas / $limite;
					$data['total_filas'] = $total_filas;					
					$data['pagina'] = $pagina;
					$data['limite'] = $limite;				
					$res = $this->Juego->listar($informacion_sql, $usuario, $limite, $offset);
					if ($res->num_rows() > 0) {
						$data['juegos'] = $res->result_array();					
						$data['usuario'] = $usuario;
						$data['informacion'] = $informacion;
						$data['nombre_usuario'] = $this->Usuario->obtener_nombre($usuario);
						if (isset($comienzo_listado) && $comienzo_listado)
							$this->template->load('template', 'juegos/listar', $data);
						else
							$this->load->view('juegos/listar', $data);
					} else {
						$this->session->set_flashdata('mensaje', 'No hay juegos que cumplan el criterio para éste usuario');
						redirect('usuarios/index/'.$usuario);	
					}
				}
			} else {
				$this->session->set_flashdata('mensaje', 'Debe indicarse un criterio para realizar el listado');
				redirect('usuarios/index/'.$usuario);	
			}
		} else {
			$this->session->set_flashdata('mensaje', 'No existe el usuario indicado');
			redirect('usuarios/buscar');
		}
	}
	
	private function _preparar_estado($juego, $usuario) {		
		$res = $this->Juego->obtener_relacion_juego_usuario($juego, $usuario);
		if ($res->num_rows() == 1) {
			$res = $res->row_array();
			$data['estado'] = $res['informacion'];
		} else {
			$data['estado'] = "No lo tienes";
		}
		$res = $this->Critica->obtener($usuario, $juego);
		if ($res->num_rows() == 1) $data['existe_critica'] = true;
		return $data;
	}
	
	private function _cargar_generos() {
		return array('Aventura' => 'Aventura',
								 'Accion' => 'Acción',
								 'Estrategia' => 'Estrategia',
								 'FPS' => 'FPS (Acción en primera persona)',
								 'Rol' => 'Rol',
								 'Plataformas' => 'Plataformas',
								 'Aventura' => 'Aventura',														
								 'Lucha' => 'Lucha',
								 'Velocidad' => 'Velocidad',
								 'Simulacion' => 'Simulación',
								 'Musical' => 'Musical',
								 'Social' => 'Social',
								 'Otros' => 'Otros'
							   );
	}
	
	private function _cargar_sistemas() {
		$res = $this->Juego->obtener_sistemas();
		$sistemas = array();
		foreach ($res as $sistema) {
		  $id = $sistema['id'];
			$sistemas[$id] = $sistema['nombre'];
		}
		return $sistemas;
	}
	
}
