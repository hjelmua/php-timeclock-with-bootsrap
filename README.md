You can find the new version of php-timeclock-with-bootsrap
=====
here: https://github.com/hjelmua/php-timeclock



About
=====

A fork of timeclock version 1.10 that uses bootstrap

Please use the new version
====

New version found here: https://github.com/hjelmua/php-timeclock/



Timeclock - What Is It?
=======================

(from http://timeclock.sf.net/ ...)


Punchclock - What Is It?
========================

(from http://www.acmebase.org/punchclock/ ...)


##Installation

New Install
___

 - Unpack the distribution into your webserver's document root directory. 
 - Create a database named "timeclock" or whatever you wish to name it.
 - Create a mysql user named "timeclock" (or whatever you wish to name it) with a password.
 - Give this user at least SELECT, UPDATE, INSERT, DELETE, ALTER, and CREATE privileges to ONLY 
    this database.
 -  Import the tables using the create_tables.sql script included in this distribution.
 -  Edit config.inc.php.
 -  Open index.php with your web browser.
 -  Click on the Administration link on the right side of the page. Input "admin" (without the quotes) 
    for the username and "admin" (without the quotes) for the password. Please change the password 
    for this admin user after the initial setup of PHP Timeclock is complete.
 -  Create at least one office by clicking on the Create Office link on the left side of the page. 
    You MUST create an office to achieve the desired results. Create more offices if needed.
 -  Create at least one group by clicking on the Create Group link on the left side of the page. 
    You MUST create a group to achieve the desired results. Create more groups if needed.
 -  Add your users by clicking on the Create New Users link, and assign them to the office(s) and
    group(s) you created above. Give Sys Admin level access for users who will administrate 
    PHP Timeclock. Give Time Admin level access for users who will need to edit users' time, but 
    who will not need Sys Admin level access. If you require the reports to be secured so only 
    certain users can run them, then give these users reports level access. 


Migration from another verison of PHP-Timeclock
___

 -  Backup your current install directory and database.
 -  Delete all files in your current install directory.
 -  Copy all files from a zip of this repo's master branch
 -  Modify the new `config.inc.php` file to match your old settings, make sure you correctly set your timezone in php.ini (recommended) or `config.inc.php`.


##Roles
Admin level access and reports level access are completely separate from each other. Just because a user has admin level access does not give that user reports level access. You must specifically give them reports level access when you are creating or editing the users, if you choose to secure these reports for these users. To make PHP Timeclock lock down the reports to only these users, set the use_reports_password setting in config.inc.php to "yes".

##PHP Weather discontinued
The PHP Weather part has been discontinued and the code has not been updated to PHP 7. Do not use it on PHP7 servers.

## Now PHP 7 enabled.
All ereg/eregi and mysql code had been updated to PHP 7.

## New frontend first step to PHP 8.x
In the folder newfrontend you can find the beginning of the next generation PHP Timeclock. It is build to use the database structure.

##License
________

This software and changes made are licensed under the GNU GENERAL PUBLIC LICENSE 2 as found in docs/LICENSE


##Swedish 
________
Digital stämplelklocka i PHP och Bootstrap

##Screenshots
________

![Startpage](screenshots/start.jpg?raw=true "The start page")

![User create page](screenshots/createuser.jpg?raw=true "The page to create a user")

![Reportpage](screenshots/report.jpg?raw=true "A report page")
