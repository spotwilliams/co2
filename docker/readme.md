
## Docker

Start the containers
```
docker-compose up -d
```

Stop / restart containers

```
docker-compose stop|start|restart [service-name]
```

Destroy the containers

```
docker-compose down
```

Rebuild the containers (if Dockerfile was changed)
```
docker-compose up -d --build
```

Execute a command on a running container

```
# Linux:
docker-compose exec [service-name] [command]

# example
docker-compose exec node bash

# Windows:
docker exec -it pareto_[service-name]_1 [command]

# example
docker exec -it pareto_node_1 bash
```

View container logs / output

```
docker-compose logs [-f] [service-name]
```

Run an artisan command:
```
// Linux:
docker-compose exec php ./artisan [command]

// Windows:
docker exec -it pareto_php_1 ./artisan [command]  
```

Run Composer on disposable container:
```
docker run -it --rm -v "D:\pareto://app" -v "C:\Users\[user]]\AppData\Roaming\composer://composer" composer install --ignore-platform-reqs --no-scripts
```
