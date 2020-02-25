FROM ubuntu:18.04

# Set Warsaw TimeZone
ENV DEBIAN_FRONTEND=noninteractive
ENV TZ=Europe/Warsaw
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get update && \
    apt-get install -y software-properties-common sudo && \
    add-apt-repository ppa:ondrej/php && \
    add-apt-repository ppa:libreoffice/ppa && \
    add-apt-repository universe && \
    apt-get remove --purge -y $BUILD_PACKAGES && \
    rm -rf /var/lib/apt/lists/*

# Install packages
RUN apt-get update && apt-get install -y \
        vim \
        curl \
        wget \
        nginx-full \
        mysql-client \
        libreoffice-common \
        libreoffice-base mc \
        php7.3 \
        php7.3-dom \
        php7.3-curl \
        php7.3-intl \
        php7.3-mysql \
        php7.3-zip \
        php7.3-mbstring \
        php7.3-gd \
        php7.3-imagick\
        php7.3-dev \
        php7.3-xmlrpc \
        php7.3-apcu  && \
        apt-get remove --purge -y $BUILD_PACKAGES && \
        rm -rf /var/lib/apt/lists/*

RUN apt-get update && apt-get install -y \
        autoconf \
        g++ \
        make \
        openssl \
        libssl-dev \
        libcurl4-openssl-dev \
        libcurl4-openssl-dev \
        pkg-config libsasl2-dev \
        xfonts-base \
        xfonts-75dpi \
        libxrender1 \
        libxext6 \
        fontconfig \
        xvfb \
        supervisor && \
        apt-get remove --purge -y $BUILD_PACKAGES && \
        rm -rf /var/lib/apt/lists/*

# Install git
RUN wget https://github.com/git/git/archive/v1.9.2.zip -O git.zip && \
    apt-get update && \
    apt-get install git -y && \
    apt-get remove --purge -y $BUILD_PACKAGES && \
    rm -rf /var/lib/apt/lists/*

# Install composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --version=1.7.2 && \
    php -r "unlink('composer-setup.php');" && \
    mv composer.phar /usr/local/bin/composer

# Install docker and docker-compose
RUN curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo apt-key add - && \
    sudo add-apt-repository \
        "deb [arch=amd64] https://download.docker.com/linux/ubuntu \
        $(lsb_release -cs) \
        stable" && \
    apt-get update && apt-get install -y \
        docker-ce \
        docker-ce-cli \
        containerd.io && \
    curl -L "https://github.com/docker/compose/releases/download/1.25.3/docker-compose-$(uname -s)-$(uname -m)" \
        -o /usr/local/bin/docker-compose && \
    chmod +x /usr/local/bin/docker-compose


# Create user with provided uid and gid and add to docker group
RUN useradd --no-log-init -r -u 999 -g docker appuser && \
    mkhomedir_helper appuser && \
    echo "Created user 'appuser' 999 in group 999 (docker)"

# Change apache user
RUN echo "export APACHE_RUN_USER=appuser" >> /etc/apache2/envvars
RUN echo "export APACHE_RUN_GROUP=docker" >> /etc/apache2/envvars

# Change php.ini using sed and regex
RUN sed -i 's/memory_limit = .*/memory_limit = 512M/' /etc/php/7.3/apache2/php.ini && \
    sed -i 's/upload_max_filesize = .*/upload_max_filesize = 50M/' /etc/php/7.3/apache2/php.ini && \
    sed -i 's/max_file_uploads = .*/max_file_uploads = 50/' /etc/php/7.3/apache2/php.ini && \
    sed -i 's/max_execution_time = .*/max_execution_time = 60/' /etc/php/7.3/apache2/php.ini && \
    sed -i 's/post_max_size = .*/post_max_size = 50M/' /etc/php/7.3/apache2/php.ini && \
    sed -i 's/KeepAliveTimeout .*/KeepAliveTimeout 10/' /etc/apache2/apache2.conf && \
    a2enmod rewrite headers

# Set PHP CLI version
RUN update-alternatives --set php /usr/bin/php7.3 > /dev/null

# Copy files to docker container filesystem
ADD ./docker_filesystem/etc/apache2/sites-enabled/0-fscs-executor.conf /etc/apache2/sites-enabled/0-fscs-executor.conf

# Copy and link application directory
ADD --chown=appuser:docker . /app/fscs-executor
RUN chmod -R 777 /app/fscs-executor/storage && \
    rm -rf /var/www/html && \
    ln -s /app/fscs-executor/public /var/www/html

# Setup cron
RUN sudo -u appuser crontab /app/fscs-executor/crontab.txt

# Composer install
RUN cd /app/fscs-executor && \
    sudo -u appuser composer install --no-interaction --no-plugins --no-scripts

# Set working directory
WORKDIR /app/fscs-executor

# Show files
RUN ls -la

# Set supervisor entrypoint
CMD supervisord -n -c /app/fscs-executor/supervisord.conf
