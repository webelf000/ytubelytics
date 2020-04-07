<?php

require_once("home.php"); // including home controller

/**
* class admin_config
* @category controller
*/
class member extends Home
{
    /**
    * load constructor method
    * @access public
    * @return void
    */
    public function __construct()
    {
        parent::__construct();

        if ($this->session->userdata('logged_in')!= 1) {
            redirect('home/login_page', 'location');
        }

        if ($this->session->userdata('user_type')!= 'Member') {
            redirect('home/login_page', 'location');
        }

    }

    /**
    * load index method. redirect to config
    * @access public
    * @return void
    */
    public function index()
    {
        $this->edit_profile();
    }

    /**
    * load config form method
    * @access public
    * @return void
    */
    public function edit_profile()
    {        
        $data['body'] = "member/edit_profile";
        $data['page_title'] = $this->lang->line('edit profile');
        $data["profile_info"]=$this->basic->get_data("users",array("where"=>array("id"=>$this->session->userdata("user_id"))));
        $data["currency"]=$this->get_currency_enum_values();
        $this->_viewcontroller($data);
    }

     public function get_currency_enum_values(){
        $stream_type_values=$this->basic->get_enum_values($table="users", $column="currency");
        foreach($stream_type_values as $row){
            $return_array[trim($row)]=trim($row);
        } 
        return $return_array;
    }

    /**
    * method to edit config
    * @access public
    * @return void
    */
    public function edit_profile_action()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            redirect('home/access_forbidden', 'location');
        }

        if ($_POST) 
        {
            // validation
            $this->form_validation->set_rules('name',                 '<b>'.$this->lang->line("company name").'</b>',             'trim|required|xss_clean');
            $this->form_validation->set_rules('mobile',               '<b>'.$this->lang->line("company phone/ mobile").'</b>',    'trim|required|xss_clean');
            $this->form_validation->set_rules('address',              '<b>'.$this->lang->line("company address").'</b>',          'trim|required|xss_clean');
            $this->form_validation->set_rules('website',              '<b>'.$this->lang->line("brand url").'</b>',                'trim|required|xss_clean');
            $this->form_validation->set_rules('vat_no',               '<b>'.$this->lang->line("VAT No").'</b>',                   'trim|xss_clean');
            $this->form_validation->set_rules('currency',             '<b>'.$this->lang->line("Currency").'</b>',                 'trim|xss_clean|required');
            $this->form_validation->set_rules('paypal_email',         '<b>'.$this->lang->line("PayPal Email").'</b>',             'trim|xss_clean|valid_email');
            
            if ($this->form_validation->run() == false) {
                return $this->edit_profile();
            } else {
                // assign
                $name=addslashes(strip_tags($this->input->post('name', true)));
                $mobile=addslashes(strip_tags($this->input->post('mobile', true)));
                $address=addslashes(strip_tags($this->input->post('address', true)));
                $website=addslashes(strip_tags($this->input->post('website', true)));
                $vat_no=addslashes(strip_tags($this->input->post('vat_no', true)));
                $currency=addslashes(strip_tags($this->input->post('currency', true)));
                $paypal_email=addslashes(strip_tags($this->input->post('paypal_email', true)));

                $base_path=realpath(APPPATH . '../member');

                $this->load->library('upload');

                $photo="";
                if ($_FILES['logo']['size'] != 0) {
                    $photo = $this->session->userdata("user_id").".png";
                    $config = array(
                        "allowed_types" => "png",
                        "upload_path" => $base_path,
                        "overwrite" => true,
                        "file_name" => $photo,
                        'max_size' => '200',
                        'max_width' => '600',
                        'max_height' => '300'
                        );
                    $this->upload->initialize($config);
                    $this->load->library('upload', $config);

                    if (!$this->upload->do_upload('logo')) {
                        $this->session->set_userdata('logo_error', $this->upload->display_errors());
                        return $this->edit_profile();
                    }
                }

                $update_data=array
                (
                    "name"=>$name,
                    "mobile"=>$mobile,
                    "address"=>$address,
                    "brand_url"=>$website,
                    "brand_logo"=>$photo,
                    "vat_no"=>$vat_no,
                    "currency"=>$currency,
                    "paypal_email"=>$paypal_email
                );

                $this->basic->update_data("users",array("id"=>$this->session->userdata("user_id")),$update_data);
                     
                $this->session->set_flashdata('success_message', 1);
                redirect('member/edit_profile', 'location');
            }
        }
    }
}
