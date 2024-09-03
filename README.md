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