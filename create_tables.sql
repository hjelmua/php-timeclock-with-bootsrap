#***************************************************************************
#*   Copyright (C) 2006 by Ken Papizan                                     *
#*   Copyright (C) 2008 by phpTimeClock Team                               *
#*   http://sourceforge.net/projects/phptimeclock                          *
#*                                                                         *
#*   This program is free software; you can redistribute it and/or modify  *
#*   it under the terms of the GNU General Public License as published by  *
#*   the Free Software Foundation; either version 2 of the License, or     *
#*   (at your option) any later version.                                   *
#*                                                                         *
#*   This program is distributed in the hope that it will be useful,       *
#*   but WITHOUT ANY WARRANTY; without even the implied warranty of        *
#*   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         *
#*   GNU General Public License for more details.                          *
#*                                                                         *
#*   You should have received a copy of the GNU General Public License     *
#*   along with this program; if not, write to the                         *
#*   Free Software Foundation, Inc.,                                       *
#*   51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA.             *
#***************************************************************************

# if you would like to utilize a table prefix when creating these tables, be sure to reflect that in config.inc.php so the program
# will be aware of it. this option is $db_prefix. if you are unaware of what is meant by utilizing a 'table prefix', then please disregard.

#
# Table structure for table `audit`
#

CREATE TABLE audit (
  modified_by_ip varchar(39) NOT NULL default '',
  modified_by_user varchar(50) NOT NULL default '',
  modified_when bigint(14) NOT NULL,
  modified_from bigint(14) NOT NULL,
  modified_to bigint(14) NOT NULL,
  modified_why varchar(250) NOT NULL default '',
  user_modified varchar(50) NOT NULL default '',
  PRIMARY KEY  (modified_when),
  UNIQUE KEY modified_when (modified_when)
);

# --------------------------------------------------------

#
# Table structure for table `dbversion`
#

CREATE TABLE dbversion (
  dbversion decimal(5,1) NOT NULL default '0.0',
  PRIMARY KEY  (dbversion)
);

#
# Dumping data for table `dbversion`
#

INSERT INTO dbversion VALUES ('1.4');

# --------------------------------------------------------

#
# Table structure for table `employees`
#

CREATE TABLE employees (
  empfullname varchar(50) NOT NULL default '',
  tstamp bigint(14) default NULL,
  employee_passwd varchar(25) NOT NULL default '',
  displayname varchar(50) NOT NULL default '',
  email varchar(75) NOT NULL default '',
  groups varchar(50) NOT NULL default '',
  office varchar(50) NOT NULL default '',
  admin tinyint(1) NOT NULL default '0',
  reports tinyint(1) NOT NULL default '0',
  time_admin tinyint(1) NOT NULL default '0',
  disabled tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (empfullname)
);

#
# Dumping data for table `employees`
#

INSERT INTO employees VALUES ('admin', NULL, 'xy.RY2HT1QTc2', 'administrator', '', '', '', 1, 1, 1, '');

# --------------------------------------------------------

#
# Table structure for table `groups`
#

CREATE TABLE groups (
  groupname varchar(50) NOT NULL default '',
  groupid int(10) NOT NULL auto_increment,
  officeid int(10) NOT NULL default '0',
  PRIMARY KEY  (groupid)
);

# --------------------------------------------------------

#
# Table structure for table `info`
#

CREATE TABLE info (
  fullname varchar(50) NOT NULL default '',
  `inout` varchar(50) NOT NULL default '',
  timestamp bigint(14) default NULL,
  notes varchar(250) default NULL,
  ipaddress varchar(39) NOT NULL default '',
  KEY fullname (fullname)
);

# --------------------------------------------------------

#
# Table structure for table `metars`
# had to make som changes

CREATE TABLE `metars` (
  `metar` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `station` varchar(4) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`station`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;



# --------------------------------------------------------

#
# Table structure for table `offices`
#

CREATE TABLE offices (
  officename varchar(50) NOT NULL default '',
  officeid int(10) NOT NULL auto_increment,
  PRIMARY KEY  (officeid)
);

# --------------------------------------------------------

#
# Table structure for table `punchlist`
#

CREATE TABLE punchlist (
  punchitems varchar(50) NOT NULL default '',
  color varchar(7) NOT NULL default '',
  in_or_out tinyint(1) default NULL,
  PRIMARY KEY  (punchitems)
);

#
# Dumping data for table `punchlist`
#

INSERT INTO punchlist VALUES ('in', '#009900', 1);
INSERT INTO punchlist VALUES ('out', '#FF0000', 0);
INSERT INTO punchlist VALUES ('break', '#FF9900', 0);
INSERT INTO punchlist VALUES ('lunch', '#0000FF', 0);

# --------------------------------------------------------


