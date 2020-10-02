<?php
require '../autoload.php';
error_reporting(0);
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

class deluge {
    private $url;
    private $request_id;
    private $session;

    function __construct($host, $password)  {
        $this->url = $host . (substr($host, -1) == "/" ? "" : "/") . "json";
        $this->request_id = 0;

        try {
            $response = $this->makeRequest("auth.login", array($password));
            if (gettype($response) != 'boolean' || $response != true) {
                throw new Exception("Login failed");
            }
            else {
                $response = $this->makeRequest("auth.check_session", array());
                if (gettype($response) != 'boolean' || $response != true) {
                    throw new Exception("Web api is not connected to a daemon");
                }
            }
        }
        catch (Exception $e) {
            throw new Exception("Failed to initiate deluge api: " . $e->getMessage());
        }
    }

    function get_session() {
        //check cookies folder - or make it
        if(!file_exists('./cookies/')){
            mkdir('./cookies/', 0755, true);
        }
        $return = null;
        foreach(glob("./cookies/*.txt") as $file) {
            $return .= file_get_contents($file).';';
        }
        return $return;
    }

    private function set_session($http_response_header) {
        foreach($http_response_header as $header) {
            if(substr($header, 0, 12) == 'Set-Cookie: '){
                $this->session = str_replace("Set-Cookie: ", "", $header);
            }
        }
    }

    private function makeRequest($method, $params) {
        $postdata = json_encode(array("id" => $this->request_id, "method" => $method, "params" => $params));

        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => array('Content-type: application/json','Cookie: '.$this->session."\r\n"),
                'content' => $postdata
            )
        );
        $context  = stream_context_create($opts);
        $response = file_get_contents($this->url, false, $context);
        if ($method == "auth.login")
            $this->set_session($http_response_header);
        $response_obj = json_decode($response);
        return $response_obj->result;
    }

    public function getTorrents() {
        return $this->makeRequest("webapi.get_torrents", array())->torrents;
    }

    public function getTorrentsStatus() {
        return $this->makeRequest("core.get_torrents_status", array(array(), array(), array()));
    }

}

$datas = array();
$deluge = new deluge(getenv('DOWNLOADERURL'), getenv('DOWNLOADERAUTH'));
$torrents = $deluge->getTorrents();
$torrentstatus = $deluge->getTorrentsStatus();

$key = 0;

foreach ($torrentstatus as $torrenttatus)
{
    if ((strpos($torrenttatus->state, 'Downloading') !== false) || (strpos($torrenttatus->state, 'Queued') !== false)) {
        $datas[$key] = array(
            'dname'         => substr($torrenttatus->name,0,40),
            'dstatus'         => $torrenttatus->state,
            'dsize'         => Misc::getSize($torrenttatus->total_size),
            'ddown'         => Misc::getSize($torrenttatus->download_payload_rate),
            'dseeders'         => $torrenttatus->num_seeds.'('.$torrenttatus->total_seeds.')',
            'ddownloaded'         => round($torrenttatus->progress,2)."%"
        );

        $key++;
    }
}

echo json_encode($datas);

?>