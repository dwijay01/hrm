<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MX_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->db->query('SET SESSION sql_mode = ""');

		$this->load->model(array(
			'auth_model'
		));

		// Auto-Migrate Indonesian Language
		$this->load->dbforge();
		if ($this->db->table_exists('language') && !$this->db->field_exists('indonesian', 'language')) {
			$this->dbforge->add_column('language', [
				'indonesian' => [
					'type' => 'TEXT',
					'null' => TRUE
				]
			]);
		}

		$this->load->helper('captcha');
	}

	public function index()
	{
		if ($this->session->userdata('isLogIn'))
			redirect('dashboard/home');
		$data['title'] = display('login');

		#-------------------------------------#
		$this->form_validation->set_rules('email', display('email'), 'required|valid_email|max_length[100]|trim');
		$this->form_validation->set_rules('password', display('password'), 'required|max_length[32]|md5|trim');
		// Captcha di-disable


		#-------------------------------------#
		$data['user'] = (object) $userData = array(
			'email' => $this->input->post('email', true),
			'password' => $this->input->post('password', true),
		);
		#-------------------------------------#
		if ($this->form_validation->run()) {

			$this->session->unset_userdata('captcha');

			$user = $this->auth_model->checkUser($userData);

			if ($user->num_rows() > 0) {

				$checkPermission = $this->auth_model->userPermission2($user->row()->id);
				if ($checkPermission != NULL) {
					$permission = array();
					$permission1 = array();
					if (!empty($checkPermission)) {
						foreach ($checkPermission as $value) {
							$permission[$value->module] = array(
								'create' => $value->create,
								'read' => $value->read,
								'update' => $value->update,
								'delete' => $value->delete
							);

							$permission1[$value->menu_title] = array(
								'create' => $value->create,
								'read' => $value->read,
								'update' => $value->update,
								'delete' => $value->delete
							);
						}
					}
				}



				if ($user->row()->is_admin == 2) {
					$row = $this->db->select('client_id,client_email')->where('client_email', $user->row()->email)->get('setup_client_tbl')->row();
				}

				$employee_info = $this->db->select('employee_id,employee_status,first_name,last_name,is_super_visor')->from('employee_history')->where('email', $user->row()->email)->get()->row();

				// Check if the user is active or inactive from caomparion with employee from $employee_info
				if (!$user->row()->is_admin) {

					if ($user->row()->status != 1 || $employee_info->employee_status != 1) {

						$this->session->set_flashdata('exception', "Seems you are not active user of the system !");
						redirect('login');
					}
				}
				// End of Check if the user is active or inactive from caomparion with employee from $employee_info

				// Financial year
				$fyear = $this->auth_model->checkfinancialyear();
				if (!$fyear) {
					$this->session->set_flashdata('message', display('welcome_back') . ' ' . $user->row()->fullname);
					redirect('accounts/accounts/financial_year');
				} else {

					$sData = array(
						'isLogIn' => true,
						'isAdmin' => (($user->row()->is_admin == 1) ? true : false),
						'user_type' => $user->row()->is_admin,
						'id' => $user->row()->id,
						'client_id' => @$row->client_id,
						'fullname' => $user->row()->fullname,
						'user_level' => $user->row()->user_level,
						'email' => $user->row()->email,
						'image' => $user->row()->image,
						'last_login' => $user->row()->last_login,
						'last_logout' => $user->row()->last_logout,
						'ip_address' => $user->row()->ip_address,
						'employee_id' => $employee_info->employee_id,
						'first_name' => $employee_info->first_name,
						'last_name' => $employee_info->last_name,
						'supervisor' => $employee_info->is_super_visor,
						'permission' => json_encode(@$permission),
						'label_permission' => json_encode(@$permission1),
						'fyear' => $fyear->id,
						'fyearName' => $fyear->yearName,
						'fyearStartDate' => $fyear->startDate,
						'fyearEndDate' => $fyear->endDate,
					);

					//store date to session 
					$this->session->set_userdata($sData);
					//update database status
					$this->auth_model->last_login();

					$this->session->set_flashdata('message', display('welcome_back') . ' ' . $user->row()->fullname);
					redirect('dashboard/home');
				}

			} else {
				$this->session->set_flashdata('exception', display('incorrect_email_or_password'));
				redirect('login');
			}

		} else {

			$data['captcha_word'] = '';
			$data['captcha_image'] = '';
			$this->session->set_userdata('captcha', '');

			echo Modules::run('template/login', $data);
		}
	}

	public function logout()
	{
		//update database status
		$this->auth_model->last_logout();
		//destroy session
		$this->session->sess_destroy();
		redirect('login');
	}
	/*
 |--------------------------------------------------------
 | Finger print Device information
 |--------------------------------------------------------
 */
	public function deviceData()
	{
		return $this->db->select('*')->from('deviceinfo')->get()->row();
	}

}
