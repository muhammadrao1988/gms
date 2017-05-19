<?php


/**
 * Class User_types
 * @property M_user_types $m_user_types
 * @property M_cpanel $m_cpanel
 */
if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_types extends CI_Controller
{
    var $table;
    var $id_field;
    var $module;
    var $module_name;
    var $module_title;
    var $iic_user_type;

    function __construct()
    {
        parent::__construct();
        $this->m_cpanel->checkLogin();

        //TODO:: Module Name
        $this->module_name = getUri(2);
        $this->module = 'm_' . $this->module_name;
        $this->load->model(ADMIN_DIR . $this->module);
        $this->module = $this->{$this->module};

        $this->table = $this->module->table;
        $this->id_field = $this->module->id_field;

        $this->module_title = ucwords(str_replace('_', ' ', $this->module_name));
        $this->iic_user_type = intval(get_option('iic_user_type'));
    }

    /**
     * *****************************************************************************************************************
     * @method user_types index | Grid | listing
     * *****************************************************************************************************************
     */

    public function index()
    {
        $where = '';
        $where .= getFindQuery();

        $data['title'] = $this->module_title;
        $data['query'] = "SELECT ".$this->table.".id, ".$this->table.".user_type as template_name,   IF(".$this->table.".user_status=1,'Yes','No') AS active,  ".$this->table.".last_edited, CONCAT(users.first_name,' ',users.surname) AS edited_by  
		FROM " . $this->table . "
		
		LEFT JOIN users ON (".$this->table.".edited_by = users.user_id)
		WHERE 1 " . $where;
        $this->load->view(ADMIN_DIR . $this->module_name . '/grid', $data);
    }

    /**
     * *****************************************************************************************************************
     * @method user_types form
     * *****************************************************************************************************************
     */
    public function form()
    {
        $id = intval(getUri(4));
        $data = array();

        if ($id) {
            $SQL = "SELECT * FROM " . $this->table . " WHERE " . $this->id_field . "='" . $id . "'";
            $data['row'] = $this->db->query($SQL)->row();
        }
        /*------------------------------------Modules ------------------------------*/
        $sql = "SELECT module_id,actions FROM user_type_module_rel WHERE user_type_id='{$id}'";
        $rs = $this->db->query($sql);
        $modules = array();
        $selected_action = array();
        if ($rs->num_rows() > 0) {
            foreach ($rs->result() as $module) {
                array_push($modules, $module->module_id);
                $selected_action[$module->module_id] = explode('|', $module->actions);

            }

        }
        $data['modules'] = $modules;
        $data['selected_action'] = $selected_action;
        /*------------------------------------ END Modules ------------------------------*/

        $this->load->view(ADMIN_DIR . $this->module_name . '/form', $data);
    }

    /**
     * *****************************************************************************************************************
     * @method user_types form
     * *****************************************************************************************************************
     */
    public function view()
    {
        $id = intval(getUri(4));

        if ($id > 0) {
            $SQL = "SELECT * FROM " . $this->table . " WHERE id NOT IN(-1, '" . $this->iic_user_type . "') AND " . $this->id_field . "='{$id}'";
            $data['row'] = $this->db->query($SQL)->row();
        }

        $data['title'] = $this->module_title;
        $this->load->view(ADMIN_DIR . $this->module_name . '/form', $data);
    }


    public function add()
    {

        if (!$this->module->validate()) {
            $data['row'] = array2object($this->input->post());

            $this->load->view(ADMIN_DIR . $this->module_name . '/form', $data);
        } else {
            $DbArray = getDbArray($this->table);
            $DBdata = $DbArray['dbdata'];
            $id = save($this->table, $DBdata);

            if (count(getVar('modules')) > 0) {
                foreach (getVar('modules') as $module_id) {
                    $data = array(
                        'user_type_id' => $id,
                        'module_id' => $module_id,
                        'actions' => implode('|', $_REQUEST['actions'][$module_id])
                    );
                    save('user_type_module_rel', $data);
                }
            }

            redirect(ADMIN_DIR . $this->module_name . '/?msg=Record has been inserted..');
        }
    }


    public function update()
    {
         $id = intval(getVar('id'));
        if (!$this->module->validate()) {
            $data['row'] = array2object($this->input->post());
            $this->load->view(ADMIN_DIR . $this->module_name . '/form', $data);
        } else {
            $DbArray = getDbArray($this->table);
            $DBdata = $DbArray['dbdata'];
            unset($DbArray['dbdata']['show_on_menu']);

            $DbArray['dbdata']['show_on_menu'] = getVar('show_on_menu');


            $where = $DbArray['where'];
            save($this->table, $DBdata, $where);

            $this->db->delete('user_type_module_rel', "`user_type_id` ='" . $id."'");

            foreach (getVar('modules') as $module_id) {

                $data = array(
                    'user_type_id' => $id,
                    'module_id' => $module_id,
                    'actions' => implode('|', $_REQUEST['actions'][$module_id])
                );
                save('user_type_module_rel', $data);
            }


                redirect(ADMIN_DIR . $this->module_name . '/?msg=Record has been updated..');


        }
    }

    /**
     * *****************************************************************************************************************
     * @method Delete
     * @unlink Delete Files (unlink)
     * *****************************************************************************************************************
     */
    public function delete()
    {
        $JSON = array();

		$id = getVar('del-id');

		
        if($id > 1) {
             $SQL = "DELETE FROM " . $this->table . " WHERE `" . $this->id_field . "` = '".$id."'";
            $this->db->query($SQL);
             $SQL = "DELETE FROM user_type_module_rel WHERE `user_type_id`  = '" . $id . "'";
            $this->db->query($SQL);
            $JSON['notification'] = '<div class="alert alert-success "><button type="button" class="close" data-dismiss="alert">×</button>Record has been deleted..</div>';
            $redirct_url = '?msg=Record has been deleted..';
            $JSON['redirect_url'] = $redirct_url;
        }else{
            $JSON['notification'] = '<div class="alert alert-danger "><button type="button" class="close" data-dismiss="alert">×</button>This record can not be deleted..</div>';
            $redirct_url = '?error=This record can not be deleted..';
            $JSON['redirect_url'] = $redirct_url;
        }
		echo json_encode($JSON);
    }

    /**
     * *****************************************************************************************************************
     * @method Status
     * @unlink Delete Files (unlink)
     * *****************************************************************************************************************
     */
    function status()
    {
        $ids = $this->uri->segment(4);
        $status = 'active';
        if (getVar('status') == 'active') {
            $status = 'inactive';
        }
        $data = array('status' => $status);

        $where = $this->id_field . " IN ({$ids})";
        save($this->table, $data, $where);
        $this->index();
    }


    /**
     * *****************************************************************************************************************
     * @method import
     * *****************************************************************************************************************
     */

  
    
}

?>