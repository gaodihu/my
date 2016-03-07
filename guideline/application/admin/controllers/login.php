<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
    function __construct()
    {
        parent::__construct();
    }
	public function index()
	{  
        $this->load->helper(array('form', 'url'));
        if(isset($this->session->userdata['user_name'])){
            redirect(base_url('index.php/home/index'));
        }
        $data['action'] =base_url('index.php/login/check_login');
        if(isset($this->session->userdata['error_message'])){
            $data['error_message'] =$this->session->userdata['error_message'];
            $this->session->unset_userdata('error_message');
        }else{
            $data['error_message'] ='';
        }
		$this->load->view('login',$data);
	}
    public function check_login(){
        $this->load->model('User_model');
        $user_name =$this->input->post('username');
        $password =$this->input->post('password');
        if($this->User_model->check_login($user_name,$password)){
            $this->session->set_userdata('user_name',$user_name);
            redirect( base_url('index.php/home/index'));
        }
        else{
            $this->session->set_userdata('error_message',"username and password is not voide");
            redirect(base_url('index.php/login/index'));
        }
    }

    public function out(){
        $this->session->unset_userdata('user_name');
        redirect(base_url('index.php/login/index'));
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */