<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

    /**
     * Serves the homepage
     */
    public function index() {

		$data['content'] = $this->load->view('code/new', null, true);

		$this->load->view('layouts/default', $data);
	}
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */
