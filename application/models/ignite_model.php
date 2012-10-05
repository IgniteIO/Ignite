<?php

/**
 * Handles basic Ignite database interactions
 *
 * @author clone1018
 */
class Ignite_model extends CI_Model {

    /**
     * Model Constructor
     */
    function __construct() {
        parent::__construct();
    }


    /**
     * Retrieves a users documents
     *
     * @param string $username
     * @return array
     */
    function get_documents($username = '') {
        if(!$username) $documents = $this->mongo_db->get('code');
        else $documents = $this->mongo_db->where(array('user' => $username))->get('code');
        
        return $documents;
    }
    
    /**
     * Gets specific documents from the database.
     * 
     * @param string $id
     * @return array
     */
    function get_document($id) {
        $document = $this->mongo_db->where(array('_id' => $id))->get('code');
        $document = $document[0];
        
        return $document;
    }
    
}
