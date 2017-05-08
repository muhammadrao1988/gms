<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class Login
 * @property M_Modules $m_modules
 * @property M_cpanel $m_cpanel
 */
class Modules extends CI_Controller
{
    var $table;
    var $id_field;
    var $module;
    var $module_name;
    var $module_title;

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

    }

    public function index()
    {
        $where = '';
        $where .= getFindQuery();

        $data['title'] = $this->module_title;
        $data['query'] = "SELECT
            m.id
            , m.module
            , IFNULL(pm.module_title, 'Main') AS parent
            , m.module_title
            , m.ordering
            , m.show_on_menu
            , m.actions
            , m.created
            , m.status
        FROM
            modules AS m
            LEFT JOIN modules AS pm
                ON (m.parent_id = pm.id) WHERE 1 " . $where;
        $this->load->view(ADMIN_DIR . $this->module_name . '/grid', $data);
    }


    public function form()
    {
        $id = intval(getUri(4));
        $data =  array();

        if ($id > 0) {
            $SQL = "SELECT * FROM " . $this->table . " WHERE " . $this->id_field . "='" . $id . "'";
            $data['row'] = $this->db->query($SQL)->row();
        }

        $this->load->view(ADMIN_DIR . $this->module_name . '/form', $data);
    }

    public function view()
    {
        $id = intval(getUri(4));

        if ($id > 0) {
            $SQL = "SELECT
                m.id
                , m.module
                , IFNULL(pm.module_title, 'Main') AS parent
                , m.module_title
                , m.ordering
                , m.show_on_menu
                , m.actions
                , m.created
                , m.status
            FROM
                modules AS m
                LEFT JOIN modules AS pm
                    ON (m.parent_id = pm.id)
                    WHERE " . $this->id_field . "='" . $id . "'";
            $data['row'] = $this->db->query($SQL)->row();
        }

        $data['title'] = $this->module_title;
        $this->load->view(ADMIN_DIR . $this->module_name . '/form', $data);
    }


    function status()
    {

        $id = intval(getUri(4));
        $status = (getUri(5));

        $where = $this->id_field . "='" . $id . "'";
        save($this->table, array('status' => $status), $where);
        redirect(ADMIN_DIR . $this->module_name);
    }

    public function add()
    {

        if (!$this->module->validate()) {
            $data['row'] = array2object($this->input->post());

            $this->load->view(ADMIN_DIR . $this->module_name . '/form', $data);
        } else {
            $DbArray = getDbArray($this->table);
            $DBdata = $DbArray['dbdata'];

            if (!empty($_FILES['icon']['name'])) {
                $fileData = $this->module->do_upload('icon');
                $DBdata['icon'] = $fileData['file_name'];
            }
            $id = save($this->table, $DBdata);

            redirect(ADMIN_DIR . $this->module_name . '/?msg=Record has been inserted..');
        }
    }


    public function update()
    {
        if (!$this->module->validate()) {
            $data['row'] = array2object($this->input->post());
            $this->load->view(ADMIN_DIR . $this->module_name . '/form', $data);
        } else {
            $DbArray = getDbArray($this->table);
            $DBdata = $DbArray['dbdata'];
            unset($DBdata['show_on_menu']);

            if (!empty($_FILES['icon']['name'])) {
                $fileData = $this->module->do_upload('icon');
                $DBdata['icon'] = $fileData['file_name'];
            }

            $DBdata['show_on_menu'] = getVar('show_on_menu');

            $where = $DbArray['where'];
            save($this->table, $DBdata, $where);
            redirect(ADMIN_DIR . $this->module_name . '/?msg=Record has been updated..');

        }
    }


    public function delete()
    {
		$JSON = array();
		if(getVar('action')==""){
		$id = getVar('del-id');
		}else{
		$id = getVar('del-all');	
		}
		
      
        $SQL = "DELETE FROM " . $this->table . " WHERE `" . $this->id_field . "` IN(" . $id . ")";
        $this->db->query($SQL);
		$JSON['notification'] = '<div class="alert alert-success "><button type="button" class="close" data-dismiss="alert">×</button>Record has been deleted..</div>';
		$redirct_url 		  =  '?msg=Record has been deleted..' ;
		$JSON['redirect_url'] =  $redirct_url;
		echo json_encode($JSON);
        
    }
}

/* End of file pages.php */
/* Location: ./application/controllers/admin/pages.php */