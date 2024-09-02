FROM mysql:8.4.2

# Add any additional setup or initialization scripts
COPY ./mysql/init.sql /docker-entrypoint-initdb.d/

# Expose the default MySQL port
EXPOSE 3306