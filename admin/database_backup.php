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

// A thanks goes to the PhpBB team for the inspiration for the memory management and table parsing for mysql.

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
            
               <table width=100% border=0 cellpadding=7 cellspacing=1>
                  <tr>
                     <td height=10 align=center valign=top scope=row class=title_underline>
                        PHP Timeclock Administration
                     </td>
                  </tr>
                  <tr>
                     <td align=center valign=top scope=row>
                        <table width=200 border=0 cellpadding=5 cellspacing=0>
                           <tr>
                              <td align=center>
                                 You are not presently logged in, or do not have permission to view this page.
                              </td>
                           </tr>
                           <tr>
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
";
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
    include 'leftmain.php';


echo '<div class="row">
    <div class="col-md-10">
      <div class="box box-info"> ';
echo '<div class="box-header with-border">
    <h3 class="box-title"><i class="fa fa-clock-o"></i> '.$title.' - Backup Database</h3>
  </div><div class="box-body">';

}

/**
 * Determines the memory limit requirements for exporting in mysql.
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
    $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
    if ($result != false) {
        // Get field information
        $field_list = mysqli_query($GLOBALS["___mysqli_ston"], "SHOW COLUMNS FROM $db_prefix$table");
        while ($field = mysqli_fetch_row($field_list)) {
            if ($field[0] == "inout") { // Needs to be escaped
                $fields .='`inout`, ';
            } else {
                $fields .= $field[0].", ";
            }
        }
        $fields = rtrim($fields, ', ');
        $fields_cnt = (($___mysqli_tmp = mysqli_num_fields($result)) ? $___mysqli_tmp : false);

        $sql_data       .= 'INSERT INTO '.$table.' ('.$fields.') VALUES ';
        $first_set      = true;
        $query_len      = 0;
        $max_len        = get_usable_memory();

        // Parse the table data and build the insertion statement
        while ($row = mysqli_fetch_row($result)) {
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
        ((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);

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
            <table class='table table-hover'>
                  <tr>
                     <th>
                        Confirm Database Backup
                     </th>
                  </tr>
               </table>
               <table class='table table-hover'>
                  <form name='form' action='$self' method='post'>
                     <tr>
                        <td height=11> </td>
                     </tr>
                     <tr>
                        <td>
                           Clicking backup will create the database backup file. Upon successful backup creation the file will be available for download.
                        </td>
                     </tr>
                     <tr>
                        <td>
                           Clicking done will return you to the database management page.
                        </td>
                     </tr>
               </table>";
	       echo '<div class="box-footer">
	                   <button type="submit" name="submit" value="backup" class="btn btn-warning"><i class="glyphicon glyphicon-download-alt"></i> Backup</button>
	                   <button class="btn btn-default pull-right"><a href="database_management.php">Done <i class="glyphicon glyphicon-ok-sign text-green"></i></a></button>   
	                 </div></form>';	
echo "          </div></div></div></div>\n";
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
include '../theme/templates/endmaincontent.inc';
include '../footer.php';
include '../theme/templates/controlsidebar.inc'; 
include '../theme/templates/endmain.inc';
include '../theme/templates/adminfooterscripts.inc';
exit;
?>
