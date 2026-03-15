<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shift_model extends CI_Model {
    
    // -- Shift Master CRUD --
    
    public function get_all_shifts() {
        return $this->db->get('shift_master')->result();
    }
    
    public function get_active_shifts() {
        $this->db->where('status', 1);
        return $this->db->get('shift_master')->result_array();
    }
    
    public function get_shift_by_id($id) {
        $this->db->where('shift_id', $id);
        return $this->db->get('shift_master')->row();
    }
    
    public function create_shift($data) {
        return $this->db->insert('shift_master', $data);
    }
    
    public function update_shift($data) {
        $this->db->where('shift_id', $data['shift_id']);
        return $this->db->update('shift_master', $data);
    }
    
    public function delete_shift($id) {
        $this->db->where('shift_id', $id);
        return $this->db->delete('shift_master');
    }
    
    // -- Employee Shift Roster CRUD --
    public function get_roster_by_date_range($start_date, $end_date) {
        $this->db->select('r.*, e.first_name, e.last_name, s.shift_name, s.start_time, s.end_time');
        $this->db->from('employee_shift_roster r');
        $this->db->join('employee_history e', 'e.employee_id = r.employee_id');
        $this->db->join('shift_master s', 's.shift_id = r.shift_id');
        if (!empty($start_date) && !empty($end_date)) {
            $this->db->where("r.roster_date BETWEEN '$start_date' AND '$end_date'");
        }
        $this->db->order_by('r.roster_date', 'DESC');
        return $this->db->get()->result();
    }
    
    public function get_roster_by_employee_date($employee_id, $date) {
        $this->db->select('r.*, s.start_time, s.end_time, s.tolerance_minutes, s.shift_name');
        $this->db->from('employee_shift_roster r');
        $this->db->join('shift_master s', 's.shift_id = r.shift_id');
        $this->db->where('r.employee_id', $employee_id);
        $this->db->where('r.roster_date', $date);
        return $this->db->get()->row();
    }
    
    public function save_roster($data) {
        // Check if roster for this employee and date already exists
        $this->db->where('employee_id', $data['employee_id']);
        $this->db->where('roster_date', $data['roster_date']);
        $exists = $this->db->get('employee_shift_roster')->row();
        
        if ($exists) {
            $this->db->where('roster_id', $exists->roster_id);
            return $this->db->update('employee_shift_roster', $data);
        } else {
            return $this->db->insert('employee_shift_roster', $data);
        }
    }
    
    public function delete_roster($id) {
        $this->db->where('roster_id', $id);
        return $this->db->delete('employee_shift_roster');
    }
    
    public function employee_dropdown() {
        $this->db->select('*');
        $this->db->from('employee_history');
        $this->db->where('employee_status', 1);
        $query = $this->db->get();
        $data = $query->result();
        
        $list = array('' => 'Select Employee...');
        if (!empty($data)) {
            foreach ($data as $value) {
                $list[$value->employee_id] = $value->first_name . ' ' . $value->last_name . " (" . $value->employee_id . ")";
            }
        }
        return $list;
    }
}
