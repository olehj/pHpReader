<?php
/*
    pHpReader - reading pH values from atsci_ph.
    Copyright (C) 2017  Ole-Henrik Jakobsen

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
    
    
    This is a PHP-CLI script made to pick up values from AtlasScientific program.
 
	Configure config.ini to choose which sensor to use.
	
	Requirements: php-cli atlas_scientific (git clone https://github.com/jvsalo/atlas_scientific.git)
	
	Last updated: 2018-03-19

*/
chdir(dirname(__FILE__));

$ini_file = "config.ini";
$ini_array = parse_ini_file($ini_file, true);

// get temperature
if(@$ini_array["tempreader"]["TEMPREADERPATH"] && @is_file("" . @$ini_array["tempreader"]["TEMPREADERPATH"] . "/tempreader.php")) {
	$argv1 = @$argv[1]; // save argv[1]
	$argv[1] = ""; // reset argv[1] - conflicts with tempreader
	$tempreadersensor = $ini_array["tempreader"]["TEMPREADERSENSOR"];
	$tempreaderserial = $ini_array["tempreader"]["TEMPREADERSENSORSERIAL"];
	
	chdir($ini_array["tempreader"]["TEMPREADERPATH"]);
	include_once("" . $ini_array["tempreader"]["TEMPREADERPATH"] . "/tempreader.php");
	$tempreader = tempreader();
	$temperature = round(@$tempreader[$tempreadersensor]["data"][$tempreaderserial], 1);
	
	// get back to current directory
	chdir(dirname(__FILE__));
	
	// restore argv[1]
	$argv[1] = $argv1;
	
	// get the config again because of tempreader
	$ini_array = parse_ini_file($ini_file, true);
}
else {
	$temperature = @$ini_array["settings"]["TEMPERATURE"];
}

// put array data into variables
$test = @$ini_array["settings"]["TEST"];
$sensor = @$ini_array["settings"]["SENSOR"];

function phreader($temperature = 25) {
	global $ini_array;
	global $sensor;
	global $test;
	
	if(!$test) { $test = 0; }
	
	// get readings from sensor
	include("sensors/" . $sensor . ".php");
	
	if($phreader) {
		return $phreader_value;
	}
	else {
		return false;
	}
}

if(!@$test && @$argc && @is_file("" . @dirname(__FILE__) . "/sensors/" . $argv[1] . ".php")) {
	$phreader_cli = phreader($temperature);
	print(@$phreader_cli); print("\n");
}
else if(!@$test) {
	die("\nError: please specify a valid pH sensor. You typed: $argv[1]\n");
}

if($test) {
	phreader($temperature);
}
?>
