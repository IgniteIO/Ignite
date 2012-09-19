<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Code extends CI_Controller {

    public function index() {
        if (!$this->uri->segment(2))
            redirect('/');

        $code = $this->mongo_db->where(array('_id' => $this->uri->segment(2)))->get('code');
        $code = $code[0];

        $data['language'] = $code['language'];
        $data['code'] = $code;
        if (!isset($code['realtime']))
            $data['code']['realtime'] = true;
        $data['content'] = $this->load->view('code/posted', $data, true);

        $this->load->view('layouts/default', $data);
    }

    public function download($id) {
        $this->load->helper('download');

        $file = $this->mongo_db->where(array('_id' => $id))->get('code');
        $file = $file[0];

        $this->output->set_content_type($file['language']);
        force_download($id . '.' . $file['language'], $file['code']);
    }

    /**
     * Returns an embed object
     * 
     * @param string $id
     */
    public function embed($id) {
        if (empty($id))
            exit;
        header("Content-type: application/javascript");
        $this->load->model('ignite');
        $this->load->helper('file');

        $data['doc'] = $this->ignite->get_document($id);
        //$data['output'] = $this->compile($data['doc']['code'], $data['doc']['language'], true);

        echo "document.write(" . json_encode($this->load->view('code/embed', $data, true)) . ")";
    }

    public function commit() {
        if (empty($_POST['code']) || empty($_POST['language'])) {
            exit(json_encode(array('status' => 'error', 'message' => 'Something wasn\'t submitted.')));
        } else {
            if ($this->Account_model->loggedin())
                $user = $this->Account_model->username();
            else
                $user = null;

            $commit = array(
                'user' => $user,
                'code' => urldecode($this->input->post('code')),
                'language' => $this->input->post('language'),
                'time' => time(),
                'name' => $this->input->post('name'),
                'realtime' => $this->input->post('realtime'));

            if($commit['realtime'] && !$this->config->item('live', 'ignite')) {
                $commit['realtime'] = FALSE;
            }

            if ($this->input->post('id')) {
                $commit['parent'] = $this->input->post('id');
            }

            $id = $this->mongo_db->insert('code', $commit);
            $id = json_encode($id);
            $id = json_decode($id, true);

            echo(json_encode(array('status' => 'success', 'id' => $id['$id'], 'code' => $this->input->post('code'))));
        }
    }

    public function compile($code = false, $language = false, $return = false) {
        if (!$code)
            $code = $this->input->post('code');
        if (!$language)
            $language = $this->input->post('language');

        if ($language == 'php') {
            return $this->php($code, $return);
        } elseif ($language == 'python') {
            $this->python($code, $return);
        } elseif ($language == 'ruby') {
            $this->ruby($code, $return);
        } elseif ($language == 'javascript' || $language == 'nodejs') {
            $this->javascript($code, $return);
        } else {
            exit(json_encode(array('status' => 'error', 'message' => 'This language can\'t be ran, sorry!')));
        }
    }

    private function php($code, $return = false) {
        //error_reporting(0);
        //$this->load->library('safereval');

        $code = urldecode($code);

        //$newLine = (PHP_EOL > "\r\n" ? "\r\n" : "\n");
        //$explodedCode = explode($newLine, $code);
        $line = preg_split('#(\r?\n|\r)#', $code);

        if (strpos($line[0], '<?php') !== false) {
            //$explodedCode[0] = str_replace(array("<?php", "<?"), '', $explodedCode[0]);
            //$code = implode($newLine, $explodedCode);
            //print_r($code);
            //$code = preg_replace('/^.+\n/', '', $code);
            //$code = preg_replace('/^.+<\?(php)?/i', '', $code, 1);
            //$code = preg_replace('/^.+?<\?(php)?\s*/is', '', $code, -1);
            unset($line[0]);
            $code = implode($line, PHP_EOL);

            $fields_string = '';

            $url = 'http://code.ignite.io/compile.php';
            $fields = array(
                'code' => urlencode($code),
                'language' => urlencode("php")
            );

            //url-ify the data for the POST
            foreach ($fields as $key => $value) {
                $fields_string .= $key . '=' . $value . '&';
            }
            rtrim($fields_string, '&');

            //open connection
            $ch = curl_init();

            //set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, count($fields));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

            ob_start();

            $result = curl_exec($ch);
            $output = ob_get_contents();

            ob_end_clean();
            $output = explode('<BREAK>', $output);
            //var_dump($output);
            if (isset($output[1]))
                if ($return)
                    return json_decode($output[1], true); else
                    echo $output[1];
            else
            if ($return)
                return json_decode($output[0], true); else
                echo $output[0];

            //close connection
            curl_close($ch);
        } else {
            exit(json_encode(array('status' => 'error', 'message' => 'No PHP was submitted, did you forget <?php or <? ?')));
        }
    }

    private function python($code, $return = false) {
        $url = "http://eval.appspot.com/eval?statement=";

        $output = htmlspecialchars($this->getContents($url . $code));

        exit(json_encode(array('status' => 'success', 'output' => $output, 'errors' => null)));
    }

    public function ruby($code, $return = false) {
        $code = urldecode($code);
        $fields_string = '';

        $url = 'http://rubyfiddle.com/plays/run';
        $fields = array(
            'utf8' => urlencode("&#x2713;"),
            'authenticity_token' => urlencode("370snieb6vz+7I8BjMHTyIyC5+wTWnzTP7d8BM9iDVM="),
            'riddle[code]' => urlencode($code)
        );

        //url-ify the data for the POST
        foreach ($fields as $key => $value) {
            $fields_string .= $key . '=' . $value . '&';
        }
        rtrim($fields_string, '&');

        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

        //execute post
        ob_start();

        $result = curl_exec($ch);
        $output = ob_get_contents();

        ob_end_clean();
        $output = str_replace(array('$("#riddle_result").html("', '");', '$("#riddle_result").parent().parent().effect("highlight", {}, 1000);'), '', $output);
        $output = str_replace(array('\n', '\\'), '', $output);
        $output = str_replace('\\/', '/', $output);
        preg_match("/<p>(.+?)<\/p>/is", $output, $matches);
        if ($matches[1] == 'Thanks for trying out RubyFiddle')
            $matches[1] = null;


        //close connection
        curl_close($ch);

        exit(json_encode(array('status' => 'success', 'output' => $matches[1], 'errors' => null)));
    }

    public function javascript($code) {
        $code = urldecode($code);
        $fields_string = '';

        $url = 'http://code.ignite.io:1337/compile';
        $fields = array(
            'code' => urlencode($code)
        );

        //url-ify the data for the POST
        foreach ($fields as $key => $value) {
            $fields_string .= $key . '=' . $value . '&';
        }
        rtrim($fields_string, '&');

        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

        //execute post
        ob_start();

        $result = curl_exec($ch);
        $output = ob_get_contents();

        ob_end_clean();
        $output = json_decode($output, true);

        // Let's parse the return
        if($output['result'] == null || $output['result'] == "null") {
            $output['result']  = implode(PHP_EOL, $output['console']);
            $output['console'] = '';
        }

        //close connection
        curl_close($ch);

        exit(json_encode(array('status' => 'success', 'output' => $output['result'], 'errors' => $output['console'])));
    }

    private function getContents($url) {

        $ch = curl_init();
        $timeout = 30;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    private function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    private function addSlashes($s) {

        $o = "";
        $l = strlen($s);
        for ($i = 0; $i < $l; $i++) {
            $c = $s[$i];
            switch ($c) {
                case '<': $o.='\\x3C';
                    break;
                case '>': $o.='\\x3E';
                    break;
                case '\'': $o.='\\\'';
                    break;
                case '\\': $o.='\\\\';
                    break;
                case '"': $o.='\\"';
                    break;
                case "\n": $o.='\\n';
                    break;
                case "\r": $o.='\\r';
                    break;
                default:
                    $o.=$c;
            }
        }
        return $o;
    }

}
