<?php
class google_spreadsheet {

    private $_data;
    private $_log_enabled = FALSE;
    private $_path_log = "../log/";
    private $_spreadsheet_url = "https://spreadsheets.google.com/feeds/list/%key%/%sheet%/private/full";

    private $_parser;
    private $_spreasheet;
    private $_sheet_number;

    public function __construct($login = "", $password = "") {
        $this->_data = new stdClass();
        $this->_data->login = $login;
        $this->_data->passw = $password;
    }

    public function load_parser($parser_name){
      $class_name = ucfirst($parser_name);

      require_once "parser/".$parser_name.".php";

      $this->_parser = new $class_name;
    }

    public function set_spreadsheet($spreadsheet){
      $this->_spreasheet = $spreadsheet;
    }

    public function set_sheet_number($sheet_number){
      $this->_sheet_number = $sheet_number;
    }

    public function query() {

        $alt = "json";
        $key = $this->_spreasheet;
        $sheet = $this->_sheet_number;

        $search = array("%key%" => $key, "%sheet%" => $sheet);
        $url = str_replace(array_keys($search), array_values($search), $this->_spreadsheet_url);

        if (!empty($alt)) {
            $url .= "?alt=" . $alt;
        }

        // Construct an HTTP POST request
        $clientlogin_url = "https://www.google.com/accounts/ClientLogin";
        $clientlogin_post = array(
            "accountType" => "HOSTED_OR_GOOGLE",
            "Email" => $this->_data->login,
            "Passwd" => $this->_data->passw,
            "service" => "wise",
            "source" => "your application name"
        );

        $data = http_build_query($clientlogin_post);

        $opts = array(
          'http'=>array(
            'method'=>"POST",
            'header'=> "Content-type: application/x-www-form-urlencoded\r\n"
                . "Content-Length: " . strlen($data) . "\r\n",
            'content' => $data
          )
        );

        $context = stream_context_create($opts);

        $fp = fopen($clientlogin_url, 'r', false, $context);

        $response = "";

        while($f = fgets($fp)){
          $response .= $f;
        }

        // Get the Auth string and save it
        preg_match("/Auth=([a-z0-9_-]+)/i", $response, $matches);
        $auth = @$matches[1];

        // Include the Auth string in the headers
        // Together with the API version being used
        $headers = array(
            "Authorization: GoogleLogin auth=" . $auth,
            "GData-Version: 3.0",
        );

        $opts = array(
          'http'=>array(
            'method'=>"GET",
            'header'=> "Content-type: application/json\r\n"
                . "Authorization: GoogleLogin auth=" . $auth . "\r\n"
                . "GData-Version: 3.0\r\n",
          )
        );

        $context = stream_context_create($opts);

        $fp = fopen($url, 'r', false, $context);

        $response = "";

        while($f = fgets($fp)){
          $response .= $f;
        }

        if ($this->_log_enabled) {
            $this->_log($url, $response);
        }

        return $this->_execute_parser(json_decode($response));
    }

    private function _execute_parser($response){
      return $this->_parser->parse($response);
    }

    private function _log($url, $response) {
        $search = array(
            "http://" => "",
            "https://" => "",
            "." => "_",
            "/" => "_",
            "?" => "_",
            "=" => "_"
        );
        $log_filename = str_replace(array_keys($search), array_values($search), $url);
        file_put_contents($this->_path_log . $log_filename . ".txt", $response);
    }

}
