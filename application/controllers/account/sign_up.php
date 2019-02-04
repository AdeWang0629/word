<?php

/*
 * Sign_up Controller
 */

class Sign_up extends CI_Controller
{

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        // Load the necessary stuff...
        $this->load->config('account/account');
        $this->load->helper(array('language', 'account/ssl', 'url'));
        $this->load->library(array('account/authentication', 'account/authorization', 'account/recaptcha', 'form_validation'));
        $this->load->model(array('account/account_model', 'account/account_details_model'));

    }

    /**
     * Account sign up
     *
     * @access public
     * @return void
     */
    function index()
    {
        // Enable SSL?
        maintain_ssl($this->config->item("ssl_enabled"));

        $user_account_id = $_POST['user_id'];
        $user_type = $_POST['user_type'];

        if ($user_type == 1) // 1 for company, 2 for user under company
            // Redirect signed in users to homepage
            if ($this->authentication->is_signed_in()) redirect('');

        $sign_up_username = mb_convert_kana($this->input->post('sign_up_username', TRUE), 'a', 'UTF-8');

        // Setup form validation
        $this->form_validation->set_error_delimiters('<span class="field_error">', '</span>');
        $this->form_validation->set_rules(array(array('field' => 'sign_up_username', 'label' => 'lang:sign_up_username', 'rules' => 'trim|required|min_length[2]|max_length[24]'), array('field' => 'sign_up_name', 'label' => 'lang:sign_up_name', 'rules' => 'trim|required|min_length[1]|max_length[24]'), array('field' => 'company_name', 'label' => 'lang:company_name', 'rules' => 'trim|required|min_length[1]|max_length[24]'), array('field' => 'sign_up_password', 'label' => 'lang:sign_up_password', 'rules' => 'trim|required|min_length[4]')));

//        $get_login_menu_type = $this->account_model->get_login_menu_type($sign_up_username);
//        if (!$get_login_menu_type) {
//            // Username / email doesn't exist
//            $login_menu_type = $_POST['login_menu_type'];
//        } else {
//            $login_menu_type_from_db = $get_login_menu_type->login_menu_type;
//            if ($login_menu_type_from_db == 0) {
//                $login_menu_type = 0;
//            } else {
//                $login_menu_type = $_POST['login_menu_type'];
//            }
//        }

        // Run form validation
        $data[] = "";
        if (($this->form_validation->run() === TRUE)) {
            // Check if user name is taken

            if ($this->username_check($sign_up_username, '', $user_account_id) === TRUE) {
                $data['message'] = "exist";
            } else {
                if ($user_type == 1)
                    $company_id = 0;
                else
                    $company_id = $_POST['company_id'];
                // Create user
                $user_id = $this->account_model->create($sign_up_username, $sign_up_username . "@domain.com", $this->input->post('sign_up_password', TRUE), $this->input->post('sign_up_name', TRUE), $user_type, $company_id, $user_account_id, $this->input->post('company_name', TRUE));


                // Add user details (auto detected country, language, timezone)
                if ($user_id) {
                    $data['message'] = "success";
                } else {
                    $data['message'] = "error";
                }
            }
        } else {
            // $data['message'] = "Form validation error";
            $data['message'] = "Form validation error";
            // echo json_encode($data);
            // $this->load->view('account/sign_up');
        }
        echo json_encode($data);
    }

    /**
     * Check if a username exist
     *
     * @access public
     * @param string
     * @return bool
     */
    function username_check($username, $receiver_name = '', $user_account_id = 0)
    {
        return $this->account_model->get_by_username($username, $receiver_name = '', $user_account_id) ? TRUE : FALSE;
    }

    /**
     * Check if an email exist
     *
     * @access public
     * @param string
     * @return bool
     */
    function email_check($email)
    {
        return $this->account_model->get_by_email($email) ? TRUE : FALSE;
    }

}


/* End of file sign_up.php */
/* Location: ./application/controllers/account/sign_up.php */
