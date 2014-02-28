<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Controller_view_users extends CI_Controller {
 
    function index() {
    	$this->viewUser(null);
    }

    function viewUser($msg){
        $this->load->model('model_users');
        $data['results']=$this->model_users->getAllUsers();
        $data['parent'] = "Users";
        $data['current'] = "View Users";
        if($msg != null)
            $data['message'] = "You have successfully approved the account of a user with account_number:".$msg;

        $this->load->helper(array('form','html'));
        $this->load->view("admin/view_header",$data);
        $this->load->view("admin/view_aside");
        $this->load->view("admin/view_users",$data);
        $this->load->view("admin/view_footer");
    }

    function search_user(){
        $this->load->model('model_users');
        $data['results']=$this->model_users->userSearch($this->input->post('s_user'));
        $data['parent'] = "Users";
        $data['current'] = "Search Users";

        $this->load->helper(array('form','html'));
        $this->load->view("admin/view_header",$data);
        $this->load->view("admin/view_aside");
        $this->load->view("admin/view_users",$data);
        $this->load->view("admin/view_footer");
    }

    function approve_user(){
        if($this->session->userdata('logged_in_type')!="admin")
                redirect('index.php/user/controller_login', 'refresh');
         if(isset($_POST['approve'])){
             if(isset($_POST['account_number1'])){
                 $this->load->model('model_user');
                 $this->model_user->approve_user($_POST['account_number1']);
                 $this->email_confirm_account($_POST['account_number1']);
                 $account_num = $_POST['account_number1'];
                 $message = $account_num;
                 $this->call_confirm($message);
             }
             unset($_POST['approve']);
         }
     }
 
     function call_confirm($msg){
         echo "<script>alert('You have confirmed the account of $msg')</script>";
         redirect('index.php/admin/controller_view_users/viewUser/'.$msg,'refresh');
     }
 
     function remove_user(){
        if($this->session->userdata('logged_in_type')!="admin")
                redirect('index.php/user/controller_login', 'refresh');
         if(isset($_POST['remove2'])){
             if(isset($_POST['account_number2'])){
                 $this->model_user->remove_user($_POST['account_number2']);
             }
             unset($_POST['remove2']);
         }
         else if(isset($_POST['remove3'])){
             if(isset($_POST['account_number3'])){
                 $this->model_user->remove_user($_POST['account_number3']);  
             }
             unset($_POST['remove3']);
         }
     }
 
     function email_confirm_account($account_number){  
        if($this->session->userdata('logged_in_type')!="admin")
            redirect('index.php/user/controller_login', 'refresh');
         
         $config = array(
         'protocol'  => 'smtp',
         'smtp_host' => 'ssl://smtp.googlemail.com',
         'smtp_port' => 465, //25
         'smtp_user' => 'samplemail128@gmail.com',
         'smtp_pass' => 'cmsc128ab2l',
         'mailtype'  => 'html', 
         'charset'   => 'utf-8',
         'wordwrap'  => true,
         'newline'   => "\r\n",
         'crlf'      => "\n"
         );//config for the email
         $subject='Account Approval';
         $from_email='samplemail128@gmail.com';
         $from_name='Sample ICS Library';
         
         //Get user account in database
         $this->load->model('model_user');
         $query['query'] = $this->model_user->get_acct($account_number);
         $first_name= $query['query'][0]->first_name;
         $mi=$query['query'][0]->middle_initial;
         $last_name=$query['query'][0]->last_name;
         $to=$query['query'][0]->email;
 
         $message = "Dear {$first_name} {$mi}. {$last_name},<br/>";
         $message .= "&nbsp;Your account has been approved. ";
         $message .= "Please maximize the use of the site for your needs.<br/>";
         $message .= "&nbsp;For inquiries, please contact the ICS Library librarian.<br/>";
         $message .= "&nbsp;Thank you!<br/>";
         $message .= "&nbsp;ICS Library Administrator";
 
         $this->load->library('email', $config);
         $this->email->set_newline("\r\n");
         $this->email->from($from_email, $from_name);
         $this->email->to($to); 
         $this->email->subject($subject);
         $this->email->message($message);
         //Send the email
         if($this->email->send()){
             $this->load->view("admin/view_success_validate_user");
             //edit parameters of add_log to the specific function that your function is doing
             //first parameter: message
             //second parameter: type
             $this->add_log("Admin 1 verified account of $account_number", "Verify User");
         }
        }
    function borrow($borrower){
        if($this->session->userdata('logged_in_type')!="admin")
            redirect('index.php/user/controller_login', 'refresh');
        $arr = array(
            'borrower' => $borrower
            );
        $this->session->set_userdata($arr);
        redirect('index.php/admin/controller_search_book');
    }
}
/* End of file home_controller.php */
/* Location: ./application/controllers/user/controller_home.php */