<?php
# Parser class name
class Parser_json {

    # CONTRUSTOR
  public function __construct(){

  }

  # PARSER METHOD
  public function parse($response){

    # DATA ARRAY
    $data = array();

    foreach ($response->entry as $entry) {
      $key = $this->_get_value($entry, 'key');
      $value = $this->_get_value($entry, 'value');
      $data[$key] = $value;
    }


    # OUTPUT FILE
    file_put_contents("output/data.json", json_encode($data));
  }

  # PRIVATE METHODE TO SIMPLY DATA ACCESS FROM THE ENTRIES
  private function _get_value($entry, $key){
    return (string) $entry->{'gsx$'.$key}->{'$t'};
  }
}
