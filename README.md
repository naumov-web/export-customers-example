## Export customers from file

### 1. Run of application
For run of application you must execute following command in command line:
```shell
    ./deployment/local/scripts/start.sh
```

### 2. Connect to Docker-container
For connect to Docker-container with php-fpm you must execute following command in command line:
```shell
    ./deployment/local/scripts/php_fpm_bash.sh
```

### 2. Export of customers
For export of customers from CSV file you must run following command into container with php-fpm:
```shell
    php artisan export:customers resources/export/random.csv
```
