# Memor-i Studio
<p align="center">
<img src="https://raw.githubusercontent.com/scify/Memor-i/master/src/main/resources/img/memori.png" width="400">
</p>
<br>
Memor-i is a Memory card game especially tailored to meet the needs of blind people.

Memor-i Studio, is an online games repository that people can use in order to create their own flavors of Memor-i game!
Following the open-source paradigm, Memor-i Studio lets anyone sign up and create their own version of the popular game.


## About the project

This is a Laravel project:

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable, creative experience to be truly fulfilling. Laravel attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, yet powerful, providing tools needed for large, robust applications. A superb combination of simplicity, elegance, and innovation give you tools you need to build any application with which you are tasked.

##Installing dependencies (assuming apache as web server and mysql as db):

In a nutshell (assuming debian-based OS), first install the dependencies needed:

Note: php5 package installs apache2 as a dependency so we have no need to add it manually.

```
% sudo aptitude install php5 php5-cli mcrypt php5-mcrypt mysql-server php5-mysql
```

Install composer according to official instructions (link above) and move binary to ~/bin:
```% curl -sS https://getcomposer.org/installer | php5 && mv composer.phar ~/bin```

Download Laravel installer via composer:
```% composer global require "laravel/installer=~1.1"```


And add ~/.composer/vendor/bin to your $PATH. Example:
```
% cat ~/.profile
[..snip..]
LARAVEL=/home/username/.composer/vendor
PATH=$PATH:$LARAVEL/bin
```
And source your .profile with % source ~/.profile

##Apache configuration:
```
% cat /etc/apache2/sites-available/mysite.conf
<VirtualHost *:80>
    ServerName myapp.localhost.com
    DocumentRoot "/path/to/VoluntEasy/VoluntEasy/public"
    <Directory "/path/to/VoluntEasy/VoluntEasy/public">
        AllowOverride all
    </Directory>
</VirtualHost>
```
Make the symbolic link:
```
% cd /etc/apache2/sites-enabled && sudo ln -s ../sites-available/mysite.conf
```
Enable mod_rewrite and restart apache:
```
% sudo a2enmod rewrite && sudo service apache2 restart
```
Fix permissions for storage directory:
```
sudo chown -R user:www-data storage
chmod 775 storage
cd storage/
find . -type f -exec chmod 664 {} \;
find . -type d -exec chmod 775 {} \;
```
Test your setup with:
```
% php artisan serve
```
and navigate to localhost:8000.

## Setup DB
Laravel provides a simple yet powerful mechanism for creating the DB schema, called [Migrations](https://laravel.com/docs/5.3/migrations)
Simply run ```php artisan migrate``` to create the appropriate DB schema.

## Add seed data to DB
Run ```php artisan db:seed``` in order to insert the starter data to the DB by using [Laravel seeder](https://laravel.com/docs/5.3/seeding)

## Building project
After cloning the project, create an .env file (should be a copy of .env.example),
containing the information about your database name and credentials. 
After that, download all Laravel dependencies through [Composer](https://laravel.com/docs/5.3/installation), by running

```
composer install

composer update
```

After all Laravel dependencies have been downloaded, it's time to download all Javascript libraries and dependencies. 
We achieve that by using [npm](https://www.npmjs.com/). So, when in project root directory, run
```
npm install
```
To download and install all libraries and dependencies.

## Compiling assets

This project uses [Elixir](https://laravel.com/docs/5.3/elixir) which is a tool built on [Gulp](http://gulpjs.com/),
a popular toolkit for automating painful or time-consuming tasks, like SASS compiling and js/css concatenation and minification.

## Converting audio files to mpr with CBR (constant bit rate)
This project allows users to upload audio files. In order for the desktop application of Memor-i to operate correclty,
these files need to me in .mp3 format and have a CBR (constant bit rate), not a VBR (variable bit rate). [See more](https://www.lifewire.com/difference-between-cbr-and-vbr-encoding-2438423)
This project converts uploaded audio files appropriately, on the fly. For this to happen, we use [avconv](https://libav.org/avconv.html) library.
To install this library in a Unix-based machine, check [this post](http://askubuntu.com/questions/391357/how-do-you-install-avconv-on-ubuntu-server-13-04).

You can see and modify the command we use for coverting the files in ```public/convert_to_mp3.sh```.

##About jnlp, .jar file signing process 
This project uses [JNLP (Java Network Launch Protocol)](https://docs.oracle.com/javase/tutorial/deployment/deploymentInDepth/jnlp.html) for the game launches.
This protocol requires for every .jar file used to be digitally signed. Please include a file that does the signing on your behalf, and store it in ```public/sign_data_pack.sh```. Also, you should put the keystore passowrd in .env file,
aliased as KEYSTORE_PASS.

## Deploying
You can run either  ```php artisan serve``` or set up a symbolic link to ```/path/to/project/public``` directory and navigate to http://localhost/{yourLinkName}


## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](http://laravel.com/docs/contributions).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell at taylor@laravel.com. All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
