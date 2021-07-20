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
    along with this program.  If not, sHow many digits to shift to get a standard readout like 20.500Cee <http://www.gnu.org/licenses/>.
    
    
    This is a PHP-CLI script made to pick up values from AtlasScientific program.
*/
// start the code
$phreader = false;
$sensor_name = "atlas";
$path = "";
$add_to_phpipe = "";
$add_to_test_avg = "";

if(!$temperature) { $temperature = 25; }

$sensor_name = (isset($sensor_name) ? $sensor_name : null);

if(!$ini_array[$sensor_name]["ATSCIPATH"]) {
	die("\nError: missing path to sensor program.");
}
else {
	$path = $ini_array[$sensor_name]["ATSCIPATH"];
}
if(!$ini_array[$sensor_name]["I2CBUS"]) { 
	die("\nError: missing path to the sensors I2C BUS.");
}

if($ini_array[$sensor_name]["SENSORREQUESTS"] > 1) {
	$add_to_phpipe = "_avg " . $ini_array[$sensor_name]["SENSORREQUESTS"] . "";
	$add_to_test_avg = "Calculated from the average of " . $ini_array[$sensor_name]["SENSORREQUESTS"] . " pollings.\n";
}

// set temperature
$temp_set_output = shell_exec("" . $path . "/atsci_ph " . $ini_array[$sensor_name]["I2CBUS"] . " temp set $temperature");
if($temp_set_output) {
	die("\nError: could not set the temperature with current value: $temperature.");
}
// get pH value
$phpipe = popen("" . $path . "/atsci_ph " . $ini_array[$sensor_name]["I2CBUS"] . " read" . $add_to_phpipe . "", "r");
$sensor_input = stream_get_contents($phpipe);
pclose($phpipe);

//$phreader_value = "" . trim($sensor_input) . "@" . $temperature . "";
$phreader_value = trim($sensor_input);

if($test) {
	print("\npHReader TEST MODE [" . $sensor_name . "]:\nCheck your data if it looks OK, disable test mode in the configuration file.\n\n");
	print("pH Value: " . $phreader_value . " @ " . $temperature . "°C\n"); // NB! Not used in script, only for testing
	print($add_to_test_avg);
	print("********************************************************************************\n");
}
else if(!$test && @$phreader_value) {
	$phreader = true;
}
else {
	die("\nError [" . $sensor_name . "]: please check the configuration file and fill out everything missing.\n\n");
}
?>
