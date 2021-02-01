# HOW TO DEPLOY THE SHARE-O-METAL APP

## REQUIREMENTS

You will need a server running with : 

- Apache 2
- PHP 7.2.5 or higher (see Symfony documentation for required PHP extensions if not installed)
- Composer
- MySQL
- Git

## INSTALLATION

1. Clone the repository and move into it with `cd /var/www/html/Share-o-metal`

2. Then run `composer install`.

3.  create the SSL keys to generate the JWT token :

    ```
    $ mkdir -p config/jwt
    $ openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
    $ openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
    ```

4.  Allow read and write on the directory containing the keys with `sudo chmod -R 777 config/jwt/`.

5.  Allow read and write on the directory that will contain the uploads `sudo chmod -R 777 uploads/pictures/`.
   
6. Configure the .env.local with `nano .en.local` and write in : 

    ```
    DATABASE_URL="mysql://username:password@127.0.0.1:3306/share_o_metal"
    JWT_PASSPHRASE='your_passphrase'
    APP_ENV=prod
    ```
    The JWT passphrase was asked to you at the creation of the SSL keys.

7. create the database `php bin/console doctrine:database:create`.

8. run the migrations `php bin/console doctrine:migrations:migrate`.

9. import the bands for our database with `php bin/console app:get:bands`. Use `--update` for further bands import.

10. create the fixtures (if needed) with `php bin/console doctrine:fixtures:load`


11. clear the cache with `php bin/console cache:clear` and run `php bin/console cache:warmup`.

And that's it (for the moment).