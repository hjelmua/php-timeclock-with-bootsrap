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

/**
 * This module will allow a sys admin to restore the database.
 * @note This module depends on the backup file having been created by the database_base.php code.
 */

session_start();

$self = $_SERVER['PHP_SELF'];
$request = $_SERVER['REQUEST_METHOD'];

include '../config.inc.php';
include 'header.php';
include 'topmain.php';
include 'leftmain.php';


// Ensure that the curent user has system access
if (! isset($_SESSION['valid_user'])) {
    write_admin_interface($title);
    echo "
            <!-- Display User Is Not Logged In -->
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
               </table>";
    include "../footer.php";
    exit;
}

/**
 * Writes the administration interface on the left of the browser
 * @param $title is the title of the website bookmarks.
 */
function write_admin_interface($title) {
	echo '<div class="row">
	    <div class="col-md-10">
	      <div class="box box-info"> ';
	echo '<div class="box-header with-border">
	    <h3 class="box-title"><i class="fa fa-clock-o"></i> '.$title.' - Restore Database</h3>
	  </div><div class="box-body">';
}

/**
 * Validates the input given from the restore selection interface.
 * @param $backup_file is a file that was uploaded from a client's browser.
 */
function validInput($backup_file) {
    $is_valid_input = True; // Makes sure that the input is in a valid state

    if (! is_uploaded_file($backup_file['tmp_name'])) { // Ensure a backup file name was given
        echo "
            <!-- Backup Filename Missing Message -->
            
               <table width=90% align=center height=40 border=0 cellpadding=0 cellspacing=0>
                  <tr>
                     <td class=table_rows_red>
                        No backup file was uploaded.
                     </td>
                  </tr>
            </td></table>";
        $is_valid_input = False;
    }

    return $is_valid_input;
}

/**
 * Clears all data on a table in the database.
 * @param $table is the table to clear the data from the database.
 */
function clear_table($table) {
    $query = "DELETE FROM $table";
    $result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
    if ($result == false) {
        echo "
                        Failed to clear table: $table, ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)).". <br>";
    } else {
        echo "
                        Cleared table: $table. <br>";
    }
    ((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);
}

/**
 * Restores information on to the database into table.
 * @param $data is the mysql data insertion statement.
 * @param $table is the table to place the data into.
 * @note If $table does not exist the restore process for the table is skipped.
 */
function restore_table($data, $table)
{
    // Add data to the table in the database
    $result = mysqli_query($GLOBALS["___mysqli_ston"], $data);
    if ($result == false) {
        echo "
                        Failed to restore data, ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)).". <br>
                        <br>";
    } else {
        echo "
                        Successfully restored data. <br>
                        <br>";
    }
    ((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);
}

write_admin_interface($title);

// Determine if we need to validate post input
if ($request == 'POST') {
    // Begin creating post data
    $post_backup_file = $_FILES['backup_file'];
    $restore_confirmed = $_POST['restore_confirmed'];

    $is_valid_input = validInput($post_backup_file);
} else {
    $is_valid_input = True;
}

if (($request == 'GET') || (! $is_valid_input)) { // Output Restore Backup Interface
    echo "
            <!-- Restore Interface -->
            ";

    if ($is_valid_input) { // Add table if no error message have been displayed
        echo "
               <table class='table'>";
    }

    echo "
                  <tr>
                     <th class=table_heading_no_color nowrap width=100% align=left>
                        Database Restore
                     </th>
                  </tr>
               </table>
               <table class=table>
                  <form name='form' enctype='multipart/form-data' action='$self' method='post'>
                     <tr>
                        <td height=11> </td>
                     </tr>
                     <tr class=right_main_text>
                        <td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>
                           Select backup file: &nbsp;
                           <!-- Set the maximum file size for a database backup file to 1 Gibi -->
                           <input type='hidden' name='MAX_FILE_SIZE' value='1073741824' />
                           <input type='file' name='backup_file' size=40>
                        </td>
                     </tr>
               </table>";
	       echo '<div class="box-footer">
	                   <button type="submit" name="submit" value="restore" class="btn btn-warning">Next <i class="glyphicon glyphicon-arrow-right"></i></button>
	                   <button class="btn btn-default pull-right"><a href="database_management.php"><i class="glyphicon glyphicon-remove-circle text-red"></i> Cancel</a></button>   
	                 </div></form>';
			 
echo "          </div></div></div></div>\n";
} else if ($request == 'POST') { // Restore the database with the backup file
    echo "
            <!-- Restore Progress Interface -->
            <td valign=top>
               <table width=90% align=center height=40 border=0 cellpadding=0 cellspacing=0>
                  <tr>
                     <th class=table_heading_no_color nowrap width=100% align=left>
                        Database Restore
                     </th>
                  </tr>
               </table>
               <table class=table_border width=90% align=center border=0 cellpadding=0 cellspacing=0>
                  <tr>
                     <td height=11> </td>
                  </tr>
                  <tr class=right_main_text>
                     <td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>
                        Starting database restoration...
                     </td>
                  </tr>
                  <tr class=right_main_text>
                     <td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>";
    // Begin the database restoration process
    $filename = $post_backup_file['tmp_name'];
    $file_handle = fopen($filename, "r");
    while ($line = fgets($file_handle)) {
    //    if (ereg("# Data from table: ", $line)) {
	  if (preg_match("/# Data from table: /", $line)) {
        //    $table = split("# Data from table: ", $line);
	    $table = explode('# Data from table: ', $line);
            $table = rtrim($table[1]); // Strip ending characters to get only the table name
            echo "
                        <strong>Restore $table:</strong> <br>
                        Clearing table: $table... <br>";
            clear_table($table);
     //   } elseif (ereg("INSERT INTO ", $line)) {
	} elseif (preg_match('/INSERT INTO /', $line)) {
            $line = rtrim($line); // Strip ending characters
            restore_table($line, $table);
        }
    }
    fclose($file_handle);
    // database restoration finishes
    echo "
                     </td>
                  </tr>
                  <tr class=right_main_text>
                     <td class=table_rows height=25 width=20% style='padding-left:32px;' nowrap>
                        Database Restoration Completed.
                     </td>
                  </tr>
                  <div style='position:absolute; visibility:hidden; background-color:#ffffff; layer-background-color:#ffffff;' id='mydiv' height=200>
                     &nbsp;
                  </div>
                  <tr>
                     <td>
                        <a href='database_management.php'>
                           <img src='../images/buttons/done_button.png' border='0'>
                        </a>
                     </td>
                  </tr>
               </table>";
}
// Add footer information and clean up left over HTML
include '../theme/templates/endmaincontent.inc';
include '../footer.php';
include '../theme/templates/controlsidebar.inc'; 
include '../theme/templates/endmain.inc';
include '../theme/templates/adminfooterscripts.inc';
exit;
?>
