<?php
/***************************************************************************
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

// A thanks goes to the PhpBB team for the inspiration for the memory management and table parsing for MySQL.

/**
 * This module will allow a sys admin to backup the database.
 */

session_start();

include '../config.inc.php';

$self = $_SERVER['PHP_SELF'];
$request = $_SERVER['REQUEST_METHOD'];

// Ensure that the curent user has system access
if (! isset($_SESSION['valid_user'])) {
    write_admin_interface();
    echo "
            <!-- Display User Is Not Logged In -->
            <td align=left class=right_main scope=col>
               <table width=100% border=0 cellpadding=7 cellspacing=1>
                  <tr class=right_main_text>
                     <td height=10 align=center valign=top scope=row class=title_underline>
                        PHP Timeclock Administration
                     </td>
                  </tr>
                  <tr class=right_main_text>
                     <td align=center valign=top scope=row>
                        <table width=200 border=0 cellpadding=5 cellspacing=0>
                           <tr class=right_main_text>
                              <td align=center>
                                 You are not presently logged in, or do not have permission to view this page.
                              </td>
                           </tr>
                           <tr class=right_main_text>
                              <td align=center>
                                 Click
                                 <a class=admin_headings href='../login.php?login_action=admin'>
                                    <u>here</u>
                                 </a> to login.
                              </td>
                           </tr>
                        </table>
                        <br />
                     </td>
                  </tr>
               </table>
            </td>
         </tr>";
    include "../footer.php";
    exit;
}

/**
 * Writes the administration interface on the left of the browser
 */
function write_admin_interface() {
    include '../config.inc.php';
    include 'header.php';
    include 'topmain.php';

    echo "
      <title>
         $title - Backup Database
      </title>
      <!-- Administration Interface -->
      <table width=100% height=89% border=0 cellpadding=0 cellspacing=1>
         <tr valign=top>
            <td class=left_main width=180 align=left scope=col>
               <table class=hide width=100% border=0 cellpadding=1 cellspacing=0>
                  <tr>
                     <td class=left_rows height=11> </td>
                  </tr>
                  <tr>
                     <td class=left_rows_headings height=18 valign=middle>
                        Users
                     </td>
                  </tr>
                  <tr>
                     <td class=left_rows height=18 align=left valign=middle>
                        <img src='../images/icons/user.png' alt='User Summary' />&nbsp;&nbsp;
                        <a class=admin_headings href='useradmin.php'>
                           User Summary
                        </a>
                     </td>
                  </tr>
                  <tr>
                     <td class=left_rows height=18 align=left valign=middle>
                        <img src='../images/icons/user_add.png' alt='Create New User' />&nbsp;&nbsp;
                        <a class=admin_headings href='usercreate.php'>
                           Create New User
                        </a>
                     </td>
                  </tr>
                  <tr>
                     <td class=left_rows height=18 align=left valign=middle>
                        <img src='../images/icons/magnifier.png' alt='User Search' />&nbsp;&nbsp;
                        <a class=admin_headings href='usersearch.php'>
                           User Search
                        </a>
                     </td>
                  </tr>
                  <tr>
                     <td class=left_rows height=33 > </td>
                  </tr>
                  <tr>
                     <td class=left_rows_headings height=18 valign=middle>
                        Offices
                     </td>
                  </tr>
                  <tr>
                     <td class=left_rows height=18 align=left valign=middle>
                        <img src='../images/icons/brick.png' alt='Office Summary' />&nbsp;&nbsp;
                        <a class=admin_headings href='officeadmin.php'>
                           Office Summary
                        </a>
                     </td>
                  </tr>
                  <tr>
                     <td class=left_rows height=18 align=left valign=middle>
                        <img src='../images/icons/brick_add.png' alt='Create New Office' />&nbsp;&nbsp;
                        <a class=admin_headings href='officecreate.php'>
                           Create New Office
                        </a>
                     </td>
                  </tr>
                  <tr>
                     <td class=left_rows height=33> </td>
                  </tr>
                  <tr>
                     <td class=left_rows_headings height=18 valign=middle>
                        Groups
                     </td>
                  </tr>
                  <tr>
                     <td class=left_rows height=18 align=left valign=middle>
                        <img src='../images/icons/group.png' alt='Group Summary' />&nbsp;&nbsp;
                        <a class=admin_headings href='groupadmin.php'>
                           Group Summary
                        </a>
                     </td>
                  </tr>
                  <tr>
                     <td class=left_rows height=18 align=left valign=middle>
                        <img src='../images/icons/group_add.png' alt='Create New Group' />&nbsp;&nbsp;
                        <a class=admin_headings href='groupcreate.php'>
                           Create New Group
                        </a>
                     </td>
                  </tr>
                  <tr>
                     <td class=left_rows height=33> </td>
                  </tr>
                  <tr>
                     <td class=left_rows_headings height=18 valign=middle colspan=2>
                        In/Out Status
                     </td>
                  </tr>
                  <tr>
                     <td class=left_rows height=18 align=left valign=middle>
                        <img src='../images/icons/application.png' alt='Status Summary' />&nbsp;&nbsp;
                        <a class=admin_headings href='statusadmin.php'>
                           Status Summary
                        </a>
                     </td>
                  </tr>
                  <tr>
                     <td class=left_rows height=18 align=left valign=middle>
                        <img src='../images/icons/application_add.png' alt='Create Status' />&nbsp;&nbsp;
                        <a class=admin_headings href='statuscreate.php'>
                           Create Status
                        </a>
                     </td>
                  </tr>
                  <tr>
                     <td class=left_rows height=33> </td>
                  </tr>
                  <tr>
                     <td class=left_rows_headings height=18 valign=middle colspan=2>
                        Miscellaneous
                     </td>
                  </tr>
                  <tr>
                     <td class=left_rows height=18 align=left valign=middle>
                        <img src='../images/icons/clock.png' alt='Modify Time' />&nbsp;&nbsp;
                        <a class=admin_headings href='timeadmin.php'>
                           Modify Time
                        </a>
                     </td>
                  </tr>
                  <tr>
                     <td class=left_rows height=18 align=left valign=middle>
                        <img src='../images/icons/application_edit.png' alt='Edit System Settings' />&nbsp;&nbsp;
                        <a class=admin_headings href='sysedit.php'>
                           Edit System Settings
                        </a>
                    </td>
                  </tr>
                  <tr>
                     <td class=current_left_rows height=18 align=left valign=middle>
                        <img src='../images/icons/database_go.png' alt='Manage Database' />&nbsp;&nbsp;&nbsp;
                        <a class=admin_headings href='database_management.php'>
                           Manage Database
                        </a>
                     </td>
                  </tr>
               </table>
            </td>";
}

/**
 * Determines the memory limit requirements for exporting in MySQL.
 */
function get_usable_memory() {
    $val = trim(@ini_get('memory_limit'));
    if (preg_match('/(\\d+)([mkg]?)/i', $val, $regs)) {
        $memory_limit = (int) $regs[1];
        switch ($regs[2]) {
            case 'k':
            case 'K':
                $memory_limit *= 1024;
            break;

            case 'm':
            case 'M':
                $memory_limit *= 1048576;
            break;

            case 'g':
            case 'G':
                $memory_limit *= 1073741824;
            break;
        }

        // how much memory PHP requires at the start of export (it is really a little less)
        if ($memory_limit > 6100000) {
            $memory_limit -= 6100000;
        }

        // allow us to consume half of the total memory available
        $memory_limit /= 2;
    } else {
        // set the buffer to 1M if we have no clue how much memory PHP will give us :P
        $memory_limit = 1048576;
    }
    return $memory_limit;
}

/**
 * Writes and flushes the data in the ob buffer.
 * @param $data is the data to write to the ob buffer
 */
function write_flush($data) {
    echo $data;
    ob_flush();
    flush();
}

/**
 * Writes and flushes all data found on a table in the connected database.
 * @param $table is the database table to extract the data from and write to the ob buffer.
 */
function write_table($table) {
    $sql_data .= "# \n";
    $sql_data .= "# Data from table: $table \n";
    $sql_data .= "# \n";

    $query = "SELECT * FROM $db_prefix$table";
    $result = mysql_query($query);
    if ($result != false) {
        // Get field information
        $field_list = mysql_query("SHOW COLUMNS FROM $db_prefix$table");
        while ($field = mysql_fetch_row($field_list)) {
            if ($field[0] == "inout") { // Needs to be escaped
                $fields .='`inout`, ';
            } else {
                $fields .= $field[0].", ";
            }
        }
        $fields = rtrim($fields, ', ');
        $fields_cnt = mysql_num_fields($result);

        $sql_data       .= 'INSERT INTO '.$table.' ('.$fields.') VALUES ';
        $first_set      = true;
        $query_len      = 0;
        $max_len        = get_usable_memory();

        // Parse the table data and build the insertion statement
        while ($row = mysql_fetch_row($result)) {
            $values = array();
            if ($first_set) {
                $query = $sql_data . '(';
            } else {
                $query  .= ',(';
            }

            for ($j = 0; $j < $fields_cnt; $j++) {
                if (!isset($row[$j]) || is_null($row[$j])) {
                    $values[$j] = 'NULL';
                } else if (($field->flags & 32768) && ! ($field->flags & 1024)) {
                    $values[$j] = $row[$j];
                } else {
                    $values[$j] = "'" . str_replace($search, $replace, $row[$j]) . "'";
                }
            }
            $query .= implode(', ', $values) . ')';

            // Write and flush the insertion statement
            $query_len += strlen($query);
            if ($query_len > $max_len) {
                write_flush($query.";\n\n");
                $query = '';
                $query_len = 0;
                $first_set = true;
            } else {
                $first_set = false;
            }
        }
        mysql_free_result($result);

        // check to make sure we have nothing left to flush
        if (! $first_set && $query) {
            write_flush($query.";\n\n");
        }
    }
}

if ($request == 'GET') { // Output Backup Confirmation Interface
    write_admin_interface();
    echo "
            <!-- Backup Confirmation Interface -->
            <td valign=top>
               <table width=90% align=center height=40 border=0 cellpadding=0 cellspacing=0>
                  <tr>
                     <th class=table_heading_no_color nowrap width=100% align=left>
                        Confirm Database Backup
                     </th>
                  </tr>
               </table>
               <table class=table_border width=90% align=center border=0 cellpadding=0 cellspacing=0>
                  <form name='form' action='$self' method='post'>
                     <tr>
                        <td height=11> </td>
                     </tr>
                     <tr class=right_main_text>
                        <td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>
                           Clicking backup will create the database backup file. Upon successful backup creation the file will be available for download.
                        </td>
                     </tr>
                     <tr class=right_main_text>
                        <td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>
                           Clicking done will return you to the database management page.
                        </td>
                     </tr>
                     <div style='position:absolute; visibility:hidden; background-color:#ffffff; layer-background-color:#ffffff;' id='mydiv' height=200>
                        &nbsp;
                     </div>
                     <tr>
                        <td>
                           <input type='image' name='submit' value='backup' src='../images/buttons/backup_button.gif'>
                           <a href='database_management.php'>
                              <img src='../images/buttons/done_button.png' border='0'>
                           </a>
                        </td>
                     </tr>
                  </form>
               </table>
            </td>
         </tr>";
} else if ($request == 'POST') { // Begin the database backup
    $creating_backup_file = True; // Prevents the HTML from being added in the header file.
    include 'header.php';

    // Create a time fields for the backup file
    $time = time();
    $hour = gmdate('H', $time);
    $minute = gmdate('i', $time);
    $second = gmdate('s', $time);
    $month = gmdate('m', $time);
    $day = gmdate('d', $time);
    $year = gmdate('Y', $time);

    // Setup file name based on calendar preferences
    if ($calendar_style == "euro") {
        $date = "$day/$month/$year";
        $filename = "phptimeclock_backup_".$day."_".$month."_".$year.".sql";
    } elseif ($calendar_style == "amer") {
        $date = "$month/$day/$year";
        $filename = "phptimeclock_backup_".$month."_".$day."_".$year.".sql";
    } else {
        $date = "Calendar Style not set in config file.";
        $filename = "phptimeclock_backup.sql";
    }

    // Begin backup file creation
    header('Pragma: no-cache');
    header("Content-Type: application/x-sql; name=\"$filename\"");
    header("Content-disposition: attachment; filename=$filename");
    header("Expires: 0");
    ob_start();
    // Create backup file header
    $sql_data  = "# \n";
    $sql_data .= "# PhpTimeClock Database Backup \n";
    if (! empty($db_prefix)) { // If there are multiple databases, display which one we are backing up
        $sql_data .= "# Backup for $db_prefix \n";
    }
    $sql_data .= "# Backup created on $date $hour:$minute:$second GMT \n";
    $sql_data .= "# \n \n \n";
    write_flush($sql_data);
    $sql_data = "";
    // Begin the backup of each table in the database
    write_table("audit");
    write_flush($sql_data);
    $sql_data = "";
    write_table("dbversion");
    write_flush($sql_data);
    $sql_data = "";
    write_table("employees");
    write_flush($sql_data);
    $sql_data = "";
    write_table("groups");
    write_flush($sql_data);
    $sql_data = "";
    write_table("info");
    write_flush($sql_data);
    $sql_data = "";
    write_table("metars");
    write_flush($sql_data);
    $sql_data = "";
    write_table("offices");
    write_flush($sql_data);
    $sql_data = "";
    write_table("punchlist");
    write_flush($sql_data);
    ob_end_flush();
    exit;
}
// Add footer information and clean up left over HTML
include "../footer.php";
exit;
?>
