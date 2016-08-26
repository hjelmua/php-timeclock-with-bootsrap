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
 * This module creates the current status information of the employees in
 * right area of the interface.
 */

session_start();

include 'config.inc.php';
include 'header.php';


if (! isset($_GET['printer_friendly'])) {
    if (isset($_SESSION['valid_user'])) {
        $set_logout = "1";
    }

include 'theme/templates/mainstart.inc';
    include 'topmain.php';
    include 'leftmain.php';
}


$current_page = "timeclock.php";

if (! isset($_GET['printer_friendly'])) {
    echo " <!-- debug caller 1 --> ";
}

// code to allow sorting by Name, In/Out, Date, Notes //
// Not very bootstrappy //


if ($show_display_name == "yes") {
    if (! isset($_GET['sortcolumn'])) {
        $sortcolumn = "displayname";
    } else {
        $sortcolumn = $_GET['sortcolumn'];
    }
} else {
    if (! isset($_GET['sortcolumn'])) {
        $sortcolumn = "fullname";
    } else {
        $sortcolumn = $_GET['sortcolumn'];
    }
}

if (! isset($_GET['sortdirection'])) {
    $sortdirection = "asc";
} else {
    $sortdirection = $_GET['sortdirection'];
}

if ($sortdirection == "asc") {
    $sortnewdirection = "desc";
} else {
    $sortnewdirection = "asc";
}


// determine what users, office, and/or group will be displayed on main page //
if (($display_current_users == "yes") && ($display_office == "all") && ($display_group == "all")) {
    $current_users_date = strtotime(date($datefmt));
    $calc = 86400;
    $a = $current_users_date + $calc - @$tzo;
    $b = $current_users_date - @$tzo;

    $query = "select ".$db_prefix."info.*, ".$db_prefix."employees.*, ".$db_prefix."punchlist.* from ".$db_prefix."info, ".$db_prefix."employees, ".$db_prefix."punchlist where ".$db_prefix."info.timestamp = ".$db_prefix."employees.tstamp and ".$db_prefix."info.fullname = ".$db_prefix."employees.empfullname and ".$db_prefix."info.`inout` = ".$db_prefix."punchlist.punchitems and ((".$db_prefix."info.timestamp < '".$a."') and (".$db_prefix."info.timestamp >= '".$b."')) and ".$db_prefix."employees.disabled <> '1' and ".$db_prefix."employees.empfullname <> 'admin' order by `$sortcolumn` $sortdirection";
    $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
} elseif (($display_current_users == "yes") && ($display_office != "all") && ($display_group == "all")) {
    $current_users_date = strtotime(date($datefmt));
    $calc = 86400;
    $a = $current_users_date + $calc - @$tzo;
    $b = $current_users_date - @$tzo;

    $query = "select ".$db_prefix."info.*, ".$db_prefix."employees.*, ".$db_prefix."punchlist.* from ".$db_prefix."info, ".$db_prefix."employees, ".$db_prefix."punchlist where ".$db_prefix."info.timestamp = ".$db_prefix."employees.tstamp and ".$db_prefix."info.fullname = ".$db_prefix."employees.empfullname and ".$db_prefix."info.`inout` = ".$db_prefix."punchlist.punchitems and ".$db_prefix."employees.office = '".$display_office."' and ((".$db_prefix."info.timestamp < '".$a."') and (".$db_prefix."info.timestamp >= '".$b."')) and ".$db_prefix."employees.disabled <> '1' and ".$db_prefix."employees.empfullname <> 'admin' order by `$sortcolumn` $sortdirection";
    $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
} elseif (($display_current_users == "yes") && ($display_office == "all") && ($display_group != "all")) {
    $current_users_date = strtotime(date($datefmt));
    $calc = 86400;
    $a = $current_users_date + $calc - @$tzo;
    $b = $current_users_date - @$tzo;

    $query = "select ".$db_prefix."info.*, ".$db_prefix."employees.*, ".$db_prefix."punchlist.* from ".$db_prefix."info, ".$db_prefix."employees, ".$db_prefix."punchlist where ".$db_prefix."info.timestamp = ".$db_prefix."employees.tstamp and ".$db_prefix."info.fullname = ".$db_prefix."employees.empfullname and ".$db_prefix."info.`inout` = ".$db_prefix."punchlist.punchitems and ".$db_prefix."employees.groups = '".$display_group."' and ((".$db_prefix."info.timestamp < '".$a."') and (".$db_prefix."info.timestamp >= '".$b."')) and ".$db_prefix."employees.disabled <> '1' and ".$db_prefix."employees.empfullname <> 'admin' order by `$sortcolumn` $sortdirection";
    $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
} elseif (($display_current_users == "yes") && ($display_office != "all") && ($display_group != "all")) {
    $current_users_date = strtotime(date($datefmt));
    $calc = 86400;
    $a = $current_users_date + $calc - @$tzo;
    $b = $current_users_date - @$tzo;

    $query = "select ".$db_prefix."info.*, ".$db_prefix."employees.*, ".$db_prefix."punchlist.* from ".$db_prefix."info, ".$db_prefix."employees, ".$db_prefix."punchlist where ".$db_prefix."info.timestamp = ".$db_prefix."employees.tstamp and ".$db_prefix."info.fullname = ".$db_prefix."employees.empfullname and ".$db_prefix."info.`inout` = ".$db_prefix."punchlist.punchitems and ".$db_prefix."employees.office = '".$display_office."' and ".$db_prefix."employees.groups = '".$display_group."' and ((".$db_prefix."info.timestamp < '".$a."') and (".$db_prefix."info.timestamp >= '".$b."')) and ".$db_prefix."employees.disabled <> '1' and ".$db_prefix."employees.empfullname <> 'admin' order by `$sortcolumn` $sortdirection";
    $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
} elseif (($display_current_users == "no") && ($display_office == "all") && ($display_group == "all")) {
    $query = "select ".$db_prefix."info.*, ".$db_prefix."employees.*, ".$db_prefix."punchlist.* from ".$db_prefix."info, ".$db_prefix."employees, ".$db_prefix."punchlist where ".$db_prefix."info.timestamp = ".$db_prefix."employees.tstamp and ".$db_prefix."info.fullname = ".$db_prefix."employees.empfullname and ".$db_prefix."info.`inout` = ".$db_prefix."punchlist.punchitems and ".$db_prefix."employees.disabled <> '1' and ".$db_prefix."employees.empfullname <> 'admin' order by `$sortcolumn` $sortdirection";
    $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
} elseif (($display_current_users == "no") && ($display_office != "all") && ($display_group == "all")) {
    $query = "select ".$db_prefix."info.*, ".$db_prefix."employees.*, ".$db_prefix."punchlist.* from ".$db_prefix."info, ".$db_prefix."employees, ".$db_prefix."punchlist where ".$db_prefix."info.timestamp = ".$db_prefix."employees.tstamp and ".$db_prefix."info.fullname = ".$db_prefix."employees.empfullname and ".$db_prefix."info.`inout` = ".$db_prefix."punchlist.punchitems and ".$db_prefix."employees.office = '".$display_office."' and ".$db_prefix."employees.disabled <> '1' and ".$db_prefix."employees.empfullname <> 'admin' order by `$sortcolumn` $sortdirection";
    $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
} elseif (($display_current_users == "no") && ($display_office == "all") && ($display_group != "all")) {
    $query = "select ".$db_prefix."info.*, ".$db_prefix."employees.*, ".$db_prefix."punchlist.* from ".$db_prefix."info, ".$db_prefix."employees, ".$db_prefix."punchlist where ".$db_prefix."info.timestamp = ".$db_prefix."employees.tstamp and ".$db_prefix."info.fullname = ".$db_prefix."employees.empfullname and ".$db_prefix."info.`inout` = ".$db_prefix."punchlist.punchitems and ".$db_prefix."employees.groups = '".$display_group."' and ".$db_prefix."employees.disabled <> '1' and ".$db_prefix."employees.empfullname <> 'admin' order by `$sortcolumn` $sortdirection";
    $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
} elseif (($display_current_users == "no") && ($display_office != "all") && ($display_group != "all")) {
    $query = "select ".$db_prefix."info.*, ".$db_prefix."employees.*, ".$db_prefix."punchlist.* from ".$db_prefix."info, ".$db_prefix."employees, ".$db_prefix."punchlist where ".$db_prefix."info.timestamp = ".$db_prefix."employees.tstamp and ".$db_prefix."info.fullname = ".$db_prefix."employees.empfullname and ".$db_prefix."info.`inout` = ".$db_prefix."punchlist.punchitems and ".$db_prefix."employees.office = '".$display_office."' and ".$db_prefix."employees.groups = '".$display_group."' and ".$db_prefix."employees.disabled <> '1' and ".$db_prefix."employees.empfullname <> 'admin' order by `$sortcolumn` $sortdirection";
    $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
}

$time = time();
$tclock_hour = gmdate('H',$time);
$tclock_min = gmdate('i',$time);
$tclock_sec = gmdate('s',$time);
$tclock_month = gmdate('m',$time);
$tclock_day = gmdate('d',$time);
$tclock_year = gmdate('Y',$time);
// $tclock_stamp = mktime($tclock_hour, $tclock_min, $tclock_sec, $tclock_month, $tclock_day, $tclock_year);
$tclock_stamp = time($tclock_hour, $tclock_min, $tclock_sec, $tclock_month, $tclock_day, $tclock_year);

$tclock_stamp = $tclock_stamp + @$tzo;
$tclock_time = date($timefmt, $tclock_stamp);
$tclock_date = date($datefmt, $tclock_stamp);
$report_name="Current Status Report";

echo '  <!-- start misc -->
	<section class="content-header">
	      <h1>'
	        .$report_name.
	        '<small>As of: '.$tclock_time.', '.$tclock_date.'</small>
	      </h1>
	    </section>';

// Add the current status of the employees are retrieved from the querry stored in $result
include 'display.php';


if (! isset($_GET['printer_friendly'])) {
    include 'footer.php';
    include 'theme/templates/endmain.inc';
    include 'theme/templates/footerscripts.inc';
}
?>

