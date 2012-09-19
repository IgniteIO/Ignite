<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account extends CI_Controller {

	public function index() {
	
	}

	public function register() {
		$this->load->library(array('bcrypt'));

		if(empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email'])) {
			exit(json_encode(array('status' => 'error', 'message' => 'You forgot a field.')));
		} elseif($this->input->post('password', true) != $this->input->post('confirmpassword', true)) {
			exit(json_encode(array('status' => 'error', 'message' => 'Silly you, your passwords don\'t match!')));
		} else {
			if($this->accountExists() == true) exit(json_encode(array('status' => 'error', 'message' => 'Username or Email already exists in our records.')));

			$return = array(
			'status' => 'success',
			'message' => "Welcome to Ignite, we've added you to the database but you'll need to check your email to finish this process. You can continue to use our service in the meantime, and you'll be able to login to check your verification status.");

			$this->load->library(array('email', 'encrypt'));

			$validate = base64_encode($this->input->post('email', true));

			$this->email->from('hello@ignite.io', 'Ignite Staff');
			$this->email->to($this->input->post('email', true));

			$this->email->subject('Welcome to the Ignite Alpha!');
			$msg = "Hey there, thanks for signing up at Ignite! 

	To verify your account, click this link: " . base_url('account/verify/' . $validate) . "

	Thanks,
	The Ignite Team";
			$this->email->message($msg);

			$this->email->send();

			$user = array(
				'username' => $this->input->post('username'),
				'hash' => $this->bcrypt->hash($this->input->post('password', true)),
				'email' => $this->input->post('email', true),
				'ip' => $this->input->ip_address(),
				'registered' => time(),
				'group' => 'none');

			if($this->accountExists() != true) $this->mongo_db->insert('users', $user);

			$this->doLogin($this->input->post('username', true), $this->input->post('password', true));

			echo json_encode($return);
		}
	}

	public function login() {
		$this->load->library(array('encrypt'));

		if(empty($_POST['username']) || empty($_POST['password'])) {
			exit(json_encode(array('status' => 'error', 'message' => 'You forgot a field.')));
		} else {
			if($this->doLogin($this->input->post('username', true), $this->input->post('password', true)) == false) {
				exit(json_encode(array('status' => 'error', 'message' => 'Invalid login.')));
			} else {
				exit(json_encode(array('status' => 'success', 'message' => 'Logged in!')));
			}
		}
	}

	public function logout() {
		if (!$this->Account_model->loggedin())
			redirect('/');
		$this->session->sess_destroy();
	}

	public function verify() {
		$validate = base64_decode($this->uri->segment(3));
		if (!isset($validate) OR $validate == '') {
			
		} else {

			$this->mongo_db->where(array('email' => $validate))->set('group', 'users')->update('users');
			$user = $this->mongo_db->where(array('email' => $validate))->get('users');
			$user = $user[0];

			if($user['ip'] == $this->input->ip_address()) {
				$this->session->set_userdata(array('username' => $user['username'], 'logged_in' => true));
				redirect('/');
			} else {
				redirect('/');
			}

		}
		redirect('/');
	}

	private function doLogin($username, $password) {
		$this->load->library(array('bcrypt'));

		$user = $this->mongo_db->where(array('username' => $username))->get('users');
		$user = $user[0];

		if($this->bcrypt->verify($password, $user['hash'])) {
			$this->session->set_userdata(array('username' => $username, 'logged_in' => true));
			return true;
		} else {
			return false;
		}
	}

	private function accountExists() {

		// Username
		$user = $this->mongo_db->where(array('username' => $this->input->post('username', true)))->get('users');

		// Email
		$email = $this->mongo_db->where(array('email' => $this->input->post('email', true)))->get('users');

		if(isset($email[0]) || isset($user[0]))
			return true;
		else 
			return false;

	}

}