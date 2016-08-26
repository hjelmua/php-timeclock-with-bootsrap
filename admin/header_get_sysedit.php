<?php
echo '<html>';

/***************************************************************************
 *   Copyright (C) 2006 by Ken Papizan                                     *
 *   Copyright (C) 2008 by phpTimeClock Team                               *
 *   http://sourceforge.net/projects/phptimeclock                          *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU General Public License as published by  *
 *   the Free Software Foundation; either version 2 of the License, or     *
 *   (at your option) any later version.                                   *
 *                                                                         *
 *   This program is distributed in the hope that it will be useful,       *
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of        *
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         *
 *   GNU General Public License for more details.                          *
 *                                                                         *
 *   You should have received a copy of the GNU General Public License     *
 *   along with this program; if not, write to the                         *
 *   Free Software Foundation, Inc.,                                       *
 *   51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA.             *
 ***************************************************************************/

include '../functions.php';

// grab the connecting ip address. //

$connecting_ip = get_ipaddress();
if (empty($connecting_ip)) {
    return FALSE;
}

// determine if connecting ip address is allowed to connect to PHP Timeclock //

if ($restrict_ips == "yes") {
  for ($x=0; $x<count($allowed_networks); $x++) {
    $is_allowed = ip_range($allowed_networks[$x], $connecting_ip);
    if (!empty($is_allowed)) {
      $allowed = TRUE;
    }
  }
  if (!isset($allowed)) {
    echo "You are not authorized to view this page."; exit;
  }
}

// check for correct db version //

if ($use_persistent_connection == "yes") {
    @ $db = ($GLOBALS["___mysqli_ston"] = mysqli_connect($db_hostname,  $db_username,  $db_password));
} else {
    @ $db = ($GLOBALS["___mysqli_ston"] = mysqli_connect($db_hostname,  $db_username,  $db_password));
}
if (!$db) {echo "Error: Could not connect to the database. Please try again later."; exit;}
((bool)mysqli_query($GLOBALS["___mysqli_ston"], "USE " . $db_name));

$table = "dbversion";
$result = mysqli_query($GLOBALS["___mysqli_ston"], "SHOW TABLES LIKE '".$db_prefix.$table."'");
@$rows = mysqli_num_rows($result);
if ($rows == "1") {
$dbexists = "1";
} else {
$dbexists = "0";
}

$db_version_result = mysqli_query($GLOBALS["___mysqli_ston"], "select * from ".$db_prefix."dbversion");
while (@$row = mysqli_fetch_array($db_version_result)) {
  @$my_dbversion = "".$row["dbversion"]."";
}

// include css and timezone offset//

if (($use_client_tz == "yes") && ($use_server_tz == "yes")) {

$use_client_tz = '$use_client_tz';
$use_server_tz = '$use_server_tz';
echo "Please reconfigure your config.inc.php file, you cannot have both $use_client_tz AND $use_server_tz set to 'yes'"; exit;}

echo "<head>\n";
if ($use_client_tz == "yes") {
if (!isset($_COOKIE['tzoffset'])) {
include '../tzoffset.php';
echo "<meta http-equiv='refresh' content='0;URL=index.php'>\n";}}
include '../theme/templates/adminheader.inc';
echo "<link rel='stylesheet' type='text/css' media='print' href='../css/print.css' />\n";
echo "<script type=\"text/javascript\" src=\"../scripts/CalendarPopup.js\"></script>\n";
echo "<script language=\"javascript\">document.write(getCalendarStyles());</script>\n";
echo "<script language=\"javascript\" src=\"../scripts/pnguin.js\"></script>\n";
include '../scripts/dropdown_get_sysedit.php';
echo "</head>\n";

if ($use_client_tz == "yes") {
if (isset($_COOKIE['tzoffset'])) {
$tzo = $_COOKIE['tzoffset'];
settype($tzo, "integer");
$tzo = $tzo * 60;}
} elseif ($use_server_tz == "yes") {
  $tzo = date('Z');
} else {
  $tzo = "1";}
//echo "<body>\n";
// echo "<body onload='office_names();'>\n";
echo '<body class="hold-transition skin-blue-light" sidebar-mini onload="office_names();">

<div id="wrapper">';
?>
