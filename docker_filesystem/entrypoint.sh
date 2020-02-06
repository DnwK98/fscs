#!/bin/bash

cd /app/fscs-executor

echo "Initializing application..."
sleep 5
php artisan migrate
echo "Application initialization finished"
