#!/bin/bash

echo -e "\e[32mConnecting to php-fpm container!"
echo -e "\e[97m"

docker exec -it export_customers_example_php_fpm bash
