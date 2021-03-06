<?php

// IMPORT LIBRARY
$dir = dirname(__FILE__);

require_once($dir."/lib/google_spreadsheet.class.php");

// SET VARS
$login          = isset($argv[1]) ? $argv[1] : "";
$password       = isset($argv[2]) ? $argv[2] : "";

# Spreedsheet key / sheet number
$spreadsheet    = "__PLACE SPREADSHEET KEY HERE__";
$sheet_number   = "default";

# Parser to use
$parser         = "parser_json";

// SET GOOLE SPREADSHEET INSTANCE
$gdoc = new google_spreadsheet($login, $password);

// LOAD PARSER
$gdoc->load_parser($parser);
$gdoc->set_spreadsheet($spreadsheet);
$gdoc->set_sheet_number($sheet_number);

$gdoc->query();
