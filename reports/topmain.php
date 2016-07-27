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
 * This module creates the navigation interface for the reports directory.
 */

echo "
      <!-- Top Level Reports Interface -->
      <table class=header width=100% border=0 cellpadding=0 cellspacing=1>
         <tr>";

// display the logo in top left of each page. This will be $logo you setup in config.inc.php. //
// It will also link you back to your index page. //
if ($logo == "none") {
    echo "
            <td height=35 align=left> </td>";
} else {
    echo "
            <td align=left>
               <a href='index.php'>
                  <img border=0 src='../$logo'>
               </a>
            </td>";
}

// if db is out of date, report it here //
if (($dbexists <> "1") || (@$my_dbversion <> $dbversion)) {
    echo "
            <td no class=notprint valign=middle align=left style='font-size:13;font-weight:bold;color:#AA0000'>
               <p>
                 ***Your database is out of date.*** <br />
                 &nbsp;&nbsp;&nbsp; Upgrade it via the admin section.
               </p>
            </td>";
}

// display a 'reset cookie' message if $use_client_tz = "yes" //
if ($date_link == "none") {
    if ($use_client_tz == "yes") {
        echo "
            <td class=notprint valign=middle align=right style='font-size:9px;'>
               <p>
                  If the times below appear to be an hour off, click
                  <a href='../resetcookie.php' style='font-size:9px;'>
                     here
                  </a> to reset. <br />
                  If that doesn't work, restart your web browser and reset again.
               </p>
            </td>";
    }

    echo "
            <td colspan=2 scope=col align=right valign=middle>
               <a style='color:#000000;font-family:Tahoma;font-size:10pt;text-decoration:none;'>";
} else {
    if ($use_client_tz == "yes") {
        echo "
            <td class=notprint valign=middle align=right style='font-size:9px;'>
               <p>
                  If the times below appear to be an hour off, click
                  <a href='../resetcookie.php' style='font-size:9px;'>
                     here
                  </a> to reset. <br />
                 If that doesn't work, restart your web browser and reset again.
               </p>
            </td>";
    }

    echo "
            <td colspan=2 scope=col align=right valign=middle>
               <a href='$date_link' style='color:#000000;font-family:Tahoma;font-size:10pt; text-decoration:none;'>";
}

// display today's date in top right of each page. This will link to $date_link you setup in config.inc.php. //
$todaydate=date('F j, Y');
echo "
                  $todaydate&nbsp;&nbsp;
               </a>
            </td>
         </tr>
      </table>";

// display the topbar //
echo "
      <table class=topmain_row_color width=100% border=0 cellpadding=0 cellspacing=0>
         <tr>";

// Add any logged in user
if (isset($_SESSION['valid_user'])) {
    $logged_in_user = $_SESSION['valid_user'];
    echo "
            <td align=left valign=middle width=10 style='padding-left:12px;'>
               <img src='../images/icons/user_orange.png' border='0'>
            </td>
            <td align=left valign=middle style='color:#000000;font-family:Tahoma;font-size:10pt;padding-left:8px;'>
               Logged in as: $logged_in_user
            </td>";
} else if (isset($_SESSION['time_admin_valid_user'])) {
    $logged_in_user = $_SESSION['time_admin_valid_user'];
    echo "
            <td align=left valign=middle width=10 style='padding-left:12px;'>
               <img src='../images/icons/user_red.png' border='0'>
            </td>
            <td align=left valign=middle style='color:#000000;font-family:Tahoma;font-size:10pt;padding-left:8px;'>
               Logged in as: $logged_in_user
            </td>";
} else if (isset($_SESSION['valid_reports_user'])) {
    $logged_in_user = $_SESSION['valid_reports_user'];
    echo "
            <td align=left valign=middle width=10 style='padding-left:12px;'>
               <img src='../images/icons/user_suit.png' border='0'>
            </td>
            <td align=left valign=middle style='color:#000000;font-family:Tahoma;font-size:10pt;padding-left:8px;'>
               Logged in as: $logged_in_user
            </td>";
} else if (isset($_SESSION['valid_report_employee'])) {
    $logged_in_user = $_SESSION['valid_report_employee'];
    echo "
            <td align=left valign=middle width=10 style='padding-left:12px;'>
               <img src='../images/icons/user_suit.png' border='0'>
            </td>
            <td align=left valign=middle style='color:#000000;font-family:Tahoma;font-size:10pt;padding-left:8px;'>
               Logged in as: $logged_in_user
            </td>";
}

// Add navigation interface
echo "
            <td align=right valign=middle>
               <img src='../images/icons/house.png' border='0'>&nbsp;&nbsp;
            </td>
            <td align=right valign=middle width=10>
               <a href='../index.php' style='color:#000000;font-family:Tahoma;font-size:10pt;text-decoration:none;'>
                  Home&nbsp;&nbsp;
               </a>
            </td>
            <td align=right valign=middle width=23>
               <img src='../images/icons/bricks.png' border='0'> &nbsp;&nbsp;
            </td>
            <td align=right valign=middle width=10>
               <a href='../login.php?login_action=admin' style='color:#000000;font-family:Tahoma;font-size:10pt;text-decoration:none;'>
                  Administration&nbsp;&nbsp;
               </a>
            </td>
            <td align=right valign=middle width=23>
               <img src='../images/icons/report.png' border='0' >&nbsp;&nbsp;
            </td>";

// Setup reports links
if ($use_reports_password == "yes") {
    echo "
            <td align=right valign=middle width=10>
               <a href='../login.php?login_action=reports' style='color:#000000;font-family:Tahoma;font-size:10pt; text-decoration:none;'>
                  Reports&nbsp;&nbsp;
               </a>
            </td>";
} elseif ($use_reports_password == "no") {
    echo "
            <td align=right valign=middle width=10>
               <a href='../reports/index.php' style='color:#000000;font-family:Tahoma;font-size:10pt; text-decoration:none;'>
                  Reports&nbsp;&nbsp;
               </a>
            </td>";
}

// Add a logout link if a user is logged in
if ((isset($_SESSION['valid_user'])) || (isset($_SESSION['valid_reports_user'])) || (isset($_SESSION['valid_report_employee'])) || (isset($_SESSION['time_admin_valid_user']))) {
    echo "
            <td align=right valign=middle width=20>
               <img src='../images/icons/arrow_rotate_clockwise.png' border='0'> &nbsp;
            </td>
            <td align=right valign=middle width=10>
               <a href='../logout.php' style='color:#000000;font-family:Tahoma;font-size:10pt;text-decoration:none;'>
                  Logout&nbsp;&nbsp;
               </a>
            </td>";
}

echo "
         </tr>
      </table>";
?>
