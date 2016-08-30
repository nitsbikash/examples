Better IP2Location PHP Library

1. What is does it do?
Better IP2Location PHP Library for faster query time.

1.2. Features
- Faster query time

2. Requirements
-PHP 5
-IP2Location lite database (DB11)

3. Installation
Download test.php, IP2Location.php, ip_list.txt, ip_result.txt and free IP2Location Lite DB 11 to a folder on your pc. 
Create inside the main folder antother folder "databases" and move the IP2LOCATION-LITE-DB11.BIN file into it. 

4. Tutorial
4.1 run the "test.php" in the command shell with option -f: php -f test.php.
4.2 Pre-calculation of binary search
4.2.1 Download calc.php, IP2LocationCalc.php to the main folder
4.2.2 Create pre-calculation and run calc.php in command shell (php -d calc.php) and wait 30-50min. 
4.2.3 Open the file1.txt and copy its content to the file IP2Location.php into var $k replacing the old array.
4.3 Alternative solution (slower)
4.3.1 Replace in test.php require_once 'IP2Location.php'; with require_once 'IP2LocationCalc.php';
4.3.2 run test.php (php -f test.php)

6. Changelog
01.06.2016 Initial release
