Overview:

Visitor Management System Overview
The visitor management system manage the tenants and their guest has arrival history.

To Begin the software installation, please follow the steps

Step 1: Install LAMP stack:

Windows/ Macbook (OSX): https://www.apachefriends.org/download.html

and install latest NodeJS stable version 
https://nodejs.org/en/download/

please make sure PHP version is 7.3 or above and latest MariaDB/mySQL stable version.

Step 2: download/ clone the source code from the github
https://github.com/pvrs2006sgnkl/assessment-visitor-management.git

Step 3: Start the Apache/ Database server
  - In windows OS go to start => Run => Search XAMPP, from the popup, start the Apache, MySQL 

Step 4: create the new database 
 - Go to http://localhost/phpmyadmin/inndex.php
 - Click Database menu and enter database name as visitor_admin, second dropdown select uft8_general_ci

Step 5: composer update

Step 6: php artisan migrate:fresh --seed

Step 7: php artisan serv

Step 8: Access the application by accessing the url from 
http://127.0.0.1:8000

Username: s-admin@mailinator.com
Password: sPassword

Note: 
- You can change the password upon the login
- This build has the POC (proof of the concept), some of search, other system improvement not handled. But the realtime, based on the business needs everything will be covered.

If you facing any difficulties, feel free to reach me via email/whatsApp
email: pvrs2006sgnkl@gmail.com/ pvrs2006@gmail.com
whatsApp: +6581863707

Thanks a lot for your time.