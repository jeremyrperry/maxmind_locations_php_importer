<?php
/**
*This script contains the database settings for the importer.  Adjust as necessary.
*@link http://www.jeremyrperry.com
*@version 1.0
*@author Jeremy Perry jperry@phluant.com, jeremyrperry@gmail.com
*@package Maxmind Locations PHP Importer
*/

define('main_db_host','your_hust');
define('main_db_username','your_username');
define('main_db_password','your_password');
define('main_db_name','your_db_name');
$db = new mysqli(main_db_host,main_db_username,main_db_password,main_db_name);