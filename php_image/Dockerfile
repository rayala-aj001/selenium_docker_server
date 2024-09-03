FROM php:8.3-apache-bullseye

# Install your extensions to connect to MySQL, add mysqli
RUN docker-php-ext-install mysqli
# Install pdo is you need to use PHP PDO
RUN docker-php-ext-install pdo pdo_mysql
# Run docker-php-ext-enable command to activate mysqli
RUN docker-php-ext-enable mysqli

#Install Steps for selenium
RUN apt update
RUN apt install -y npm=7.5.2+ds-2 cron nano
RUN npm install -g n@9.2.3 && n latest && hash -r
RUN npm install -g selenium-side-runner@4.0.12

# Copy custom php.ini configuration
COPY php.ini /usr/local/etc/php/

# Copy selenium side files to app directory
RUN mkdir /var/www/selenium
COPY ./selenium /var/www/selenium
RUN chmod -R 755 /var/www/selenium
RUN chown -R www-data:www-data /var/www/selenium/

#Setup Command ShortCuts
RUN echo 'nohup selenium-side-runner -w 10 --server http://${SE_CHROME_HOST}:4444/wd/hub -T 6000000 -t 6000000 -r 1 -c "goog:chromeOptions.args=[start-maximized, disable-dev-shm-usage, user-data-dir=/home/seluser/.config/google-chrome, profile-directory=worker1] browserName=chrome" /var/www/selenium/SV.side > /var/www/html/sv_run.log 2>&1' >> /bin/sv_run && chmod +x /bin/sv_run
RUN echo 'nohup selenium-side-runner -w 10 --server http://${SE_CHROME_HOST}:4444/wd/hub -T 6000000 -t 6000000 -r 1 -c "goog:chromeOptions.args=[start-maximized, disable-dev-shm-usage, user-data-dir=/home/seluser/.config/google-chrome, profile-directory=worker2] browserName=chrome" /var/www/selenium/W1.side > /var/www/html/w1_run.log 2>&1' >> /bin/w1_run && chmod +x /bin/w1_run
RUN echo 'nohup selenium-side-runner -w 10 --server http://${SE_CHROME_HOST}:4444/wd/hub -T 6000000 -t 6000000 -r 1 -c "goog:chromeOptions.args=[start-maximized, disable-dev-shm-usage, user-data-dir=/home/seluser/.config/google-chrome, profile-directory=worker3] browserName=chrome" /var/www/selenium/W2.side > /var/www/html/w2_run.log 2>&1' >> /bin/w2_run && chmod +x /bin/w2_run
RUN echo 'nohup selenium-side-runner -w 10 --server http://${SE_CHROME_HOST}:4444/wd/hub -T 6000000 -t 6000000 -r 1 -c "goog:chromeOptions.args=[start-maximized, disable-dev-shm-usage, user-data-dir=/home/seluser/.config/google-chrome, profile-directory=worker4] browserName=chrome" /var/www/selenium/W3.side > /var/www/html/w3_run.log 2>&1' >> /bin/w3_run && chmod +x /bin/w3_run
RUN echo 'cron && apache2-foreground' >> /bin/srvc_start && chmod +x /bin/srvc_start

# Setup cron job
RUN (crontab -l -u www-data; echo "0 */5 * * * sv_run") | crontab -u www-data -

# Set the working directory
WORKDIR /var/www/html
# Copy PHP files
COPY ./php /var/www/html

#Setup link Download folder
RUN mkdir /var/www/html/filesfolder && chown -R www-data:www-data /var/www/html/filesfolder

#setup ServerName
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Expose port 80
EXPOSE 80

# Start Apache server
CMD ["srvc_start"]