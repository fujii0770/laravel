### Instalation in 4 steps
Get source code from Git Repository https://git-codecommit.ap-northeast-1.amazonaws.com/v1/repos/ebi_IoT_philippines
```bash
composer update
cp .env.example .env
php artisan key:generate
```
- You have to setup database connection, paste this to your .env file

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pac
DB_USERNAME=root
DB_PASSWORD=
```
- Create Database: import file database/ebi_ddl.sql and database/ebi_dml.sql to database

##### That's all. Enjoy.

### Change log
##### v 1.0.2

## Screenshots