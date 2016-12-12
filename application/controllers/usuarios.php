<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usuarios extends CI_Controller {

  function __construct() {		
    CI_Controller::__construct();
    $this->load->model('Usuario');
    $this->load->model('Juego');
    $this->load->model('Critica');
    $this->load->model('Sistema');
  }

	public function index($id = "", $pagina = 0)
	{
		if (!$this->session->userdata('id') && $id == "") {	
			redirect('usuarios/buscar');
		} else {
			if ($id == "") $id = $this->session->userdata('id');
			$data = $this->_recoger_datos_usuario($id, $pagina);
			if ($data != false) {				
				$this->template->load_array('template', array('usuarios/index', 'tablones/index'), $data);
			} else {
				$this->session->set_flashdata('mensaje', 'No se encuentra el usuario solicitado');
				redirect('usuarios/buscar');
			}
		}
	}
	
	function login() {
	  $data['login'] = true;
  	if ($this->input->post('login')) {
      $nombre = $this->input->post('nombre');
      $password = $this->input->post('password');
      $res = $this->Usuario->obtener($nombre, $password);
      if ($res->num_rows() == 1) {
        $datos = $res->row_array();
        $this->session->set_userdata('id', $datos['id']);
        $this->session->set_userdata('usuario', $nombre);
				$this->session->set_userdata('email', $datos['email']);
        redirect('portadas/index');
      } else {
        $mensaje = 'Error: usuario o contraseña incorrectos';
      }
    } else {
      $mensaje = $this->session->flashdata('mensaje');
    }
    if ($this->session->flashdata('mensaje')) $data['mensaje'] = $this->session->flashdata('mensaje');
    $this->template->load('template', 'usuarios/login', array('mensaje' => $mensaje, 'login' => true));
  }
  
  function logout() {
    $this->session->sess_destroy();
    redirect('portadas/index');
  }
	
	public function crear()	{
		$data = array('nombre' => '', 'email' => '');
		$mensaje = ($this->session->flashdata('mensaje')) ? $this->session->flashdata('mensaje') : '';
		if ($this->input->post('crear')) {
			$nombre = $this->input->post('nombre');
			$password = $this->input->post('password');
			$confirm_password = $this->input->post('confirm_password');
			$email = $this->input->post('email');
			$data = array('nombre' => $nombre, 'email' => $email);
			if ($this->session->flashdata('mensaje')) $mensaje = $this->session->flashdata('mensaje');
			$mensajes = array();
			if (empty($nombre) || empty($nombre) || empty($confirm_password) || empty($email)) {
				$mensajes[] = "No se puede dejar ningún campo vacío";
			} elseif ($password != $confirm_password) {
				$mensajes[] = "Las contraseñas introducidas no coinciden";
			} else {				
				if (strlen($nombre) < 6) $mensajes[] = "El nombre debe tener al menos 6 caracteres";
				if (strlen($nombre) > 25)	$mensajes[] = "El nombre no puede tener más de 25 caracteres";
				if (strlen($password) < 6) $mensajes[] = "La contraseña debe tener al menos 6 caracteres";
				if (strlen($password) > 25)	$mensajes[] = "El password no puede tener más de 25 caracteres";
				if (strlen($email) > 50) $mensajes[] = "El email no puede tener más de 50 caracteres";
				if (sizeof($mensajes) == 0) {
					$this->form_validation->set_rules('nombre', 'nombre', 'is_unique[usuarios.nombre]');
					if ($this->form_validation->run() == false) {
						$mensajes[] = "El nombre de usuario introducido ya está registrado";
					} else {
						$this->form_validation->set_rules('email', 'email', 'is_unique[usuarios.email]');
						if ($this->form_validation->run() == false) {
							$mensajes[] = "El email introducido ya está registrado";
						} else {
							$this->Usuario->crear(trim($nombre), trim($password), trim($email));
							if ($this->db->affected_rows() == 1) {
								$usuario_creado = true;			
							} else {
								$mensajes[] = "Ha habido un error a la hora de crear el usuario";
							}
						}
					}
				}
			}
			$data['mensajes'] = $mensajes;			
			if (isset($usuario_creado)) {
				$this->session->set_flashdata('mensaje', 'Usuario creado, ya puede iniciar sesión');
			  redirect('usuarios/login');
			} else {
				$data['crear_usuario'] = true;
				$this->template->load('template', 'usuarios/crear', $data);
			}
		} elseif ($this->input->post('cancelar')) {			
			redirect('portadas/index');
		} else {			
			$data['crear_usuario'] = true;
			$this->template->load('template', 'usuarios/crear', $data);
		}
	}
	
	function cambiar_estado() {
		if ($this->session->userdata('id')) {
			$data = $this->_recoger_datos_usuario($this->session->userdata('id'));
			if ($this->input->post('hay_estado')) {
				if (strlen($this->input->post('estado')) <= 60) {
					$res = $this->db->query('update usuarios set estado = ? where id = ?',
																	 array($this->input->post('estado'), $this->session->userdata('id')));
					if ($this->db->affected_rows() == 0) 
						$this->session->set_flashdata('mensaje', 'Ha habido un error al cambiar el estado');
					$data = $this->_recoger_datos_usuario($this->session->userdata('id'));
				} else {
					$data['mensaje'] = 'El estado no puede tener más de 60 caracteres';
				}
			}
			$this->load->view('usuarios/index', $data);
		} else {
			$this->session->set_flashdata('mensaje', 'Primero debes iniciar sesión');
			redirect('usuarios/login');
		}
	}
	
	function cambiar_avatar() {
		if ($this->session->userdata('id')) {
			if ($this->input->post('cambio_avatar')) {				
				$config['upload_path'] = '/home/toni/web/juegos/imagenes/usuarios/';
				$config['allowed_types'] = 'gif|jpg|png';
				$config['max_size']	= '200';
				$config['max_width'] = '80';
				$config['max_height'] = '80';
				$this->load->library('upload', $config);
				if (!$this->upload->do_upload()) {
					$this->session->set_flashdata('mensaje', 'Error al subir imagen, compruebe que el tamaño y el tipo son los correctos');
				} else {
					$avatar = $this->upload->data();
					$this->Usuario->actualizar_avatar("usuarios/{$avatar['file_name']}", $this->session->userdata('id'));
					if ($this->db->affected_rows() == 0)
						$this->session->set_flashdata('mensaje', 'Ha habido un error a la hora de cambiar el avatar');
				}	
				$data = $this->_recoger_datos_usuario($this->session->userdata('id'));
				$this->template->load('template', 'usuarios/index', $data);
			} else {
				redirect('usuarios/index/'.$this->session->userdata('id').'/true');
			}
		} else {
			$this->session->set_flashdata('mensaje', 'Primero debes iniciar sesión');
			redirect('usuarios/login');
		}
	}
	
	function cambiar_password() {
		if ($this->session->userdata('usuario')) {
			if ($this->input->post('editar')) {
				$password = trim($this->input->post('password'));
				$npassword = trim($this->input->post('npassword'));
				$cpassword = trim($this->input->post('confirm_password'));
				if ($password == '' || $npassword == '' || $cpassword == '') {
					$data['mensaje'] = 'Ningún campo puede estar vacío';
					$this->template->load('template', 'usuarios/editar', $data);
				} else {
					$res = $this->Usuario->obtener_datos($this->session->userdata('id'))->row_array();
					if ($res != 0) {
						if (md5($password) == $res['password']) {
							if ($npassword == $cpassword) {
								$this->Usuario->modificar_password($npassword, $this->session->userdata('id'));
								if ($this->db->affected_rows() != 0): 
									$this->session->set_flashdata('mensaje', 'Contraseña modificada con éxito');
								else: $this->session->set_flashdata('mensaje', 'Ha habido un error al intentar modificar la contraseña');
								endif;
								redirect('usuarios/index');
							} else {
								$data['mensaje'] = 'La nueva contraseña y su verificación no coinciden';
								$this->template->load('template', 'usuarios/editar', $data);
							}
						} else {
							$data['mensaje'] = 'La contraseña antigua introducida es erronea';
							$this->template->load('template', 'usuarios/editar', $data);
						}
					} else {
						$this->session->set_flashdata('mensaje', 'Error al consultar la antigua contraseña del usuario');
						redirect('usuarios/index');
					}
				}
			} elseif ($this->input->post('cancelar')) {
				redirect('usuarios/index');
			} else {
				$this->template->load('template', 'usuarios/editar');
			}
		} else {
			$this->session->set_flashdata('Debes estar logueado primero');
			redirect('usuarios/login');
		}
	}
	
	function buscar($valor = "", $pagina = 0) {
		if ($this->session->flashdata('mensaje')) $data['mensaje'] = $this->session->flashdata('mensaje');
		$data['valor'] = $valor;
		$data['pagina'] = $pagina;
		if ($pagina > 0) {
			if (trim($valor) != "" && trim($valor) != "0") {
				$total_filas = $this->Usuario->buscar_total_filas($valor);
				if ($total_filas > 0) {
					$limite = 10;
					if ($pagina == 0) $pagina = 1;
					$offset = ($limite * $pagina) - $limite;
					$data['total_paginas'] = $total_filas / $limite;
					$data['total_filas'] = $total_filas;
					$res = $this->Usuario->buscar($valor, $limite, $offset)->result_array();
					$data['usuarios'] = $res;
					$endfor = count($data['usuarios']) - 1;
					for ($i = 0; $i <= $endfor; $i++) {
						$data['usuarios'][$i]['karma'] = $this->Usuario->calcular_karma($data['usuarios'][$i]['id']);
					}
					$data['valor'] = $valor;
					$data['pagina'] = $pagina;
					$data['limite'] = $limite;					
					$this->load->view('usuarios/resultado_busqueda', $data);
				} else {
					$data['resultado'] = 'No se han encontrado usuarios con el criterio introducido';
					$this->load->view('usuarios/resultado_busqueda', $data);
				}
			} else {
				$data['resultado'] = 'El criterio de búsqueda no puede estar vacío';
				$this->load->view('usuarios/resultado_busqueda', $data);
			}
		} else {			
			$this->template->load('template', 'usuarios/buscar', $data);
		}
	}
	
	function listar($tipo, $id, $pagina = 0) {
		$limite = 10;
		if ($pagina == 0) {
			$pagina = 1;
			$comienzo_listado = true;
		}
		$offset = ($limite * $pagina) - $limite;
		$data = array('pagina' => $pagina, 'limite' => $limite, 'tipo' => $tipo, 'id' => $id);
		if ($tipo == 'seguidores' || $tipo == 'seguidos') {
			if ($this->Usuario->existe($id)) {
				if ($tipo == 'seguidores') $total_filas = $this->Usuario->obtener_total_seguidores($id);
				elseif ($tipo == 'seguidos') $total_filas = $this->Usuario->obtener_total_seguidos($id);
				$data['total_paginas'] = $total_filas / $limite;
				$data['total_filas'] = $total_filas;
				if ($tipo == 'seguidores') $res = $this->Usuario->obtener_seguidores($id, $limite, $offset);
				elseif ($tipo == 'seguidos') $res = $this->Usuario->obtener_seguidos($id, $limite, $offset);
				if (isset($res) && $res->num_rows() > 0) {
					$data['usuarios'] = $res->result_array();
					$endfor = count($data['usuarios']) - 1;
					for ($i = 0; $i <= $endfor; $i++) {
						$data['usuarios'][$i]['karma'] = $this->Usuario->calcular_karma($data['usuarios'][$i]['id']);
					}					
					$usuario = $this->Usuario->obtener_datos($id)->row_array();
					$data['cabecera'] = "Lista de usuarios $tipo de ".anchor('usuarios/index/'.$id, $usuario['nombre']);
					if (isset($comienzo_listado) && $comienzo_listado)
						$this->template->load('template', 'usuarios/listar', $data);
					else
						$this->load->view('usuarios/listar', $data);
				} else {
					$this->session->set_flashdata('mensaje', "El usuario no tiene usuarios $tipo");
					redirect('usuarios/index/'.$id);
				}
			} else {
				$this->session->set_flashdata('mensaje', 'El usuario solicitado no existe');
				redirect('usuarios/buscar');
			}			
		} elseif ($tipo == 'critica') {
			if ($this->Juego->existe($id)) {			
				$total_filas = $this->Usuario->obtener_total_por_valoraciones($id);
				$data['total_paginas'] = $total_filas / $limite;
				$data['total_filas'] = $total_filas;		
				$res = $this->Usuario->obtener_por_valoraciones($id, $limite, $offset);
				if ($res->num_rows() > 0) {
					$data['usuarios'] = $res->result_array();
					$endfor = count($data['usuarios']) - 1;
					for ($i = 0; $i <= $endfor; $i++) {
						$data['usuarios'][$i]['karma'] = $this->Usuario->calcular_karma($data['usuarios'][$i]['id']);
						$res = $this->Critica->obtener_valoracion($data['usuarios'][$i]['id'], $id)->row_array();
						$data['usuarios'][$i]['valoracion'] = $res['valor'];
					}
					$critica = $this->Critica->obtener_por_id($id)->row_array();
					$usuario = $this->Usuario->obtener_datos($critica['usuario'])->row_array();
					$juego = $this->Juego->obtener_datos($critica['juego'])->row_array();
					$data['cabecera'] = "Usuarios que puntuaron la ".anchor("criticas/index/{$juego['id']}/{$usuario['id']}",'crítica');
					$data['cabecera'] .= " de " . anchor('usuarios/index/'.$usuario['id'], $usuario['nombre'])." a ";
					$data['cabecera'] .= anchor('juegos/index/'.$juego['id'], $juego['nombre']);
					if (isset($comienzo_listado) && $comienzo_listado)
						$this->template->load('template', 'usuarios/listar', $data);
					else
						$this->load->view('usuarios/listar', $data);
				} else {
					$this->session->set_flashdata('mensaje', 'El juego solicitado no tiene ninguna crítica');
					redirect('juegos/index');
				}
			} else {
				$this->session->set_flashdata('mensaje', 'El juego solicitado no existe');
				redirect('juegos/buscar');
			}		
		} elseif ($tipo == 'pasado' || $tipo == 'pendiente' || $tipo == 'deseado') {
			if ($this->Juego->existe($id)) {			
				$total_filas = $this->Usuario->obtener_total_por_juego($id);
				$data['total_paginas'] = $total_filas / $limite;
				$data['total_filas'] = $total_filas;
				$res = $this->Usuario->obtener_por_juego($id, $limite, $offset);
				if ($res->num_rows() > 0) {
					$data['usuarios'] = $res->result_array();
					$endfor = count($data['usuarios']) - 1;
					for ($i = 0; $i <= $endfor; $i++) {
						$data['usuarios'][$i]['karma'] = $this->Usuario->calcular_karma($data['usuarios'][$i]['id']);
					}
					$juego = $this->Juego->obtener_datos($id)->row_array();
					$data['cabecera'] = "Usuarios que han marcado ".anchor('juegos/index/'.$id, $juego['nombre'])." como $tipo";
					if (isset($comienzo_listado) && $comienzo_listado)
						$this->template->load('template', 'usuarios/listar', $data);
					else
						$this->load->view('usuarios/listar', $data);
				} else {
					$this->session->set_flashdata('mensaje', 'El juego solicitado actualmente no está ' . $tipo . ' por nadie');
					redirect('juegos/index/'.$id);
				}
			} else {
				$this->session->set_flashdata('mensaje', 'El juego solicitado no existe');
				redirect('juegos/buscar');
			}		
		} else {
			$this->session->set_flashdata('mensaje', 'El tipo de lista solicitado es erroneo');
			redirect('usuarios/buscar');
		}
	}
	
	function cambiar_seguimiento() {
		if ($this->session->userdata('id')) {
			if ($this->input->post('confirmar') && $this->input->post('id')) {
				$seguidor = $this->session->userdata('id');
				$seguido = $this->input->post('id');
				$confirmar = $this->input->post('confirmar');
				if ($confirmar == 'Seguir') {
					if (!$this->Usuario->hay_seguimiento($seguidor, $seguido)) {
						$this->Usuario->crear_seguimiento($seguidor, $seguido);
						if ($this->db->affected_rows() == 0) $this->session->set_flashdata('mensaje', 'Se ha producido un error');
					} else {
						$this->session->set_flashdata('mensaje', 'Ya sigues a este usuario');					
					}
				} elseif ($confirmar == 'Dejar de seguir') {
					if ($this->Usuario->hay_seguimiento($seguidor, $seguido)) {
						$this->Usuario->borrar_seguimiento($seguidor, $seguido);
						if ($this->db->affected_rows() == 0) $this->session->set_flashdata('mensaje', 'Se ha producido un error');
					} else {
						$this->session->set_flashdata('mensaje', 'No seguías a este usuario');					
					}
				}
				$data = $this->_recoger_datos_usuario($seguido);
				if ($data != false) {				
					$this->load->view('usuarios/index', $data);
					$this->load->view('tablones/index', $data);
				} else {
					$this->session->set_flashdata('mensaje', 'No se encuentra el usuario solicitado');
					redirect('usuarios/buscar');
				}
			} else {
				redirect('usuarios/index');
			}
		} else {
			$this->session->set_flashdata('mensaje', 'Primero debes iniciar sesión');
			redirect('usuarios/login');
		}
	}
	
	private function _recoger_datos_usuario($id, $pagina = false) {
		$res = $this->Usuario->obtener_datos($id);
		if ($res->num_rows() == 1) {
			$data = $res->row_array();
			if ($this->session->flashdata('mensaje')) $data['mensaje'] = $this->session->flashdata('mensaje');
			$res = $this->Usuario->contar_juegos($id, 'Te lo has pasado');
			$data['pasados'] = $res['total_juegos'];
			$res = $this->Usuario->contar_juegos($id, 'Lo tienes pendiente');
			$data['pendientes'] = $res['total_juegos'];
			$res = $this->Usuario->contar_juegos($id, 'Lo quieres');
			$data['deseados'] = $res['total_juegos'];		
			$data['karma'] = $this->Usuario->calcular_karma($id);
			if ($this->session->userdata('id') && $id != $this->session->userdata('id'))
				$data['seguimiento'] = $this->Usuario->hay_seguimiento($this->session->userdata('id'), $id);
			if ($this->session->flashdata('mensaje')) $data['mensaje'] = $this->session->flashdata('mensaje');
			$data['sistemas'] = $this->Sistema->obtener_sistemas_de_usuario($id);
			$data['total_criticas'] = $this->Critica->contar_por_usuario($id);
			$res = $this->Usuario->obtener_seguidores($id, 3, 0);
			$data['seguidores'] = ($res->num_rows() > 0) ? $res->result_array() : '';
			$data['total_seguidores'] = $this->Usuario->obtener_total_seguidores($id);
			$res = $this->Usuario->obtener_seguidos($id, 3, 0);
			$data['seguidos'] = ($res->num_rows() > 0) ? $res->result_array() : '';
			$data['total_seguidos'] = $this->Usuario->obtener_total_seguidos($id);
			$this->load->library('Utilidades');
			$data = array_merge($data, $this->utilidades->recoger_datos_tablon($id, $pagina));
			if ($this->session->userdata('id'))
				$data['es_seguidor'] = $this->Usuario->es_seguidor($id, $this->session->userdata('id'));
			return $data;
		} else {
			return false;
		}
	}
	
}
