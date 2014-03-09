# google-doc-spreadsheet-parser-php


## Introduction ##

Parse google doc spreadshit with PHP. Output in JSON, XML or PHP data's from google doc.

Can parse public or private spreadsheet.

For private spreadsheet, authentification (google account) required from user who can access to the document. 


## Getting Started ##

allow url fopen and extension=php_openssl.dll are require.

PHP must be in your PATH environnement.

Edit index.php :
	
	# Spreedsheet key / sheet number
    $spreadsheet    = "__PLACE SPREADSHEET KEY HERE__";
	$sheet_number   = 1;

	# Parser to use
	$parser         = "parser_json";
    

Execute import.bat to start parser or the following command line :


    php index.php %login% %password%
    

## Create custom parser ##

To create custom parser, you need to edit or create new parser file in parser folder. Sample parser is already exist in the parser directory.

### Sample parser i18n with single generated json file :

	
	# Parser class name
	class Parser_json {
	
      # CONTRUSTOR
	  public function __construct(){
	
	  }
		
	  # PARSER METHOD
	  public function parse($response){
	
	    # DATA ARRAY
	    $data = array();
	
	    foreach ($response->feed->entry as $entry) {
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

       


### Sample parser i18n with multiple generated json files :

	
	# Parser class name
	class Parser_json {
	
      # CONTRUSTOR
	  public function __construct(){
	
	  }
		
	  # PARSER METHOD
	  public function parse($response){
	
	    # DATA ARRAY
	    $data = array();
	
	    foreach ($response->feed->entry as $entry) {
	      $key = $this->_get_value($entry, 'key');
	      $value = $this->_get_value($entry, 'value');
	      $data[$key] = $value;
	    }
	
		
	    # OUTPUT FILE
	    file_put_contents("output/data-1.json", json_encode($data));
		file_put_contents("output/data-2.json", json_encode($data));
	  }
	
	  # PRIVATE METHODE TO SIMPLY DATA ACCESS FROM THE ENTRIES
	  private function _get_value($entry, $key){
	    return (string) $entry->{'gsx$'.$key}->{'$t'};
	  }
	}
       
