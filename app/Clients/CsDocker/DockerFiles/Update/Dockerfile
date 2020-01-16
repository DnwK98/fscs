FROM fscs_server_image

# Update CSGO
RUN /steamcmd/steamcmd.sh +login anonymous +force_install_dir /csgo +app_update 740 validate +quit

# Contains wrapper scripts and .cfg files
COPY ContainerFileSystem /

# Set working directory for entrypoint script
WORKDIR /csgo

# Run server hadnler script
CMD ["./start.sh"]
