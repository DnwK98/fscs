FROM ubuntu:18.04

# Install common applications
RUN apt-get update \
    && apt-get install --no-install-recommends -y \
	lib32gcc1 \
	lib32stdc++6 \
	lib32z1 \
	lib32ncurses5 \
	lib32stdc++6 \
	ca-certificates \
	unzip \
    && rm -rf /var/lib/apt/lists/*

# Install SteamCMD
RUN mkdir /steamcmd
WORKDIR /steamcmd
ADD https://steamcdn-a.akamaihd.net/client/installer/steamcmd_linux.tar.gz steamcmd_linux.tar.gz
RUN tar -zxvf steamcmd_linux.tar.gz

# Install CSGO
RUN ./steamcmd.sh +login anonymous +force_install_dir /csgo +app_update 740 validate +quit

# Fix for missing library
RUN mkdir /root/.steam/sdk32
RUN ln -s /steamcmd/linux32/steamclient.so /root/.steam/sdk32/steamclient.so

# Install CSGO Sourcemode
ADD https://sm.alliedmods.net/smdrop/1.9/sourcemod-1.9.0-git6282-linux.tar.gz /csgo/csgo/sourcemod-1.9.0.tar.gz
RUN cd /csgo/csgo && tar -zxvf sourcemod-1.9.0.tar.gz

# Install CSGO Metamod
ADD https://mms.alliedmods.net/mmsdrop/1.10/mmsource-1.10.7-git971-linux.tar.gz /csgo/csgo/mmsource-1.10.7-git971-linux.tar.gz
RUN cd /csgo/csgo && tar -zxvf mmsource-1.10.7-git971-linux.tar.gz

# Install CSGO GET5 addon
ADD https://github.com/splewis/get5/releases/download/0.7.1/get5_0.7.1.zip /csgo/csgo/get5_0.7.1.zip
RUN cd /csgo/csgo && unzip get5_0.7.1.zip

# Enable GET5 MySql stats
RUN cd /csgo/csgo/addons/sourcemod/plugins && mv disabled/get5_mysqlstats.smx get5_mysqlstats.smx

# Contains wrapper scripts and .cfg files
COPY _container /

# Set working directory for entrypoint script
WORKDIR /csgo

# Run server hadnler script
CMD ["./start.sh"]
