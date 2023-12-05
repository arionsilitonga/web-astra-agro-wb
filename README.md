## Pre Installation Raspberry Pi 
1. OS (Rasbian or linux base)
1. Webserver Apache  
	``sudo apt install apache2``
1. MySQL  
	``sudo apt install mysql-server``
1. PHP 7.4  
	``sudo apt install php libapache2-mod-php php-mysql phpunit curl php-curl``
1. Extensi PHP  
	``sudo apt install php-json php-xml php-gd php-zip php-intl``

## Installation
1. clone project kemudian masuk ke folder project
1. Composer Install  
	``php composer.phar install``
1. Buat database dan user database  
	- masuk ke ``mysql``  
		``CREATE DATABASE database_name;``
	- buat user database  
		``CREATE USER 'newuser'@'localhost' IDENTIFIED BY 'password';``  
		``GRANT ALL PRIVILEGES ON *.* TO 'newuser'@'localhost';``  

1. copy env ke .env kemudian atur konfigurasi di .env, terkait:
	APP Base Url  
	``app.baseURL = 'http://localhost:8000'``  
	``app.indexPage = ''``  
	Head Office API Endpoint  
	``app.webAPIUrlDevelopment = 'http://sop-dev.astra-agro.co.id'``  
	``app.webAPIUrlProduction = 'http://sop.astra-agro.co.id'``  
	Database  
	``database.default.hostname = localhost``  
	``database.default.database = [database_schema]``  
	``database.default.username = [database_user]``  
	``database.default.password = [database_password]``  
	``database.default.DBDriver = MySQLi``  
	``database.default.DBPrefix =``  
1. migrasi tabel database  
	``php spark migrate``
1. seed database data  
	``php spark db:seed Users``  
	``php spark db:seed Parameters``  
	``php spark db:seed ProductTransMap``  
	``php spark db:seed WBParameters``  
1. set host ke folder public project
	- masuk folder host ``cd /etc/apache2/sites-available``  
	- edit default host atau buat baru  
		``sudo nano 000-default.conf``  
		edit  
		``DocumentRoot /var/www/astraagrolestari-weightbridge/public``  
		dan  
		``<Directory /var/www/astraagrolestari-weightbridge/public>``  
			``Options Indexes FollowSysLinks``  
			``AllowOverride All``  
			``allow from all``  
		``</Directory>``  
	- enable rewrite modul  
		``sudo a2enmod rewrite``
	- reload web server  
		``sudo service apache2 reload``  
		atau restart web server  
		``sudo service apache2 restart``  

1. Set serial port readable, untuk user www-data  
	``sudo usermod -a -G dialout www-data``
1. Set cronjob untuk backup otomatis dan scheduler send API ke HQ  
	``crontab -e``  
	tambahkan perintah berikut pada crontab untuk fungsi backup otomatis:  
	``10 12 * * * ls ~/backup | sort -dr | tail -n +11 | xargs rm``  
	``10 12 * * * mysqldump -u root -p12345 wb_db > ~/backup/wb_db_`date '+%Y-%m-%d@%H%M'`.sql``  
	tambahkan perintah berikut pada crontab untuk fungsi send API dengan scheduler  
	``*/5 * * * * php /var/www/astraagrolestari-weightbridge/public/index.php schedule``  