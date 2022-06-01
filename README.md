# Balance API

## Requirements

- PHP 7.4
- Mysql

### Setup

```

composer install
php -S localhost:8080 -t public public/index.php

```

### MySQL

```

CREATE DATABASE apibalance;
CREATE TABLE accounts (
    id varchar(20),
    balance double,
    PRIMARY KEY (id)
);

```

### Tests

````
./vendor/bin/phpunit --verbose tests
````

## Endpoints

- GET /balance?account_id
- POST /event

### Examples

```

# Deposit into existing account

POST /event {"type":"deposit", "destination":"100", "amount":10}

201 {"destination": {"id":"100", "balance":20}}


# Get balance for existing account

GET /balance?account_id=100

200 20


# Withdraw from existing account

POST /event {"type":"withdraw", "origin":"100", "amount":5}

201 {"origin": {"id":"100", "balance":15}}


# Transfer from existing account

POST /event {"type":"transfer", "origin":"100", "amount":15, "destination":"300"}

201 {"origin": {"id":"100", "balance":0}, "destination": {"id":"300", "balance":15}}

```
