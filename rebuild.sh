#!/bin/bash

echo "Stopping all containers..."
docker stop $(docker ps -a -q)

echo "Removing all containers..."
docker rm $(docker ps -a -q)

echo "Deleting all volumes..."
docker volume rm $(docker volume ls -q)

echo "Pruning networks..."
docker network prune -f

echo "Removing all images..."
docker rmi $(docker images -q)

echo "Building and starting containers..."
docker-compose up --build
