<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fires extends CI_Controller {

	public function get($id) { }

	public function getAll($username) {
		$fires = $this->mongo_db->where(array('user' => $username))->order_by(array('time' => 'desc'))->get('code');
		$i = 0;
		foreach($fires as $fire) {
			$fires[$i]['timestamp'] = date('F d, Y ', $fire['time']) . 'at' . date(' g:iA', $fire['time']);
			$i++;
		}

		exit(json_encode(array('fires' => $fires)));
	}

	public function delete($id) {
		// Get the Fire
		$fire = $this->mongo_db->where(array('_id' => $id))->get('code');
		$fire = $fire[0];

		// Make sure owner is owner or admin
		if($this->Account_model->username() != $fire['user'] || $this->Account_model->group($this->Account_model->username()) != 'admin')
			exit(json_encode(array('status' => 'error', 'errors' => 'You are not the owner of this fire.')));

		$this->mongo_db->where(array('_id' => $id))->delete('code');
	}

}