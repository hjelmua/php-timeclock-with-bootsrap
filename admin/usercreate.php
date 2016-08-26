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

$self = $_SERVER['PHP_SELF'];
$request = $_SERVER['REQUEST_METHOD'];

include '../config.inc.php';
if ($request !== 'POST') {include 'header_get.php';include 'topmain.php'; include 'leftmain.php'; }
echo "<title>$title - Create User</title>\n";

if (!isset($_SESSION['valid_user'])) {


	echo ' <div class="col-md-4"><div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-warning"></i> PHP Timeclock Administration</h4>
                You are not presently logged in, or do not have permission to view this page. Click <a href="../login.php?login_action=admin"><u>here</u></a> to login.
              </div></div>';

 exit;
}

if ($request == 'GET') {


	echo '<div class="row">
        <div class="col-md-8">
          <div class="box box-info"> ';
    echo '<div class="box-header with-border">
	                 <h3 class="box-title"><i class="fa fa-user-plus"></i> Create User</h3>
	               </div><div class="box-body">';
	
echo "            <form name='form' action='$self' method='post'>\n";
echo "            <table align=center class=table>\n";
echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Username:</td><td colspan=2 width=80%>
                      <input type='text' size='25' maxlength='50' name='post_username'>&nbsp;*</td></tr>\n";
echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Display Name:</td><td colspan=2 width=80%>
                      <input type='text' size='25' maxlength='50' name='display_name'>&nbsp;*</td></tr>\n";
echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Password:</td><td colspan=2 width=80%
                      style='padding-left:20px;'><input type='password' size='25' maxlength='25' name='password'></td></tr>\n";
echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Confirm Password:</td><td colspan=2 width=80%
                      style='padding-left:20px;'>
                      <input type='password' size='25' maxlength='25' name='confirm_password'></td></tr>\n";
echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Email Address:</td><td colspan=2 width=80%>
                      <input type='text' size='25' maxlength='75' name='email_addy'>&nbsp;*</td></tr>\n";
echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Office:</td><td colspan=2 width=80%>
                      <select name='office_name' onchange='group_names();'>\n";
echo "                      </select>&nbsp;*</td></tr>\n";
echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Group:</td><td colspan=2 width=80%>
                      <select name='group_name'>\n";
echo "                      </select>&nbsp;*</td></tr>\n";
echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Sys Admin User?</td>\n";
echo "                <td class=table_rows align=left width=80% style='padding-left:20px;'><input type='radio' name='admin_perms' value='1'>&nbsp;Yes
                    <input type='radio' name='admin_perms' value='0' checked>&nbsp;No</td></tr>\n";
echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Time Admin User?</td>\n";
echo "                <td class=table_rows align=left width=80% style='padding-left:20px;'><input type='radio' name='time_admin_perms' value='1'>&nbsp;Yes
                    <input type='radio' name='time_admin_perms' value='0' checked>&nbsp;No</td></tr>\n";
echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Reports User?</td>\n";
echo "                <td class=table_rows align=left width=80% style='padding-left:20px;'><input type='radio' name='reports_perms' value='1'>&nbsp;Yes
                    <input type='radio' name='reports_perms' value='0' checked>&nbsp;No</td></tr>\n";
echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>User Account Disabled?</td>\n";
echo "                <td class=table_rows align=left width=80% style='padding-left:20px;'><input type='radio' name='disabled' value='1'>&nbsp;Yes
                    <input type='radio' name='disabled' value='0' checked>&nbsp;No</td></tr>\n";
echo "              <tr><td class=table_rows align=right colspan=3>*&nbsp;required&nbsp;</td></tr>\n";
echo "            </table>\n";

				      echo '<div class="box-footer">
				                  <button type="submit" name="submit" value="Create User" class="btn btn-info">Create User</button>
				                  <button class="btn btn-default pull-right"><a href="usercreate.php">Cancel</a></button>   
				                </div></form>';
				      echo '</div></div></div></div>';				  
		      include '../theme/templates/endmaincontent.inc';
		      include '../footer.php';
		      include '../theme/templates/controlsidebar.inc'; 
		      include '../theme/templates/endmain.inc';
		      include '../theme/templates/adminfooterscripts.inc';
}

elseif ($request == 'POST') {

include 'header_post.php'; include 'topmain.php'; include 'leftmain.php';

$post_username = stripslashes($_POST['post_username']);
$display_name = stripslashes($_POST['display_name']);
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];
$email_addy = $_POST['email_addy'];
$office_name = $_POST['office_name'];
@$group_name = $_POST['group_name'];
$admin_perms = $_POST['admin_perms'];
$reports_perms = $_POST['reports_perms'];
$time_admin_perms = $_POST['time_admin_perms'];
$post_disabled = $_POST['disabled'];

$post_username = addslashes($post_username);
$display_name = addslashes($display_name);

$query5 = "select empfullname from ".$db_prefix."employees where empfullname = '".$post_username."' order by empfullname";
$result5 = mysqli_query($GLOBALS["___mysqli_ston"], $query5);

while ($row=mysqli_fetch_array($result5)) {
  $tmp_username = "".$row['empfullname']."";
}
((mysqli_free_result($result5) || (is_object($result5) && (get_class($result5) == "mysqli_result"))) ? true : false);

$post_username = stripslashes($post_username);
$display_name = stripslashes($display_name);

$string = strstr($post_username, "\"");
$string2 = strstr($display_name, "\"");

if ((@$tmp_username == $post_username) || ($password !== $confirm_password) ||
    (!preg_match('/' . "^([[:alnum:]]| |-|'|,)+$" . '/i', $post_username)) || (!preg_match('/' . "^([[:alnum:]]|Å|Ä|Ö|å|ä|ö| |-|'|,)+$" . '/i', $display_name)) || (empty($post_username)) ||
    (empty($display_name)) || (empty($email_addy)) || (empty($office_name)) || (empty($group_name)) ||
    (!preg_match('/' . "^([[:alnum:]]|~|\!|@|#|\$|%|\^|&|\*|\(|\)|-|\+|`|_|\=|[{]|[}]|\[|\]|\||\:|\<|\>|\.|,|\?)+$" . '/i', $password)) ||
    (!preg_match('/' . "^([[:alnum:]]|_|\.|-)+@([[:alnum:]]|\.|-)+(\.)([a-z]{2,4})$" . '/i', $email_addy)) || (($admin_perms != '1') && (!empty($admin_perms))) ||
    (($reports_perms != '1') && (!empty($reports_perms))) || (($time_admin_perms != '1') && (!empty($time_admin_perms))) ||
    (($post_disabled != '1') && (!empty($post_disabled))) || (!empty($string)) || (!empty($string2))
) {

    if (@tmp_username == $post_username) {
        $tmp_username = stripslashes($tmp_username);
    }


// begin post validation //

if (empty($post_username)) {
	
	echo ' <div class="col-md-4"><div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                 A Username is required.
              </div></div>';
}
elseif (empty($display_name)) {
	
	echo ' <div class="col-md-4"><div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                 A Display Name is required.
              </div></div>';
}
elseif (!empty($string)) {
	
	echo ' <div class="col-md-4"><div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                  Double Quotes are not allowed when creating an Username.
              </div></div>';
}
elseif (!empty($string2)) {
	
	echo ' <div class="col-md-4"><div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                  Double Quotes are not allowed when creating an Display Name.
              </div></div>';
}
elseif (empty($email_addy)) {
	
	echo ' <div class="col-md-4"><div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                  An Email Address is required.
              </div></div>';
}
elseif (empty($office_name)) {
	echo ' <div class="col-md-4"><div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                  An Office is required.
              </div></div>';
}
elseif (empty($group_name)) {
	echo ' <div class="col-md-4"><div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                  A Group is required.
              </div></div>';
}
elseif (@$tmp_username == $post_username) {
	echo ' <div class="col-md-4"><div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                  User already exists. Create another username.
              </div></div>';	



        } elseif (!preg_match('/' . "^([[:alnum:]]| |-|'|,)+$" . '/i', $post_username)) {
	echo ' <div class="col-md-4"><div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	                <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                  Alphanumeric characters, hyphens, apostrophes, commas, and spaces are allowed when creating a Username.
              </div></div>';



        } elseif (!preg_match('/' . "^([[:alnum:]]|Å|Ä|Ö|å|ä|ö| |-|'|,)+$" . '/i', $display_name)) {
	echo ' <div class="col-md-4"><div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                  Alphanumeric characters, hyphens, apostrophes, commas, and spaces are allowed when creating a Displayname.
              </div></div>';
        } elseif (!preg_match('/' . "^([[:alnum:]]|~|\!|@|#|\$|%|\^|&|\*|\(|\)|-|\+|`|_|\=|[{]|[}]|\[|\]|\||\:|\<|\>|\.|,|\?)+$" . '/i', $password)) {
	echo ' <div class="col-md-4"><div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                Single and double quotes, backward and forward slashes, semicolons, and spaces are not allowed when creating a
                Password.
              </div></div>';
}
elseif ($password != $confirm_password) {
	echo ' <div class="col-md-4"><div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                Passwords do not match.
              </div></div>';

        } elseif (!preg_match('/' . "^([[:alnum:]]|_|\.|-)+@([[:alnum:]]|\.|-)+(\.)([a-z]{2,4})$" . '/i', $email_addy)) {
	echo ' <div class="col-md-4"><div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                Alphanumeric characters, underscores, periods, and hyphens are allowed when creating an Email Address.
              </div></div>';
}
elseif (($admin_perms != '1') && (!empty($admin_perms))) {
	echo ' <div class="col-md-4"><div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                Choose \"yes\" or \"no\" for Sys Admin Perms.
              </div></div>';
}
elseif (($reports_perms != '1') && (!empty($reports_perms))) {
	echo ' <div class="col-md-4"><div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                Choose \"yes\" or \"no\" for Reports Perms.
              </div></div>';
}
elseif (($time_admin_perms != '1') && (!empty($time_admin_perms))) {
	echo ' <div class="col-md-4"><div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                Choose \"yes\" or \"no\" for Time Admin Perms.
              </div></div>';
}
elseif (($post_disabled != '1') && (!empty($post_disabled))) {
	echo ' <div class="col-md-4"><div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                 Choose \"yes\" or \"no\" for User Account Disabled.
              </div></div>';
}

if (!empty($office_name)) {
$query = "select * from ".$db_prefix."offices where officename = '".$office_name."'";
$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
while ($row=mysqli_fetch_array($result)) {
$tmp_officename = "".$row['officename']."";
}
((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);
if (!isset($tmp_officename)) {echo "Office is not defined.\n"; exit;}
}

if (!empty($group_name)) {
$query = "select * from ".$db_prefix."groups where groupname = '".$group_name."'";
$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
while ($row=mysqli_fetch_array($result)) {
$tmp_groupname = "".$row['groupname']."";
}
((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);
if (!isset($tmp_officename)) {echo "Group is not defined.\n"; exit;}
}

// end post validation //

if (!empty($string)) {$post_username = stripslashes($post_username);}
if (!empty($string2)) {$display_name = stripslashes($display_name);}

$password = crypt($password, 'xy');
$confirm_password = crypt($confirm_password, 'xy');



echo '<div class="row">
    <div class="col-md-8">
      <div class="box box-info"> ';
echo '<div class="box-header with-border">
                 <h3 class="box-title"><i class="fa fa-user-plus"></i> Create User</h3>
               </div><div class="box-body">';
echo "            <form name='form' action='$self' method='post'>\n";
echo "            <table class=table>\n";
echo "              <tr><td class=table_rows  height=25 width=20% style='padding-left:32px;' nowrap>Username:</td><td colspan=2 width=80%
                      style='color:red;font-family:Tahoma;font-size:11px;padding-left:20px;'>
                      <input type='text' size='25' maxlength='50' name='post_username' value=\"$post_username\">&nbsp;*</td></tr>\n";
echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Display Name:</td><td colspan=2 width=80%
                      style='color:red;font-family:Tahoma;font-size:11px;padding-left:20px;'>
                      <input type='text' size='25' maxlength='50' name='display_name' value=\"$display_name\">&nbsp;*</td></tr>\n";

if (!empty($string)) {$post_username = addslashes($post_username);}
if (!empty($string2)) {$displayname = addslashes($display_name);}

echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Password:</td><td colspan=2 width=80%
                      style='padding-left:20px;'><input type='password' size='25' maxlength='25' name='password'></td></tr>\n";
echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Confirm Password:</td><td colspan=2 width=80%
                      style='padding-left:20px;'>
                      <input type='password' size='25' maxlength='25' name='confirm_password'></td></tr>\n";
echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Email Address:</td><td colspan=2 width=80%
                      style='color:red;font-family:Tahoma;font-size:11px;padding-left:20px;'>
                      <input type='text' size='25' maxlength='75' name='email_addy' value=\"$email_addy\">&nbsp;*</td></tr>\n";
echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Office:</td><td colspan=2 width=80%
                      >
                      <select name='office_name' onchange='group_names();'>\n";
echo "                      </select>&nbsp;*</td></tr>\n";
echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Group:</td><td colspan=2 width=80%
                      >
                      <select name='group_name' onfocus='group_names();'>
                        <option selected>$group_name</option>\n";
echo "                      </select>&nbsp;*</td></tr>\n";

echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Sys Admin User?</td>\n";
if ($admin_perms == "1") {
echo "                <td class=table_rows align=left width=80% style='padding-left:20px;'><input type='radio' name='admin_perms' value='1'
                    checked>&nbsp;Yes<input type='radio' name='admin_perms' value='0'>&nbsp;No</td></tr>\n";
} else {
echo "                <td class=table_rows align=left width=80% style='padding-left:20px;'><input type='radio' name='admin_perms' value='1'>&nbsp;Yes
                    <input type='radio' name='admin_perms' value='0' checked>&nbsp;No</td></tr>\n";
}

echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Time Admin User?</td>\n";
if ($time_admin_perms == "1") {
echo "                <td class=table_rows align=left width=80% style='padding-left:20px;'><input type='radio' name='time_admin_perms' value='1'
                    checked>&nbsp;Yes<input type='radio' name='time_admin_perms' value='0'>&nbsp;No</td></tr>\n";
} else {
echo "                <td class=table_rows align=left width=80% style='padding-left:20px;'><input type='radio' name='time_admin_perms' value='1'>&nbsp;Yes
                    <input type='radio' name='time_admin_perms' value='0' checked>&nbsp;No</td></tr>\n";
}
echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Reports User?</td>\n";
if ($reports_perms == "1") {
echo "                <td class=table_rows align=left width=80% style='padding-left:20px;'><input type='radio' name='reports_perms' value='1'
                    checked>&nbsp;Yes<input type='radio' name='reports_perms' value='0'>&nbsp;No</td></tr>\n";
} else {
echo "                <td class=table_rows align=left width=80% style='padding-left:20px;'><input type='radio' name='reports_perms' value='1'>&nbsp;Yes
                    <input type='radio' name='reports_perms' value='0' checked>&nbsp;No</td></tr>\n";
}
echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>User Account Disabled?</td>\n";
if ($post_disabled == "1") {
echo "                <td class=table_rows align=left width=80% style='padding-left:20px;'><input type='radio' name='disabled' value='1'
                    checked>&nbsp;Yes<input type='radio' name='disabled' value='0'>&nbsp;No</td></tr>\n";
} else {
echo "                <td class=table_rows align=left width=80% style='padding-left:20px;'><input type='radio' name='disabled' value='1'>&nbsp;Yes
                    <input type='radio' name='disabled' value='0' checked>&nbsp;No</td></tr>\n";
}
echo "              <tr><td class=table_rows align=right colspan=3 >*&nbsp;required&nbsp;</td></tr>\n";
echo "            </table>\n";
// echo "            <table align=center width=60% border=0 cellpadding=0 cellspacing=3>\n";
// echo "              <tr><td height=40>&nbsp;</td></tr>\n";
// echo "              <tr><td width=30><input type='image' name='submit' value='Create User' align='middle'
//                      src='../images/buttons/next_button.png'></td><td><a href='useradmin.php'><img src='../images/buttons/cancel_button.png'
//                      border='0'></td></tr></table></form>\n";
				      echo '<div class="box-footer">
				                  <button type="submit" name="submit" value="Create User" class="btn btn-info">Create User</button>
				                  <button class="btn btn-default pull-right"><a href="usercreate.php">Cancel</a></button>   
				                </div></form>';
				      echo '</div></div></div></div>';			  
		      include '../theme/templates/endmaincontent.inc';
		      include '../footer.php'; 
		      include '../theme/templates/controlsidebar.inc'; 
		      include '../theme/templates/endmain.inc';
		      include '../theme/templates/adminfooterscripts.inc';
		      exit;
}

$post_username = addslashes($post_username);
$display_name = addslashes($display_name);

$password = crypt($password, 'xy');
$confirm_password = crypt($confirm_password, 'xy');

$query3 = "insert into ".$db_prefix."employees (empfullname, displayname, employee_passwd, email, groups, office, admin, reports, time_admin, disabled)
           values ('".$post_username."', '".$display_name."', '".$password."', '".$email_addy."', '".$group_name."', '".$office_name."', '".$admin_perms."',
           '".$reports_perms."', '".$time_admin_perms."', '".$post_disabled."')";
$result3 = mysqli_query($GLOBALS["___mysqli_ston"], $query3);

/*
echo "<table width=100% height=89% border=0 cellpadding=0 cellspacing=1>\n";
echo "  <tr valign=top>\n";
echo "    <td class=left_main width=180 align=left scope=col>\n";
echo "      <table class=hide width=100% border=0 cellpadding=1 cellspacing=0>\n";
echo "        <tr><td class=left_rows height=11></td></tr>\n";
echo "        <tr><td class=left_rows_headings height=18 valign=middle>Users</td></tr>\n";
echo "        <tr><td class=left_rows height=18 align=left valign=middle><img src='../images/icons/user.png' alt='User Summary' />&nbsp;&nbsp;
                <a class=admin_headings href='useradmin.php'>User Summary</a></td></tr>\n";
echo "        <tr><td class=current_left_rows height=18 align=left valign=middle><img src='../images/icons/user_add.png' alt='Create New User' />
                &nbsp;&nbsp;<a class=admin_headings href='usercreate.php'>Create New User</a></td></tr>\n";
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
echo "    <td align=left class=right_main scope=col>\n";
echo "      <table width=100% height=100% border=0 cellpadding=10 cellspacing=1>\n";
echo "        <tr class=right_main_text>\n";
echo "          <td valign=top>\n";
echo "            <br />\n";
*/


echo '<div class="row">
    <div class="col-md-8">
      <div class="box box-info"> ';
echo '<div class="box-header with-border">
                 <h3 class="box-title"><i class="fa fa-user-plus"></i> User created successfully.</h3>
               </div><div class="box-body">';

echo "            <table class=table>\n";
echo "              <tr>\n";
echo "                <th class=rightside_heading nowrap halign=left colspan=3><i class='fa fa-user-plus'></i>&nbsp;&nbsp;&nbsp;Create User
                </th></tr>\n";
echo "              <tr><td height=15></td></tr>\n";

$query4 = "select empfullname, displayname, email, groups, office, admin, reports, time_admin, disabled from ".$db_prefix."employees
	  where empfullname = '".$post_username."'
          order by empfullname";
$result4 = mysqli_query($GLOBALS["___mysqli_ston"], $query4);

while ($row=mysqli_fetch_array($result4)) {

$username = stripslashes("".$row['empfullname']."");
$displayname = stripslashes("".$row['displayname']."");
$user_email = "".$row['email']."";
$office = "".$row['office']."";
$groups = "".$row['groups']."";
$admin = "".$row['admin']."";
$reports = "".$row['reports']."";
$time_admin = "".$row['time_admin']."";
$disabled = "".$row['disabled']."";
}
((mysqli_free_result($result4) || (is_object($result4) && (get_class($result4) == "mysqli_result"))) ? true : false);

echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Username:</td><td align=left class=table_rows
                      colspan=2 width=80% style='padding-left:20px;'>$username</td></tr>\n";
echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Display Name:</td><td align=left class=table_rows
                      colspan=2 width=80% style='padding-left:20px;'>$displayname</td></tr>\n";
echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Password:</td><td align=left class=table_rows
                      colspan=2 width=80% style='padding-left:20px;'>***hidden***</td></tr>\n";
echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Email Address:</td><td align=left class=table_rows
                      colspan=2 width=80% style='padding-left:20px;'>$user_email</td></tr>\n";
echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Office:</td><td align=left class=table_rows
                      colspan=2 width=80% style='padding-left:20px;'>$office</td></tr>\n";
echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Group:</td><td align=left class=table_rows
                      colspan=2 width=80% style='padding-left:20px;'>$groups</td></tr>\n";

if ($admin == "1") {$admin = "Yes";}
else {$admin = "No";}
echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Sys Admin User?</td><td align=left class=table_rows
                      colspan=2 width=80% style='padding-left:20px;'>$admin</td></tr>\n";
if ($time_admin == "1") {$time_admin = "Yes";}
else {$time_admin = "No";}
echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Time Admin User?</td><td align=left class=table_rows
                      colspan=2 width=80% style='padding-left:20px;'>$time_admin</td></tr>\n";
if ($reports == "1") {$reports = "Yes";}
else {$reports = "No";}
echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>Reports User?</td><td align=left class=table_rows
                      colspan=2 width=80% style='padding-left:20px;'>$reports</td></tr>\n";
if ($disabled == "1") {$disabled = "Yes";}
else {$disabled = "No";}
echo "              <tr><td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>User Account Disabled?</td><td align=left class=table_rows
                      colspan=2 width=80% style='padding-left:20px;'>$disabled</td></tr>\n";
echo "              <tr><td height=15></td></tr>\n";
echo "            </table>\n";
echo '<div class="box-footer">
           <a href="usercreate.php"><button class="btn btn-success">Done</button></a>
          </div>';
echo '</div></div></div></div>';		


include '../theme/templates/endmaincontent.inc';
include '../footer.php'; 
include '../theme/templates/controlsidebar.inc'; 
include '../theme/templates/endmain.inc';
include '../theme/templates/adminfooterscripts.inc';
exit;
}
?>
