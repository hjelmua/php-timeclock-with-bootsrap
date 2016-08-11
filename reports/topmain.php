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
 * This module creates the navigation interface for the top level directory.
 */



echo '
<!-- Main Header -->
  <header class="main-header">';

  // display the logo in top left of each page. This will be $logo you setup in config.inc.php. //
  // It will also link you back to your index page. //
echo '    <!-- Logo -->
    <a href="index.php" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>A</b><i class="fa fa-clock-o"></i></span>
      <!-- logo for regular state and mobile devices -->';
if ($logo == "none") {     echo '<span class="logo-lg"><b>PHP</b> <i class="fa fa-clock-o"></i> Timeclock</span></a>'; }
else { echo "<span class='logo-lg'><img border='0' src='$logo'></span></a>"; }

include '../theme/templates/topnavpart1.inc';

// if db is out of date, report it here //
if (($dbexists <> "1") || (@$my_dbversion <> $dbversion)) {
    echo "
                 <li><a href=#>***Your database is out of date.*** 
                 &nbsp;&nbsp;&nbsp; Upgrade it via the admin section.</a></li>";
}



// display a 'reset cookie' message if $use_client_tz = "yes" //
if ($date_link == "none") {
    if ($use_client_tz == "yes") {
        echo "
            
                  If the times below appear to be an hour off, click
                  <a href='../resetcookie.php'>
                     here
                  </a> to reset. <br />
                  If that doesn't work, restart your web browser and reset again.";
    }


} else {
    if ($use_client_tz == "yes") {
        echo "
            
               
                  If the times below appear to be an hour off, click
                  <a href='../resetcookie.php'>
                     here
                  </a> to reset. 
                 If that doesn't work, restart your web browser and reset again.";
    }



}

// display today's date in top right of each page. This will link to $date_link you setup in config.inc.php. //
$todaydate=date('F j, Y');
echo "
<li><a href='$date_link'>$todaydate</a></li>
";

// display the topbar //


/* moved to leftmain 
if (isset($_SESSION['valid_user'])) {
    $logged_in_user = $_SESSION['valid_user'];
    echo "
            
               <li><a href='login.php'><i class='fa fa-user-secret text-orange'></i> Logged in as: $logged_in_user</a></li>";
} else if (isset($_SESSION['time_admin_valid_user'])) {
    $logged_in_user = $_SESSION['time_admin_valid_user'];
    echo "
            
               <li><a href='login.php'><i class='fa fa-user-secret text-red'></i> Logged in as: $logged_in_user</a></li>";
} else if (isset($_SESSION['valid_reports_user'])) {
    $logged_in_user = $_SESSION['valid_reports_user'];
    echo "
            
               <li><a href='login.php'><i class='fa fa-user-plus'></i> Logged in as: $logged_in_user</a></li>";
} else if (isset($_SESSION['valid_report_employee'])) {
    $logged_in_user = $_SESSION['valid_report_employee'];
    echo "
            
               <li><a href='login.php'><i class='fa fa-user'></i> Logged in as: $logged_in_user</a></li>";
}

end moved to leftmain  */

echo "
               <li><a href='../index.php'><i class='fa fa-home'></i> Home</a></li>
            
               <li><a href='../login.php?login_action=admin'><i class='fa fa-globe'></i> Administration</a></li>";

if ($use_reports_password == "yes") {
    echo "
            
               <li><a href='../login.php?login_action=reports'><i class='fa fa-globe'></i> Reports</a></li>";
} elseif ($use_reports_password == "no") {
    echo " <li><a href='../reports/index.php'><i class='fa fa-list-alt'></i> Reports</a></li>";
}

if ((isset($_SESSION['valid_user'])) || (isset($_SESSION['valid_reports_user'])) || (isset($_SESSION['valid_report_employee'])) || (isset($_SESSION['time_admin_valid_user']))) {
    echo " <li><a href='../logout.php'><i class='fa fa-sign-out'></i>Logout</a></li>";
}

include '../theme/templates/topnavpart2.inc'
?>
