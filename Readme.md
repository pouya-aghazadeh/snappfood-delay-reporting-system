# Snappfood Interview Code Challenge
## Delay Reporting Services
### for deployment, you should follow the instructions as blow
# Step 1. Database Initialization
- `cd <project-path>`
- `docker compose up -d`
- check the container status with `docker ps` or `docker logs snappfood-db`

# Step 2. Application Initialization
- `cd <project-path>`
- `composer install`
- `php -S localhost:8000 -t public`

# Step 3. Check The Connection
- For DB connection check, you can see the url `http://localhost:8000/`

# Step 4. Migration, Seed, Truncate
- Migrate Up : `curl --location --request POST 'http://localhost:8000/migrate/up'`
- Seed : `curl --location --request POST 'http://localhost:8000/migrate/seed'`
- Migrate Down : `curl --location --request POST 'http://localhost:8000/migrate/down'`
- Truncate : `curl --location --request POST 'http://localhost:8000/migrate/trunc'`

# Step 5. Play Board
- Register a delay report
  - `curl --location --request POST 'http://localhost:8000/delay-reports/register/<order-id>'`
- Track a delay report
  - `curl --location --request POST 'http://localhost:8000/delay-reports/track/<tracker-id>'`
-  Get stats of delays by vendor
  - `curl --location --request GET 'http://localhost:8000/delay-reports/list/<vendor-id>'`