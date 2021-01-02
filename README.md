# CO2 - Level

### About the project

Laravel project meant to receive CO2 Level measurement and manage alerts when limit reachs 2000 ppm

### Stack

This project was developed with the next stack:

* Laravel 7
* Doctrine 2

Was used a DDD aproach. In the `src` folder will find all logic. Also was developed through TDD technique

### Installing

1) Run and up containers: `docker-compose up -d`
2) Get inside php container to execute commands: `docker-compose exec php bash`
3) Update/create database schema `php artisan d:s:u`
4) Update/create database schema `php artisan migrate`
5) Generate Doctrine proxies `php artisan d:g:p`
6) Up worker `php artisan q:w database`

### Testing

In order to execute test

1) Get inside php container: `docker-compose exec php bash`
2) Execute tests from the container: `php artisan test`

### Job status:

All service Layer was developed. Only task in todo is the Controller layer to make public the information.

* Collect sensor mesurements: DONE

`POST /api/v1/sensors/{uuid}/mesurements`

```json
  {
    "co2": 2000,
    "time": "2019-02-01T18:55:47+00:00"
}
```

* Sensor status: TODO
  `GET /api/v1/sensors/{uuid}`

```json
{
    "status": "OK"
}
```

* Sensor metrics: TODO
  `GET /api/v1/sensors/{uuid}/metrics`

```json
{
    "maxLast30Days": 1200,
    "avgLast30Days": 900
}
```

* Listing alerts: TODO
  `GET /api/v1/sensors/{uuid}/alerts`

```json
[
    {
        "startTime": "2019-02-02T18:55:47+00:00",
        "endTime": "2019-02-02T20:00:47+00:00",
        "mesurement1": 2100,
        "mesurement2": 2200,
        "mesurement3": 2100
    }
]   
```
