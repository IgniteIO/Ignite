<?php
/**
 * This class is currently in development
 *
 * @TODO: Add private methods for testing
 */
class Testing extends CI_Controller
{

    public function test() {
        if(!$this->input->is_cli_request()) {
            exit('Sorry, this is for Travis ~ <3');
        }

        $this->load->library('unit_test');

        $test = 1 + 1;
        $expected = 2;
        $name = "Addition";

        $this->unit->run($test, $expected, $name);

        $result = $this->unit->result();
        foreach($result[0] as $key => $value) {
            echo $key.': '.$value.PHP_EOL;
        }
    }

    private function _php() {

    }

}
