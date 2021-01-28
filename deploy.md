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

3. Configure the .env.local with `nano .en.local` and write in : 

    ```
    DATABASE_URL="mysql://explorateur:Ereul9Aeng@127.0.0.1:3306/share_o_metal"
    JWT_PASSPHRASE='jwttoken1234'
    APP_ENV=prod
    ```

4. create the database `php bin/console doctrine:database:create`.

5. run the migrations `php bin/console doctrine:migrations:migrate`.

6. import the bands for our database with `php bin/console app:get:bands`.

7. check if there is duplicate rows :
   
   ```sql
    SELECT musicbrainz_id, COUNT(*)
    FROM band
    GROUP BY musicbrainz_id
    HAVING COUNT(*) > 1;
   ```
8. remove duplicate rows :

     ```sql
    DELETE t1 FROM band t1
    INNER JOIN band t2 
    WHERE 
    t1.id < t2.id AND 
    t1.musicbrainz_id = t2.musicbrainz_id;
   ```
9. create the fixtures (if needed) with `php bin/console doctrine:fixtures:load`

10. create the SSL keys to generate the JWT token :

    ```
    $ mkdir -p config/jwt
    $ openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
    $ openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
    ```

11. Allow read and write on the directory containing the keys with `sudo chmod -R 777 config/jwt/`.

12. clear the cache with `php bin/console cache:clear` and run `php bin/console cache:warmup`.

And that's it (for the moment).