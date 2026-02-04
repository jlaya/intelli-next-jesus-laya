1. Preparación del Entorno

Antes de levantar Docker, asegúrate de tener tu archivo de configuración de Laravel listo:

    Crear el .env: Si no lo tienes, copia el de ejemplo: cp .env.example .env

    Configurar la base de datos en el .env: Ajusta estos valores según tu docker-compose.yml:
    Fragmento de código

    DB_CONNECTION=pgsql
    DB_HOST=db
    DB_PORT=5432
    DB_DATABASE=testing
    DB_USERNAME=postgres
    DB_PASSWORD=123456

2. Construcción y Encendido

Desde la raíz de tu proyecto, ejecuta:
Bash

docker-compose up -d

Esto construirá la imagen de PHP usando tu Dockerfile local y descargará Nginx y Postgres.
3. Instalación de Dependencias y Laravel

Una vez que los contenedores estén corriendo (puedes verificarlo con docker-compose ps), ejecuta los comandos dentro del contenedor de la aplicación:
A. Instalar Composer

Como Laravel 5.8 es antiguo, es vital que el contenedor use PHP 7.2 o 7.3. Corre:
Bash

docker-compose exec app composer install

B. Configuración Final de Laravel
Bash

# Generar la llave de seguridad
docker-compose exec app php artisan key:generate

# Dar permisos a las carpetas de almacenamiento (Vital en Linux/Mac)
docker-compose exec app chmod -R 775 storage bootstrap/cache

C. Ejecutar Migraciones y Seeders juntos

Para crear las tablas en tu base de datos Postgres:
Bash

docker-compose exec app php artisan migrate --seed


# Los archivos donde se almacena los archivo excel

storage/app/exports
