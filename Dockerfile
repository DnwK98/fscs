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
        php7.3 \
        php7.3-dom \
        php7.3-curl \
        vim \
        php7.3-intl \
        php7.3-mysql \
        php7.3-zip \
        php7.3-mbstring \
        php7.3-gd \
        php7.3-imagick\
        nginx-full \
        curl \
        mysql-client \
        php7.3-dev \
        php7.3-xmlrpc \
        libreoffice-common \
        php7.3-apcu \
        libreoffice-base mc && \
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
        wget \
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

# Create user with provided uid and gid
RUN groupadd -g 1001 appuser && \
    useradd --no-log-init -r -u 1001 -g appuser appuser && \
    mkhomedir_helper appuser && \
    echo "Created user 'appuser' 1001 in group 1001"

# Change apache user
RUN echo "export APACHE_RUN_USER=appuser" >> /etc/apache2/envvars
RUN echo "export APACHE_RUN_GROUP=appuser" >> /etc/apache2/envvars

# Change php.ini using sed and regex
RUN sed -i 's/memory_limit = .*/memory_limit = 512M/' /etc/php/7.3/apache2/php.ini
RUN sed -i 's/upload_max_filesize = .*/upload_max_filesize = 50M/' /etc/php/7.3/apache2/php.ini
RUN sed -i 's/max_file_uploads = .*/max_file_uploads = 50/' /etc/php/7.3/apache2/php.ini
RUN sed -i 's/max_execution_time = .*/max_execution_time = 60/' /etc/php/7.3/apache2/php.ini
RUN sed -i 's/post_max_size = .*/post_max_size = 50M/' /etc/php/7.3/apache2/php.ini
RUN sed -i 's/KeepAliveTimeout .*/KeepAliveTimeout 10/' /etc/apache2/apache2.conf
RUN a2enmod rewrite headers

# Set PHP CLI version
RUN update-alternatives --set php /usr/bin/php7.3 > /dev/null

# Copy files to docker container filesystem
ADD ./docker_filesystem/etc/apache2/sites-enabled/0-fscs-executor.conf /etc/apache2/sites-enabled/0-fscs-executor.conf
ADD ./docker_filesystem/entrypoint.sh /entrypoint.sh
RUN chmod +rx /entrypoint.sh

# Copy and link application directory
ADD --chown=appuser:appuser . /app/fscs-executor
RUN chmod -R 777 /app/fscs-executor/storage && \
    rm -rf /var/www/html && \
    ln -s /app/fscs-executor/public /var/www/html

# Composer install
RUN cd /app/fscs-executor && \
    sudo -u appuser composer install --no-interaction --no-plugins --no-scripts

# Show files
RUN ls -la /app/fscs-executor

# Set entrypoint to supervisor
ENTRYPOINT supervisord -n -c /app/fscs-executor/supervisord.conf
