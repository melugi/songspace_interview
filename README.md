## Deployment

This solution was developed using MariaDB 10.5.8. To configure MariaDB you can change the URL string in the .env file. The URL string template is: `DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=mariadb-10.5.8"`

To install dependencies run `composer install`, migrations are located in the migrations folder. Once MariaDB has been deployed and configured you can run the migrations by executing `php bin/console doctrine:migrations:migrate`.

## Database Design

## Implementation Design