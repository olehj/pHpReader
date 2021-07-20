<?php
/*
    phppHReader - reading pH values from atsci_ph and put them in arrays.
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
    
    
    This is a dummy script for testing purpose.
*/
// start the code
$phreader = false;
$sensor_name = "dummy";

$sensor_name = (isset($sensor_name) ? $sensor_name : null);

$phreader_value = "7.01";

if($test) {
	print("\npHReader TEST MODE [" . $sensor_name . "]:\nCheck your data if it looks OK, disable test mode in the configuration file.\n\n");
	print("pH Value: " . $phreader_value . "\n"); // NB! Not used in script, only for testing
	print("********************************************************************************\n");
}
else if(!$test && @$phreader_value) {
	$phreader = true;
}
else {
	die("\nError [" . $sensor_name . "]: please check the configuration file and fill out everything missing.\n\n");
}
?>
