# lemoine-wordpress-dockerized
PHP version: 8.2
Wordpress version: 6.6.1



# Installing Docker and WSL and Building container
First follow the instruction provided in `docker/Guide_to_install_Docker.pdf`
Then you clone in your wsl using the ubunto terminal like
>> cd source
>> git clone git@github.com:Lemoine-Mach2d-Solutions-INC/lemoine-wordpress-dockerized.git

Building and Running the Containers Using an existing Wordpress site.
To build and run the containers with HTTPS support, use the following commands:
** Note. 
>> A the initial copy of the db will be taken from `db\back.sql` file, replaced it before runing the next command in case u like to work wiht a new copy or connect using mysql workbeach and proceed to import it manaully.
>> In case that after the build out, you face an error like "Error establishing a database connection3" in the browser, do a manual stop of  the container and start it again to allow a re-mounting.
1. `docker-compose build --no-cache --quiet & docker-compose up -d & docker-compose restart`


Output.
Web app: http://localhost:2000/
MysqlWorkbeach connection DB: 
>> Host: 127.0.0.1
>> Port: 3009
>> User: root
>> Password: {grab from .env file}
 

# Extra dockers commands that may be helpfull
Checking logs in the db container 
>> docker-compose logs db
Checking server env variables inside of the db service container 
>> docker-compose exec db env
>> docker-compose exec wordpress env

# PHP Debugging 
The project had already setting to start debuggin the php files, if it req using the `.vscode/launch.json` file, whcih will server using the port 9003

# Wordpress Settings
In case a new setting is being applied in the .env file and you would like to be propagate to the `wordpress/wp-config.php`, you may neeed to rebuild the container. There we build a new funtion that will pick those new changes. It is called `getenv_docker`. This is intended to be working only locahost.

# Instruction to replace Wordpress with latest backup.
1. Zip and export the wordpress site. herokuapp.com
1. Create a new branch in git
2. Replace all the files in the `wordpress` folder except the `wp-config.php`  and `wordpress/wp-includes/functions.php` unless something new is happing.
3. Export a db from phpmyadmin
3. Replace the `db\back.sql`
4. Re build the containers

# WP Testing user
http://localhost:2000/login/
admin-user
AdminLogin123


# GIT COMMIT 
Remmeber to 
add the identity first to allow termina to use that
>> eval "$(ssh-agent -s)"
>> ssh-add ~/.ssh/id_ed25519_luis_sscs

\

Troublehstog
1. Getting this error: from VS CODE "VS Code: NoPermissions (FileSystemError): Error: EACCES: permission denied" when u are savig a file changes...
>> https://stackoverflow.com/questions/66496890/vs-code-nopermissions-filesystemerror-error-eacces-permission-denied
To fix it..
open the wsl shell , not the service container shell. then execute this command
>> sudo chown -R USERNAME ~/source/lemoine-wordpress-dockerized/wordpress
ex: sudo chown -R lsilva ~/source/lemoine-wordpress-dockerized/wordpress

