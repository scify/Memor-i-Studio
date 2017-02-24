# Memor-i Studio
<p align="center">
<img src="https://raw.githubusercontent.com/scify/Memor-i/master/src/main/resources/img/memori.png" width="400">
</p>
<br>
Memor-i is a Memory card game especially tailored to meet the needs of blind people.

Memor-i Studio, is an online games repository that people can use in order to create their own flavors of Memor-i game!


###Installing dependencies (assuming apache as web server and mysql as db):

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
We achieve that by using [npm](http://blog.npmjs.org/post/85484771375/how-to-install-npm).
Read [this](https://www.digitalocean.com/community/tutorials/how-to-install-node-js-on-an-ubuntu-14-04-server) link in order to understand how npm should be installed.
In order for npm to run, we need to also install [Node.js](https://nodejs.org/en/)
Usually, you can install npm and Node.js by running:
```
apt-get install nodejs
apt-get install npm
```

If you prefer installing npm and/or Node.js through [homebrew](http://brew.sh/) or [linuxbrew](http://linuxbrew.sh/), read [this](http://blog.teamtreehouse.com/install-node-js-npm-linux).

So, when in project root directory, and after npm has been installed correctly, run
```
npm install
```
To download and install all libraries and dependencies.

## Compiling assets

This project uses [Elixir](https://laravel.com/docs/5.3/elixir) which is a tool built on [Gulp](http://gulpjs.com/),
a popular toolkit for automating painful or time-consuming tasks, like SASS compiling and js/css concatenation and minification.

To install gulp and gulp-cli (command line interface), please read [this](https://github.com/gulpjs/gulp/blob/master/docs/getting-started.md).

Then, when in project root directory, run 
```gulp --local```
In order for the assets to compile. Also, by running
```gulp watch --local```
A watcher is set for when a file is changed to be compiled automatically.

## Converting audio files to mpr with CBR (constant bit rate)
This project allows users to upload audio files. In order for the desktop application of Memor-i to operate correclty,
these files need to me in .mp3 format and have a CBR (constant bit rate), not a VBR (variable bit rate). [See more](https://www.lifewire.com/difference-between-cbr-and-vbr-encoding-2438423)
This project converts uploaded audio files appropriately, on the fly. For this to happen, we use [avconv](https://libav.org/avconv.html) library.
To install this library in a Unix-based machine, check [this post](http://askubuntu.com/questions/391357/how-do-you-install-avconv-on-ubuntu-server-13-04).

You can see and modify the command we use for coverting the files in ```public/convert_to_mp3.sh```.

## Converting image files to .ico files
This project includes special functionality to convert a game flavor cover image file into a .ico file, for usage when the game runs.
In order to accomplish this, we use [ImageMagick tool](https://github.com/ImageMagick/ImageMagick/blob/master/LICENSE).
ImageMagick can be installed like this:
```apt-get install imagemagick```

##Building Windows executables for game flavors
When an admin user publishes a game flavor, A .jar file is built for this game flavor. In addition, this application uses
[Launch4J](http://launch4j.sourceforge.net/) in order to build also the windows executable and [Inno setup](http://www.jrsoftware.org/isinfo.php) to build the installer (version used: 5.5.9). 
The launch4J application is included in ```public/build_app/launch4j``` as a standalone application. 
Make sure you also install Wine on your server via [WINE for Linux](https://www.winehq.org/)

When calling the innosetup script located in ```public/build_app/innosetup/iscc.sh``` we pass as a parameter the current system user.
This user has to be set in .env file:
```
APP_LOG_LEVEL=debug
APP_URL=http://localhost
SYSTEM_USER=testUser
...
```

Also, the innosetup script will try to install Innosetup automatically. For this to happen, you have to make sure that the innosetup setup file
is present on your machine, and include the file path in the .env file:
```
...
INNOSETUP_SETUP_FILE = /home/pisaris/Downloads/innosetup-5.5.9.exe
...
```

And have write access to ```/home``` directory.

## Deploying
You can run either  ```php artisan serve``` or set up a symbolic link to ```/path/to/project/public``` directory and navigate to http://localhost/{yourLinkName}


## License

This project is open-sourced software licensed under the [Apache License, Version 2.0](https://www.apache.org/licenses/LICENSE-2.0).
