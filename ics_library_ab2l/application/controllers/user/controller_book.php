<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Controller_book extends CI_Controller {

	function __construct() {
        parent::__construct();
        $this->load->model('model_check_session','',TRUE);
        $this->load->helper('url');
        $this->load->library(array('form_validation','session'));
        $this->load->library('Jquery_pagination');
        $this->load->model('model_get_list');
            $this->load->model('model_book');
            $this->load->library('pagination');
    }


	public function index()
	{
		
		
	}
	public function user_reserved_list(){
		$this->load->helper(array('form','html'));
		if($this->model_check_session->check_session() == TRUE){
		
		$session_data = $this->session->userdata('logged_in');
		$acc = $session_data['username'];						
		$data['title'] = $acc." :: Reserved Books";
		$this->load->model("model_get_list");
		
		$data['result'] = $this->model_get_list->get_list($acc,"reserved",NULL,0,0);
		$data['titlepage'] = "Reserved books";
        $this->load->view("user/view_header",$data);
        $this->load->view("user/view_reserved_books",$data);
        $this->load->view("/user/view_footer");
        }else{
        //If no session, redirect to login page
            redirect('index.php/user/controller_login', 'refresh');
        }
	}
	
	public function user_borrowed_list(){
		
		if($this->model_check_session->check_session() == TRUE){
		
		$session_data = $this->session->userdata('logged_in');
		$acc = $session_data['username'];						
		$data['title'] = $acc." :: Borrowed Books";
		$data['titlepage'] = "Borrowed Books";
		$this->load->model("model_get_list");
		
		$this->load->view("user/view_header",$data);

		$data['result'] = $this->model_get_list->get_list($acc,"overdue",NULL,0,0);
        $data['message'] = "There is no overdue books!";
		$data['header'] = "List of overdue books";
        $this->load->view("user/view_borrowed_books",$data);

		$data['result'] = $this->model_get_list->get_list($acc,"borrowed",NULL,0,0);
		$data['header'] = "List of borrowed books";
		$data['message'] = "There is no borrowed books!";
        $this->load->view("user/view_borrowed_books",$data);

        $data['result'] = $this->model_get_list->get_list($acc,"returned",NULL,0,0);
        $data['message'] = "There is no returned books!";
		$data['header'] = "List of returned books";
        $this->load->view("user/view_borrowed_books",$data);
        $this->load->view("/user/view_footer");
		}else{
        //If no session, redirect to login page
            redirect('index.php/user/controller_login', 'refresh');
        }
	}

	public function cancel(){
		$res_number = $_POST['res_number'];
		$call_number = $_POST['call_number'];
		$id = $_POST['id'];
		$rank = $_POST['rank'];
		$this->load->model("model_get_list");
		$this->model_get_list->cancel_reservation($res_number);
		$this->model_get_list->update_rank($call_number);
		$this->model_get_list->update_available($call_number);
		redirect('index.php/user/controller_book/user_reserved_list');
	}

}
?>