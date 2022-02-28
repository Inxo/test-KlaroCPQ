
## Description

Backend source: [src](/src)

Frontend source: [assets](/assets)

Test server.js: [fake_server](/fake_server)

## Installation

```
git clone git@github.com:Inxo/test-KlaroCPQ.git
cd test-task
```

**Docker:**

Both [Docker](https://docs.docker.com/install/) and [Docker Compose](https://docs.docker.com/compose/install/) are need to be installed.

Run docker:

`docker-compose up -d`

Create database:

`docker-compose exec php bin/console doctrine:database:create`

Migrate database:

`docker-compose exec php bin/console doctrine:migrations:migrate`


## Run application

Open [http://localhost](http://localhost)

## Build frontend

`npm install`

`npm run build`