# Instalación
Copiar el .env.example a .env y especificar la información de base de datos y email correspondiente.

Correr los siguientes comandos:

- ``composer install``
- ``npm install``
- ``php artisan migrate --seed``
- ``php artisan passport:install``
- ``php artisan scout:mysql-index User``
- ``php artisan scout:mysql-index Cliente``
- ``php artisan scout:mysql-index Location``