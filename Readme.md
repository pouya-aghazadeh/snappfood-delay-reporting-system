# Snappfood Interview | Code Challenge
## Delay Reporting Services
### for deployment, you should follow the instructions as blow
<br>


### Step 1. Database/Application Initialization
- `cd <project-path>`
- `docker compose up -d`
- check the container status with `docker ps` or `docker logs snappfood-<db/app>`

### Step 2. Check The Connection
- For DB connection check, you can see the url `http://localhost:8000/`

### Step 3. Migration, Seed, Truncate
- Migrate Up : `curl --location --request POST 'http://localhost:8000/migrate/up'`
- Seed : `curl --location --request POST 'http://localhost:8000/migrate/seed'`
- Migrate Down : `curl --location --request POST 'http://localhost:8000/migrate/down'`
- Truncate : `curl --location --request POST 'http://localhost:8000/migrate/trunc'`

### Step 4. Play Board
- Register a delay report
  - `curl --location --request POST 'http://localhost:8000/delay-reports/register/<order-id>'`
- Track a delay report
  - `curl --location --request PATCH 'http://localhost:8000/delay-reports/track/<tracker-id>'`
- Get stats of delays by vendor
  - `curl --location --request GET 'http://localhost:8000/delay-reports/list/<vendor-id>'`
