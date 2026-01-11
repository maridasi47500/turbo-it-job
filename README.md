![alt text](mypic1.png)

- application pour programmer PHP/pour creer un compte dans un reseau avec des langages informatique  avec ses infos, et chercher un job comme technicien informatique dans sa region
dans .env on peut modifier base donee
# turbo-it-job
````
cd my_project/
php -S localhost:8000 -t public/
````

- apt-get install php libapache2-mod-php

- CREATE SERVER monserver FOREIGN DATA WRAPPER postgres_fdw OPTIONS (host 'truc', dbname 'trucdb', port '5432');
- CREATE SERVER monserveur FOREIGN DATA WRAPPER pgsql OPTIONS (host 'truc', dbname 'trucdb', port '5432');
- CREATE FOREIGN DATA WRAPPER postgresql VALIDATOR postgresql_fdw_validator;
- $ su - postgres 
- $ psql template1
- template1=# CREATE USER tester WITH PASSWORD 'test_password';
- template1=# GRANT ALL PRIVILEGES ON DATABASE "test_database" to tester;
- template1=# \q

- DATABASE_URL=postgres://{user}:{password}@{hostname}:{port}/{database-name}

