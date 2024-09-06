# selenium_docker_server
 simple storage of scrape dataset

Need to modify if adding php and tables
1. mysql_image\mysql\init.sql : table names should match the php folder names
2. php_image\php folders: names should match init.sql

Need to modify if adding side files
1. php_image\Dockerfile: update the "Setup Command ShortCuts" section, add the path of the new side file.
2. php_image\php\CronEntry.php: update "$allowed_commands" variables this should match the Command shortcuts in php_image\Dockerfile.

web UI
1. php_image\php\CronEntry.php: use for any cron update
2. php_image\php\CheckDownload.php: for downloading files which selenium has downloaded on docker image
3. php_image\php\SideFile.php: use for uploading side on php docker image, please note that to use the side file it should follow the filenames existing on php_image\selenium folder.

web log files 
1. /var/www/html/sv_run.log
2. /var/www/html/w1_run.log
3. /var/www/html/w2_run.log
4. /var/www/html/w3_run.log
5. /var/www/html/croncheck.log

The container has 4 chrome image since this setup will require 4 selenium side files to run and its expected that each runtime will overlap.
Refer to the files below:
1. /docker-compose.yml  - image, volume
2. /php_image/Dockerfile - Setup Command ShortCuts, Setup cron job, Setup ENV for php

note: Each chrome image has 4 concurrent session, to be able to maximixe it put all web automation in 1 side file
        read more: https://www.selenium.dev/selenium-ide/docs/en/introduction/command-line-runner#test-parallelization-in-a-suite

To remove long running session on chrome image
1. cURL GET 'http://localhost:4444/status'  - to get sessions
2. cURL --request DELETE 'http://localhost:4444/session/<session-id>'  - to kill the session
read more: https://www.selenium.dev/documentation/grid/advanced_features/endpoints/

Adhoc Line
php_image\Dockerfile  - Fix bug in Selenium Parallel Run
 * This section was added to avoid run hold due to previous failed run.
 read more: https://victorjeman.com/blog/fix-for-jest-did-not-exit-one-second-after-the-test-run