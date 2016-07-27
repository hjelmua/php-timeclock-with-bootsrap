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
 * This module will logout the current user.
 */

session_start();

// Logout the admin system user
if (isset($_SESSION['valid_user'])) {
    unset($_SESSION['valid_user']);
}

// Logout the report system user
if (isset($_SESSION['valid_reports_user'])) {
    unset($_SESSION['valid_reports_user']);
}

// Logout the employee report user
if (isset($_SESSION['valid_report_employee'])) {
    unset($_SESSION['valid_report_employee']);
}

// Logout the time admin system user
if (isset($_SESSION['time_admin_valid_user'])) {
    unset($_SESSION['time_admin_valid_user']);
}

session_destroy();

// Redirect back to the main page.
echo "
      <script type='text/javascript' language='javascript'>
         window.location.href = 'index.php';
      </script>";
?>
