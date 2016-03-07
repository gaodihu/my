<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Guideline extends CI_Controller {

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
	public function index()
	{  
        $this->load->model('applications_model');
        $this->load->model('information_model');
        $data['title'] ='MyLED Guideline and LED Lighting Authority';
        $data['description'] ='MyLED will keep updating the information and guides about LED information, LED guides, LED standards, LED design, for LED designers.';
        $data['keywords'] ='LED information, LED guides, LED standards, LED design, for LED designers.';
        $breadcrumbs[] =array(
            'text' =>'Guideline',
            'href'  =>"/guideline/",
            'sep'  =>false
        );
        $data['breadcrumbs'] =$breadcrumbs;
        $data['all_app_info'] =$this->applications_model->get_all_applications();
        $data['information_info'] =$this->information_model->get_information_info(1);
        $this->load->view('common/header',$data);
        $this->load->view('common/breadcrumb');
		$this->load->view('guideline');
        $this->load->view('common/footer');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */