# Use the Selenium standalone Chrome image
FROM selenium/standalone-chrome:126.0-chromedriver-126.0-20240621

# Create directories for 4 different Chrome profiles
RUN for i in $(seq 1 4); do \
        mkdir -p /home/seluser/.config/google-chrome/worker$i && \
        chown -R seluser:seluser /home/seluser/.config/google-chrome/worker$i; \
    done

RUN mkdir -p /home/seluser/Downloads && chown -R seluser:seluser /home/seluser/Downloads

# Set the working directory
WORKDIR /usr/src/app

# Expose the default Selenium port
EXPOSE 4444 7900