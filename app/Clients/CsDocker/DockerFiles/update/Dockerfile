FROM fscs_server_image

# Update CSGO
RUN /steamcmd/steamcmd.sh +login anonymous +force_install_dir /csgo +app_update 740 validate +quit

# Set working directory for entrypoint script
WORKDIR /csgo

# Run server hadnler script
CMD ["./start.sh"]
