FROM php:8.3-apache-bullseye

# Setup ENV for php
ENV SE_CHROME_ARGS="goog:chromeOptions.args=[start-maximized, disable-dev-shm-usage, no-sandbox, user-data-dir=/home/seluser/.config/google-chrome, profile-directory=worker1] browserName=chrome"
ENV MYSQL_DATA_HOST=sel-mysql 
ENV SE_CHROME_HOST1=sel-chrome1
ENV SE_CHROME_HOST2=sel-chrome2
ENV SE_CHROME_HOST3=sel-chrome3
ENV SE_CHROME_HOST4=sel-chrome4


# Install your extensions to connect to MySQL, add mysqli
RUN docker-php-ext-install mysqli
# Install pdo is you need to use PHP PDO
RUN docker-php-ext-install pdo pdo_mysql
# Run docker-php-ext-enable command to activate mysqli
RUN docker-php-ext-enable mysqli

#Install Steps for selenium
RUN apt update
RUN apt install -y npm=7.5.2+ds-2 cron nano sudo
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
RUN echo 'cron && apache2-foreground' >> /bin/srvc_start && chmod +x /bin/srvc_start
RUN echo 'nohup /usr/local/bin/selenium-side-runner --server http://${SE_CHROME_HOST1}:4444/wd/hub -T 6000000 -t 6000000 -r 1 -c ${SE_CHROME_ARGS} /var/www/selenium/SV.side > /var/www/html/sv_run.log 2>&1 &' > /bin/sv_run && chmod 0744 /bin/sv_run
RUN echo 'nohup /usr/local/bin/selenium-side-runner --server http://${SE_CHROME_HOST2}:4444/wd/hub -T 6000000 -t 6000000 -r 1 -c ${SE_CHROME_ARGS} /var/www/selenium/W1.side > /var/www/html/w1_run.log 2>&1 &' > /bin/w1_run && chmod 0744 /bin/w1_run
RUN echo 'nohup /usr/local/bin/selenium-side-runner --server http://${SE_CHROME_HOST3}:4444/wd/hub -T 6000000 -t 6000000 -r 1 -c ${SE_CHROME_ARGS} /var/www/selenium/W2.side > /var/www/html/w2_run.log 2>&1 &' > /bin/w2_run && chmod 0744 /bin/w2_run
RUN echo 'nohup /usr/local/bin/selenium-side-runner --server http://${SE_CHROME_HOST4}:4444/wd/hub -T 6000000 -t 6000000 -r 1 -c ${SE_CHROME_ARGS} /var/www/selenium/W3.side > /var/www/html/w3_run.log 2>&1 &' > /bin/w3_run && chmod 0744 /bin/w3_run

#for parallel test
RUN echo 'nohup /usr/local/bin/selenium-side-runner --server http://${SE_CHROME_HOST1}:4444/wd/hub -T 6000000 -t 6000000 -r 1 -c ${SE_CHROME_ARGS} /var/www/selenium/SW.side > /var/www/html/sw_run.log 2>&1 &' > /bin/sw_run && chmod 0744 /bin/sw_run

# Setup cron job
RUN (crontab -l ; echo "PATH=${PATH}") | crontab -
RUN (crontab -l ; echo "SE_CHROME_ARGS=${SE_CHROME_ARGS}") | crontab -
RUN (crontab -l ; echo "SE_CHROME_HOST1=${SE_CHROME_HOST1}") | crontab -
RUN (crontab -l ; echo "SE_CHROME_HOST2=${SE_CHROME_HOST2}") | crontab -
RUN (crontab -l ; echo "SE_CHROME_HOST3=${SE_CHROME_HOST3}") | crontab -
RUN (crontab -l ; echo "SE_CHROME_HOST4=${SE_CHROME_HOST4}") | crontab -

RUN (crontab -l ; echo "* * * * * date > /var/www/html/croncheck.log 2>&1") | crontab -
RUN (crontab -l ; echo "* */5 * * * sw_run") | crontab -

RUN sed '/root\sALL=(ALL:ALL)\sALL/a www-data ALL=(ALL) NOPASSWD: /usr/bin/crontab' /etc/sudoers -i

#Fix bug in Selenium Parallel Run 
RUN sed "/},/ {N; s#},\n *testMatch: \[#},\n  globalTeardown: require.resolve('selenium-side-runner/teardown.js'),\n  testMatch: [#}" /usr/local/lib/node_modules/selenium-side-runner/jest.config.js -i
RUN echo -e "module.exports = async () => {\n  console.log('clean exit dependent process,..');\n  process.exit(0);\n};" > /usr/local/lib/node_modules/selenium-side-runner/teardown.js

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