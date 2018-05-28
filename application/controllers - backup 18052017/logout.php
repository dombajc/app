<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of logout
 *
 * @author webprog
 */
class logout extends CI_Controller {
    //put your code here
    function index(){
        $this->session->unset_userdata('id_user');
        $this->session->unset_userdata('status_login');
        redirect('login');
    }
}

?>
