<?php

class User extends CI_Controller {

    /**
     * Provides a data dump for Ignite Users.
     */
    public function dump($username = false) {
        if ($username != $this->session->userdata('username') || !$username)
            redirect('/');
        $this->load->model('ignite');
        $this->load->helper('file');
        $this->load->library('zip');

        $documents = $this->ignite->get_documents($username);
        foreach ($documents as $doc) {
            $id = $doc['_id']['$id'];
            $file = $doc['code'];
            if (!write_file("./dump/$username/$id.php", $file)) {
                echo "Unable to write files";
            } else {
                //echo "File Written";
            }
        }
        $this->zip->read_dir("dump/$username/", FALSE);
        $this->zip->download("$username.zip");
    }

    public function getOnline($id) {
        $document = $this->mongo_db->where(array('_id' => $id))->get('code');
        $document = $document[0];

        $online = array_unique($document['online']);
        if(isset($online)) {
            $re = '/(?!\d{1,3}\.\d{1,3}\.)\d/';
            foreach ($online as $key => $ip) {
                if ($this->input->valid_ip($ip)) {
                    $res = preg_replace($re, '*', $ip);
                    $online[$key] = $res;
                }
            }

            $this->checkOffline($id);
            $this->online($id);

            echo json_encode(array("users" => $online));
        }
    }

    public function online($id) {
        $document = $this->mongo_db->where(array('_id' => $id))->get('code');
        $document = $document[0];

        if ($this->Account_model->loggedin())
            $user = $this->Account_model->username();
        else
            $user = $this->input->ip_address();

        if (!isset($document['online'])) {
            $this->mongo_db->where(array('_id' => $id))->set('online', array(time() => $user))->update('code');
        } else {
            foreach ($document['online'] as $key => $value) {
                if (!in_array($user, $document['online'])) {
                    array_push($document['online'], $user);
                    $this->mongo_db->where(array('_id' => $id))->set('online', $document['online'])->update('code');
                }
            }
        }
    }

    public function offline($id) {
        $document = $this->mongo_db->where(array('_id' => $id))->get('code');
        $document = $document[0];

        if ($this->Account_model->loggedin())
            $user = $this->Account_model->username();
        else
            $user = $this->input->ip_address();

        foreach ($document['online'] as $key => $value) {
            if ($value == $user) {
                unset($document['online'][$key]);
                $this->mongo_db->where(array('_id' => $id))->set('online', $document['online'])->update('code');
            }
        }
    }

    public function checkOffline($id) {
        
    }

    public function setOption() {
        if ($this->Account_model->loggedin()) {
            //$this->mongo_db->where(array('username')
        }
    }

}