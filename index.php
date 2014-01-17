<?php
/**
*This script contains the logic for importing Maxmind's Location CSV file into a MySQL database as a remedy for the file having formatting issues with some db admin applications.
*@link http://www.jeremyrperry.com
*@version 1.0
*@author Jeremy Perry jperry@phluant.com, jeremyrperry@gmail.com
*@package Maxmind Locations PHP Importer
*/

//Includes the database credentials, including a mysqli object instance.
require_once('init.php');

/**
*The runSql function runs the bulk query.  If a database error occurs, it will terminate the script execution.  It will call up the necessary global variables.
*@param array $insert
*@return an emptied $insert array
*/
function runSql($insert){
	//The db global is imported
	global $db;
	//sql statement is initialized
	$sql = "insert into ".$GLOBALS['settings']['table_name']." (".implode(',', $GLOBALS['header']).") values";
	//A count of the $insert array is taken, then the array is looped through to add to the SQL statement
	$iCount = count($insert);
	for($i=0; $i<$iCount; $i++){
		$sql .= $insert[$i];
		if($i < $iCount-1){
			$sql .= ",";
		}
	}
	//The script will terminate if an error occurs and will print out both the failed query and error message.
	if(!$db->query($sql)){
		echo 'failed query is '.$sql.'<br />';
		echo $db->error."<br />";
		die();
	}
	else{
		return empty($insert);
	}
}

//The initial settings.  These values can be changed from within the code or altered by a corresponding get string.
$settings = array(
	'table_name'=>'geolocation_cities_test',
	'csv_file'=>'GeoLiteCity-Location.csv'
);
foreach($settings as $s=>$ss){
	if(isset($_GET[$s])){
		$settings[$s] = $_GET[$s];
	}
}

//Table is truncated.
$db->query('truncate table '.$settings['table_name']);
//CSV attributes are set up.
ini_set('auto_detect_line_endings',true);
$file = fopen($settings['csv_file'],"r");
//Other necessary values are declared.
$pastFirst = false;
$header = array();
$count = 1;
$insert = array();
//The CSV file is looped through.
while(!feof($file)){
	//CSV values are taken out
	$csv = fgetcsv($file);
	//Conditional for needing to extract the header information
	if(!$pastFirst){
		for($h=1; $h<count($csv); $h++){
			if($h > 1){
				$ins .= ",";
			}
			$header[] = $csv[$h];
		}
		//Boolean is set to true for subsequent loops.
		$pastFirst = true;
	}
	else{
		//Each line is prepared as an SQL bulk insert statement and inserted into the $insert array.
		$ins = '(';
		for($h=1; $h<=count($header); $h++){
			if($h > 1){
				$ins .= ",";
			}
			$ins .= "'".$db->real_escape_string($csv[$h])."'";
		}
		$ins .= ')';
		$insert[] = $ins;
		//Once the count reaches the determined amount, the runSql function is called up.  This value can be changed.
		if($count == 10000){
			$insert = runSql($insert);
			$count = 1;
		}
		else{
			$count++;
		}
	}	
}
//All remaining values after the loop through are run as a query.
if(!empty($insert)){
	$insert = runSql($insert);
}
//A complete message is printed off to the user.
echo 'Complete<br />';