<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Web_settings extends CI_Model {

	private $table  = "language";
    private $phrase = "phrase";

	public function __construct()
	{
		parent::__construct();
	}
	//Retrieve customer Edit Data
	public function retrieve_setting_editdata()
	{
		$this->db->select('*');
		$this->db->from('web_setting');
		$this->db->where('setting_id',1);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}
	
	//Update Categories
	public function update_setting($data)
	{
		$this->db->where('setting_id',1);
		$this->db->update('web_setting',$data);
		return true;
	}

    public function languages()
    { 
        if ($this->db->table_exists($this->table)) { 

                $fields = $this->db->field_data($this->table);

                $i = 1;
                foreach ($fields as $field)
                {  
                    if ($i++ > 2)
                    $result[$field->name] = ucfirst($field->name);
                }

                if (!empty($result)) return $result;
 

        } else {
            return false; 
        }
    }
	
}