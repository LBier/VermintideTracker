# VermintideTracker

Installation and Configuration

You have experience with MySQL, PHP and git:
-clone the repository
-import vermintide.sql into your MySQL database
-set DB_PASS in incl_config.php to your MySQL root password

You have no experience:
-Download XAMPP (Link: https://www.apachefriends.org/de/index.html)
-Install XAMPP (follow the instructions)
-Download the program as ZIP (Link: https://github.com/LBier/VermintideTracker/archive/master.zip)
-Extract VermintideTracker-master.zip to C:\xampp\htdocs
-Change the name of the extracted folder to VermintideTracker
-Open the XAMPP Control Panel and start Apache and MySQL
-Open http://localhost/phpmyadmin/ in your browser
-Click the Tab Import, click Choose File, navigate to C:\xampp\htdocs\VermintideTracker\ and open vermintide.sql, click OK
-Open http://localhost/VermintideTracker/
-Done!

In incl_config you can change:
-default difficulty (pre-selected in Difficulty dropdown)
-default item rarity (pre-selected in Rarity dropdown)
-default order field and order direction (Overview table)
-date format (e.g. "m/d/Y H:i" for Americans or "Y-m-d H:i")
