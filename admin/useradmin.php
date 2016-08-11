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

session_start();

include '../config.inc.php';
include 'header.php';
include 'topmain.php';
include 'leftmain.php';
echo "<title>$title - User Summary</title>\n";

$self = $_SERVER['PHP_SELF'];
$request = $_SERVER['REQUEST_METHOD'];
$user_agent = $_SERVER['HTTP_USER_AGENT'];

if (!isset($_SESSION['valid_user'])) {

echo "<table width=100% border=0 cellpadding=7 cellspacing=1>\n";
echo "  <tr class=right_main_text><td height=10 align=center valign=top scope=row class=title_underline>PHP Timeclock Administration</td></tr>\n";
echo "  <tr class=right_main_text>\n";
echo "    <td align=center valign=top scope=row>\n";
echo "      <table width=200 border=0 cellpadding=5 cellspacing=0>\n";
echo "        <tr class=right_main_text><td align=center>You are not presently logged in, or do not have permission to view this page.</td></tr>\n";
echo "        <tr class=right_main_text><td align=center>Click <a class=admin_headings href='../login.php?login_action=admin'><u>here</u></a> to login.</td></tr>\n";
echo "      </table><br /></td></tr></table>\n"; exit;
}

/* moved
echo "<table width=100% height=89% border=0 cellpadding=0 cellspacing=1>\n";
echo "  <tr valign=top>\n";
echo "    <td class=left_main width=180 align=left scope=col>\n";
echo "      <table class=hide width=100% border=0 cellpadding=1 cellspacing=0>\n";
echo "        <tr><td class=left_rows height=11></td></tr>\n";
echo "        <tr><td class=left_rows_headings height=18 valign=middle>Users</td></tr>\n";
echo "        <tr><td class=current_left_rows height=18 align=left valign=middle><img src='../images/icons/user.png' alt='User Summary' />&nbsp;&nbsp;
                <a class=admin_headings href='useradmin.php'>User Summary</a></td></tr>\n";
echo "        <tr><td class=left_rows height=18 align=left valign=middle><img src='../images/icons/user_add.png' alt='Create New User' />&nbsp;&nbsp;
                <a class=admin_headings href='usercreate.php'>Create New User</a></td></tr>\n";
echo "        <tr><td class=left_rows height=18 align=left valign=middle><img src='../images/icons/magnifier.png' alt='User Search' />&nbsp;&nbsp;
                <a class=admin_headings href='usersearch.php'>User Search</a></td></tr>\n";
echo "        <tr><td class=left_rows height=33></td></tr>\n";
echo "        <tr><td class=left_rows_headings height=18 valign=middle>Offices</td></tr>\n";
echo "        <tr><td class=left_rows height=18 align=left valign=middle><img src='../images/icons/brick.png' alt='Office Summary' />&nbsp;&nbsp;
                <a class=admin_headings href='officeadmin.php'>Office Summary</a></td></tr>\n";
echo "        <tr><td class=left_rows height=18 align=left valign=middle><img src='../images/icons/brick_add.png' alt='Create New Office' />&nbsp;&nbsp;
                <a class=admin_headings href='officecreate.php'>Create New Office</a></td></tr>\n";
echo "        <tr><td class=left_rows height=33></td></tr>\n";
echo "        <tr><td class=left_rows_headings height=18 valign=middle>Groups</td></tr>\n";
echo "        <tr><td class=left_rows height=18 align=left valign=middle><img src='../images/icons/group.png' alt='Group Summary' />&nbsp;&nbsp;
                <a class=admin_headings href='groupadmin.php'>Group Summary</a></td></tr>\n";
echo "        <tr><td class=left_rows height=18 align=left valign=middle><img src='../images/icons/group_add.png' alt='Create New Group' />&nbsp;&nbsp;
                <a class=admin_headings href='groupcreate.php'>Create New Group</a></td></tr>\n";
echo "        <tr><td class=left_rows height=33></td></tr>\n";
echo "        <tr><td class=left_rows_headings height=18 valign=middle colspan=2>In/Out Status</td></tr>\n";
echo "        <tr><td class=left_rows height=18 align=left valign=middle><img src='../images/icons/application.png' alt='Status Summary' />
                &nbsp;&nbsp;<a class=admin_headings href='statusadmin.php'>Status Summary</a></td></tr>\n";
echo "        <tr><td class=left_rows height=18 align=left valign=middle><img src='../images/icons/application_add.png' alt='Create Status' />&nbsp;&nbsp;
                <a class=admin_headings href='statuscreate.php'>Create Status</a></td></tr>\n";
echo "        <tr><td class=left_rows height=33></td></tr>\n";
echo "        <tr><td class=left_rows_headings height=18 valign=middle colspan=2>Miscellaneous</td></tr>\n";
echo "        <tr><td class=left_rows height=18 align=left valign=middle><img src='../images/icons/clock.png' alt='Modify Time' />
                &nbsp;&nbsp;<a class=admin_headings href='timeadmin.php'>Modify Time</a></td></tr>\n";
echo "        <tr><td class=left_rows height=18 align=left valign=middle><img src='../images/icons/application_edit.png' alt='Edit System Settings' />
                &nbsp;&nbsp;<a class=admin_headings href='sysedit.php'>Edit System Settings</a></td></tr>\n";
echo "        <tr><td class=left_rows height=18 align=left valign=middle><img src='../images/icons/database_go.png'
                alt='Manage Database' />&nbsp;&nbsp;&nbsp;<a class=admin_headings href='database_management.php'>Manage Database</a></td></tr>\n";
echo "      </table></td>\n";
end moved */

$user_count = mysql_query("select empfullname from ".$db_prefix."employees
                           order by empfullname");
@$user_count_rows = mysql_num_rows($user_count);

$admin_count = mysql_query("select empfullname from ".$db_prefix."employees where admin = '1'");
@$admin_count_rows = mysql_num_rows($admin_count);

$time_admin_count = mysql_query("select empfullname from ".$db_prefix."employees where time_admin = '1'");
@$time_admin_count_rows = mysql_num_rows($time_admin_count);

$reports_count = mysql_query("select empfullname from ".$db_prefix."employees where reports = '1'");
@$reports_count_rows = mysql_num_rows($reports_count);


echo '<div class="row">
        <div class="col-xs-12">
          <div class="box">
         
          <!-- /.box-header -->
          <div class="box-body table-responsive no-padding">
	  ';
echo "            <table class='table table-hover'>\n";
echo "              <tr><th>User Summary</th></tr>\n";
echo "              <tr><td><i class='fa fa-users text-green'></i> Total
                      Users: $user_count_rows&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class='fa fa-user-secret text-orange'></i>&nbsp;&nbsp;
                      Sys Admin Users: $admin_count_rows&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class='fa fa-user text-red'></i>&nbsp;&nbsp;
                      Time Admin Users: $time_admin_count_rows&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class='fa fa-user text-blue'></i>&nbsp;
                      &nbsp;Reports Users: $reports_count_rows</td></tr>\n";
echo "            </table></div></div></div></div>\n";
echo '<div class="row">
        <div class="col-xs-12">
          <div class="box">
         
          <!-- /.box-header -->
          <div class="box-body table-responsive no-padding">
	  ';
echo "            <table class='table table-hover'>\n";
echo "              <tr>\n";
echo "                <th>&nbsp;</th>\n";
echo "                <th>Username</th>\n";
echo "                <th>Display Name</th>\n";
echo "                <th>Email Address</th>\n";
echo "                <th>Office</th>\n";
echo "                <th>Group</th>\n";
echo "                <th>Disabled</th>\n";
echo "                <th>Sys Admin</th>\n";
echo "                <th>Time Admin</th>\n";
echo "                <th>Reports</th>\n";
echo "                <th>Edit</th>\n";
echo "                <th>Chg Pwd</th>\n";
echo "                <th>Delete</th>\n";
echo "              </tr>\n";

$row_count = 0;

$query = "select empfullname, displayname, email, groups, office, admin, reports, time_admin, disabled from ".$db_prefix."employees
          order by empfullname";
$result = mysql_query($query);

while ($row=mysql_fetch_array($result)) {

$empfullname = stripslashes("".$row['empfullname']."");
$displayname = stripslashes("".$row['displayname']."");

$row_count++;
$row_color = ($row_count % 2) ? $color2 : $color1;

echo "              <tr><td>&nbsp;$row_count</td>\n";
echo "                <td>&nbsp;<a title=\"Edit User: $empfullname\" class=footer_links
                    href=\"useredit.php?username=$empfullname&officename=".$row["office"]."\">$empfullname</a></td>\n";
echo "                <td>&nbsp;$displayname</td>\n";
echo "                <td>&nbsp;".$row["email"]."</td>\n";
echo "                <td>&nbsp;".$row['office']."</td>\n";
echo "                <td>&nbsp;".$row['groups']."</td>\n";

if ("".$row["disabled"]."" == 1) {
  echo "                <td><img src='../images/icons/cross.png' /></td>\n";
} else {
  $disabled = "";
  echo "                <td>".$disabled."</td>\n";
}
if ("".$row["admin"]."" == 1) {
  echo "                <td><img src='../images/icons/accept.png' /></td>\n";
} else {
  $admin = "";
  echo "                <td>".$admin."</td>\n";
}
if ("".$row["time_admin"]."" == 1) {
  echo "                <td><img src='../images/icons/accept.png' /></td>\n";
} else {
  $time_admin = "";
  echo "                <td>".$time_admin."</td>\n";
}
if ("".$row["reports"]."" == 1) {
  echo "                <td><img src='../images/icons/accept.png' /></td>\n";
} else {
  $reports = "";
  echo "                <td>".$reports."</td>\n";
}

if ((strpos($user_agent, "MSIE 6")) || (strpos($user_agent, "MSIE 5")) || (strpos($user_agent, "MSIE 4")) || (strpos($user_agent, "MSIE 3"))) {

echo "                <td><a 
                    title=\"Edit User: $empfullname\"
                    href=\"useredit.php?username=$empfullname&officename=".$row["office"]."\">Edit</a></td>\n";
echo "                <td><a 
                    title=\"Change Password: $empfullname\"
                    href=\"chngpasswd.php?username=$empfullname&officename=".$row["office"]."\">Chg Pwd</a></td>\n";
echo "                <td><a 
                    title=\"Delete User: $empfullname\"
                    href=\"userdelete.php?username=$empfullname&officename=".$row["office"]."\">Delete</a></td></tr>\n";

} else {

echo "                <td><a title=\"Edit User: $empfullname\"
                    href=\"useredit.php?username=$empfullname&officename=".$row["office"]."\">
                    <img border=0 src='../images/icons/application_edit.png' /></a></td>\n";
echo "                <td><a title=\"Change Password: $empfullname\"
                    href=\"chngpasswd.php?username=$empfullname&officename=".$row["office"]."\"><img border=0
                    src='../images/icons/lock_edit.png' /></a></td>\n";
echo "                <td><a title=\"Delete User: $empfullname\"
                    href=\"userdelete.php?username=$empfullname&officename=".$row["office"]."\">
                    <img border=0 src='../images/icons/delete.png' /></a></td></tr>\n";
}
}
echo "          </table></div></div></div></div>\n";
include '../footer.php';
include '../theme/templates/controlsidebar.inc'; 
include '../theme/templates/endmain.inc';
include '../theme/templates/adminfooterscripts.inc';
exit;
?>
