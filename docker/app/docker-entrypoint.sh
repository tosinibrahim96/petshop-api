#!/bin/bash

# Run the setup keys script
/usr/local/bin/setup-keys.sh

# Execute the CMD from the Dockerfile (or docker-compose.yml)
exec "$@"
