maxmind_locations_php_importer
==============================

A PHP application that can import Maxmind Location data into a MySQL database.

This PHP application was created because some DB admin programs, i.e. Sequel Pro, have issues reading the format Maxmind coded the CSV file in, Shift JIS.  This probelm occured even when a table was created with the same formatting.  I created this application to be as straigtforward as possible with minimal configuration.  The steps are below.

1) Fill in the necessary database credentials on init.php.
1) Download either the Geolite or Geoip CSV information, and extract the locations file.  The file doesn't necessarily have to be placed in the application's root directory so long as the script can read the file from where it's at.
3) Erase Maxmind's copyright row, as it will cause import errors.  Save the file.
4) Adjust the table_name and csv_file values in the $settings array on approximately line 43 as necessary if you are not using the default values.  Alternatively, these settings can be changed by a corresponding get string.
5) Execute the script from either a web browser or the command line.
