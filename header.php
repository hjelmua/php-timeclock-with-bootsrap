<?php
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

/**
 * This module creates the regular header for the interface.
 */

include 'functions.php';

ob_start();
echo "<html>";

// grab the connecting IP address. //
$connecting_ip = get_ipaddress();
if (empty($connecting_ip)) {
    return FALSE;
}

// determine if connecting IP address is allowed to connect to PHP Timeclock //
if ($restrict_ips == "yes") {
    for ($x = 0; $x < count($allowed_networks); $x++) {
        $is_allowed = ip_range($allowed_networks[$x], $connecting_ip);
        if (! empty($is_allowed)) {
            $allowed = TRUE;
        }
    }
    if (! isset($allowed)) {
        echo "You are not authorized to view this page.";
        exit;
    }
}

// connect to db anc check for correct db version //
if ($use_persistent_connection == "yes") {
    @ $db = mysql_pconnect($db_hostname, $db_username, $db_password);
} else {
    @ $db = mysql_connect($db_hostname, $db_username, $db_password);
}
if (! $db) {
    echo "Error: Could not connect to the database. Please try again later.";
    exit;
}
mysql_select_db($db_name);

$table = "dbversion";
$result = mysql_query("SHOW TABLES LIKE '".$db_prefix.$table."'");
@$rows = mysql_num_rows($result);
if ($rows == "1") {
    $dbexists = "1";
} else {
    $dbexists = "0";
}

$db_version_result = mysql_query("select * from ".$db_prefix."dbversion");
while (@$row = mysql_fetch_array($db_version_result)) {
    @$my_dbversion = "".$row["dbversion"]."";
}

// include css and timezone offset//
if (($use_client_tz == "yes") && ($use_server_tz == "yes")) {
    $use_client_tz = '$use_client_tz';
    $use_server_tz = '$use_server_tz';
    echo "Please reconfigure your config.inc.php file, you cannot have both $use_client_tz AND $use_server_tz set to 'yes'";
    exit;
}

echo "
   <head>";
if ($use_client_tz == "yes") {
    if (! isset($_COOKIE['tzoffset'])) {
        include 'tzoffset.php';
        echo "
      <meta http-equiv='refresh' content='0;URL=timeclock.php'>";
    }
}

echo "
      <link rel='stylesheet' type='text/css' media='screen' href='css/default.css' />
      <link rel='stylesheet' type='text/css' media='print' href='css/print.css' />";

// set refresh rate for each page //
if ($refresh == "none") {
    echo "
   </head>

   <body>";
} else {
    echo "
      <meta http-equiv='refresh' content=\"$refresh;URL=timeclock.php\">
      <script language=\"javascript\" src=\"scripts/pnguin_timeclock.js\">
      </script>
   </head>

   <body>";
}

// Set timezone information
if ($use_client_tz == "yes") {
    if (isset($_COOKIE['tzoffset'])) {
        $tzo = $_COOKIE['tzoffset'];
        settype($tzo, "integer");
        $tzo = $tzo * 60;
    }
} elseif ($use_server_tz == "yes") {
    $tzo = date('Z');
} else {
    $tzo = "1";
}
?>
