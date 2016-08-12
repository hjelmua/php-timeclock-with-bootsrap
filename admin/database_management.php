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
 * This module will allow a sys admin to select to backup, restore, or
 * upgrade the database.
 */

session_start();

include '../config.inc.php';
include 'header.php';
include 'topmain.php';
include 'leftmain.php';

$self = $_SERVER['PHP_SELF'];
$request = $_SERVER['REQUEST_METHOD'];

// Ensure that the curent user has system access
if (! isset($_SESSION['valid_user'])) {
    write_admin_interface($title);
    echo "
            <!-- Display User Is Not Logged In -->
               <table class='table'>
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
 * @param $title adds the bookmark title.
 */
function write_admin_interface($title) {

	echo '<div class="row">
	    <div class="col-md-8">
	      <div class="box box-info"> ';
	echo '<div class="box-header with-border">
	    <h3 class="box-title"><i class="fa fa-user-plus"></i> '.$title.' - Manage Database</h3>
	  </div><div class="box-body table-responsive">';
	          
	

}

// Create Database Management Interface
write_admin_interface($title);
echo "
            <table class='table table-hover'>
                  <tr>
                     <th class=table_heading_no_color nowrap width=100% align=left>
                        Manage Database
                     </th>
                  </tr>
               </table>
               <table class=table_border width=90% align=center border=0 cellpadding=0 cellspacing=0>
                  <tr class=right_main_text>
                     <td nowrap class=table_rows width=17%>
                        <img src='../images/icons/database_go.png' alt='Backup Database' />
                        <a href='database_backup.php'>
                           Backup Database
                        </a>
                     </td>
                  </tr>
                  <tr class=right_main_text>
                     <td nowrap class=table_rows width=17%>
                        <img src='../images/icons/database_go.png' alt='Restore Database' />
                        <a href='database_restore.php'>
                           Restore Saved Database
                        </a>
                     </td>
                  </tr>
                  <tr class=right_main_text>
                     <td nowrap class=table_rows width=17%>
                        <img src='../images/icons/database_go.png' alt='Upgrade Database' />
                        <a href='dbupgrade.php'>
                           Upgrade Database
                        </a>
                     </td>
                  </tr>
               </table>
";
echo "          </div></div></div></div>\n";
// Add HTML clean up and footer
include '../theme/templates/endmaincontent.inc';
include '../footer.php';
include '../theme/templates/controlsidebar.inc'; 
include '../theme/templates/endmain.inc';
include '../theme/templates/adminfooterscripts.inc';
exit;
?>