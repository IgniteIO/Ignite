<?php

/**
 *
 */
class Code extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('Ignite_model','ignite');
    }

    public function index($id) {
        $languages = $this->config->item('languages','ignite');
        $document = $this->ignite->get_document($id);

        if(isset($languages['Igniteable'][$document['language']])) {
            // We support this language, maybe!
            $data['code'] = $document;
            $data['output'] = $this->load->view('partials/output/'.$document['language'], $data, true);
            $data['content'] = $this->load->view('code/posted', $data, true);

            $this->load->view('layouts/default', $data);
        } else {
            // We do not support this language, to the regular view you go!
            echo "nope";
        }

        // Determine if code is runnable, if not just display regular.php
        //if($)


    }

    public function compile() {

    }

}
