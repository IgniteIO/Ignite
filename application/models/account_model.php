<?php

class Account_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function loggedin() {
        if ($this->session->userdata('logged_in')) {
            return true;
        } else {
            return false;
        }
    }

    function username() {
        return $this->session->userdata('username');
    }

    function clicks($username) {
        $urls = $this->urls($username);

        $clicks = 0;

        foreach ($urls as $url) {
            $clicks = $clicks + $url['clicks'];
        }

        return $clicks;
    }

    function isAdmin() {
        $user = $this->mongo_db->where(array('username' => $this->session->userdata('username')))->get('users');
        $user = $user[0];
        if ($user['group'] == 'admin') {
            return true;
        } else {
            return false;
        }
    }

    function detail($username, $detail) {
        $user = $this->mongo_db->where(array('username' => $username))->get('users');
        $user = $user[0];

        return $user[$detail];
    }

    function info($username) {
        $user = $this->mongo_db->where(array('username' => $username))->get('users');
        $user = $user[0];

        return $user;
    }

    function getFrom($collection, $username) {
        $content = $this->mongo_db->order_by(array('date' => 'asc'))->where(array('username' => $username))->get($collection);

        return $content;
    }

    function isOnline($username) {
        if ($this->mongo_db->where(array('username' => $username))->get('online')) {
            return true;
        } else {
            return false;
        }
    }

    function markOnline($username, $url = '') {
        $online = $this->mongo_db->get('online');
        if (empty($online)) {
            $this->mongo_db->insert('online', array('username' => $username, 'time' => time(), 'url' => $url));
        } else {
            foreach ($online as $user) {
                if ($this->mongo_db->where(array('username' => $username))->get('online')) {
                    //set a new time
                    $this->mongo_db->where(array('username' => $username))->set(array('time' => time(), 'url' => $url))->update('online');
                } else {
                    // insert row
                    $this->mongo_db->insert('online', array('username' => $username, 'time' => time(), 'url' => $url));
                }
            }
        }
    }

}