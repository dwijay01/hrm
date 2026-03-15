<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shift extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->db->query('SET SESSION sql_mode = ""');
        $this->load->model(array(
            'Shift_model',
        )); 
        if (! $this->session->userdata('isLogIn'))
            redirect('login');
    }
    
    // ==========================================
    // SHIFT MASTER
    // ==========================================
    
    public function shift_setup() {
        $this->permission->check_label('attendance')->read()->redirect();
        
        $data['title'] = "Shift Master Setup";
        $data['shifts'] = $this->Shift_model->get_all_shifts();
        $data['module'] = "attendance";
        $data['page'] = "shift/shift_setup";
        echo Modules::run('template/layout', $data);
    }
    
    public function save_shift() {
        $this->permission->check_label('attendance')->create()->redirect();
        
        $this->form_validation->set_rules('shift_name', 'Shift Name', 'required|max_length[100]');
        $this->form_validation->set_rules('start_time', 'Start Time', 'required');
        $this->form_validation->set_rules('end_time', 'End Time', 'required');
        
        if ($this->form_validation->run() === true) {
            $postData = [
                'shift_name'    => $this->input->post('shift_name', true),
                'start_time'    => $this->input->post('start_time', true),
                'end_time'      => $this->input->post('end_time', true),
                'tolerance_minutes' => $this->input->post('tolerance_minutes', true),
                'status'        => $this->input->post('status', true),
            ];
            
            $shift_id = $this->input->post('shift_id');
            if (empty($shift_id)) {
                if ($this->Shift_model->create_shift($postData)) {
                    $this->session->set_flashdata('message', display('save_successfully'));
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }
            } else {
                $postData['shift_id'] = $shift_id;
                if ($this->Shift_model->update_shift($postData)) {
                    $this->session->set_flashdata('message', display('update_successfully'));
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }
            }
            redirect("attendance/Shift/shift_setup");
            
        } else {
            $this->session->set_flashdata('exception', validation_errors());
            redirect("attendance/Shift/shift_setup");
        }
    }
    
    public function delete_shift($id = null) {
        $this->permission->check_label('attendance')->delete()->redirect();
        if ($this->Shift_model->delete_shift($id)) {
            $this->session->set_flashdata('message', display('delete_successfully'));
        } else {
            $this->session->set_flashdata('exception', display('please_try_again'));
        }
        redirect("attendance/Shift/shift_setup");
    }
    
    // ==========================================
    // SHIFT ROSTER
    // ==========================================
    
    public function shift_roster() {
        $this->permission->check_label('attendance')->read()->redirect();
        
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        if(empty($start_date)) $start_date = date('Y-m-d');
        if(empty($end_date)) $end_date = date('Y-m-d', strtotime('+7 days'));
        
        $data['title'] = "Employee Shift Roster";
        $data['roster_data'] = $this->Shift_model->get_roster_by_date_range($start_date, $end_date);
        $data['shift_list'] = $this->Shift_model->get_active_shifts();
        $data['employee_list'] = $this->Shift_model->employee_dropdown();
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        
        $data['module'] = "attendance";
        $data['page'] = "shift/shift_roster";
        echo Modules::run('template/layout', $data);
    }
    
    public function save_roster() {
        $this->permission->check_label('attendance')->create()->redirect();
        
        $this->form_validation->set_rules('employee_id', 'Employee', 'required');
        $this->form_validation->set_rules('shift_id', 'Shift', 'required');
        $this->form_validation->set_rules('start_date', 'Start Date', 'required');
        $this->form_validation->set_rules('end_date', 'End Date', 'required');
        
        if ($this->form_validation->run() === true) {
            $employee_id = $this->input->post('employee_id', true);
            $shift_id = $this->input->post('shift_id', true);
            $start = new DateTime($this->input->post('start_date', true));
            $end = new DateTime($this->input->post('end_date', true));
            $end = $end->modify('+1 day'); 
            
            $interval = new DateInterval('P1D');
            $daterange = new DatePeriod($start, $interval ,$end);
            
            $success = true;
            foreach ($daterange as $date) {
                $postData = [
                    'employee_id' => $employee_id,
                    'shift_id'    => $shift_id,
                    'roster_date' => $date->format("Y-m-d"),
                    'assigned_by' => $this->session->userdata('id'),
                    'created_at'  => date('Y-m-d H:i:s')
                ];
                if(!$this->Shift_model->save_roster($postData)) {
                    $success = false;
                }
            }
            
            if ($success) {
                $this->session->set_flashdata('message', display('save_successfully'));
            } else {
                $this->session->set_flashdata('exception', display('please_try_again'));
            }
        } else {
            $this->session->set_flashdata('exception', validation_errors());
        }
        redirect("attendance/Shift/shift_roster");
    }
    
    public function delete_roster($id = null) {
        $this->permission->check_label('attendance')->delete()->redirect();
        if ($this->Shift_model->delete_roster($id)) {
            $this->session->set_flashdata('message', display('delete_successfully'));
        } else {
            $this->session->set_flashdata('exception', display('please_try_again'));
        }
        redirect($_SERVER['HTTP_REFERER']);
    }
}
