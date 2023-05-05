# Memor-i Studio

<p align="center">
<img src="https://raw.githubusercontent.com/scify/Memor-i/master/src/main/resources/img/memori.png" width="400" alt="game logo">
</p>
<br>

[Memor-i](https://www.youtube.com/watch?v=uRpUeqyN1eA) is a Memory card game especially tailored to meet the needs of
blind people.

[Memor-i Studio](https://memoristudio.scify.org/), is an online games repository that people can use in order to create
their own flavors of Memor-i game!

## Installation Instructions

Please read thoroughly the following guid/steps, to set up Memor-i Studio.

## Pre-installation requirements

This project assumes working installations of the following technologies / components:

1. [PHP](https://php.net/) (version >= `7.2`)

2. [MySQL](https://www.mysql.com/)

3. [composer](https://getcomposer.org/) (PHP package manager)

4. [npm](https://www.npmjs.com/get-npm) (Front-end dependencies package manager)

5. [Laravel](https://laravel.com/)

6. [Apache Web Server](https://httpd.apache.org/) (or any other Web Server you are comfortable with)

## First time install (setup database and install dependencies)

### Language and Framework Dependencies

Make sure php `7.2` is installed.

Install the [Laravel IMAP](https://github.com/Webklex/laravel-imap#installation) dependencies:

```bash
sudo apt-get install php*-imap php*-mbstring php*-mcrypt && sudo apache2ctl graceful
```

After cloning the project, create an `.env` file (should be a copy of `.env.example`), containing the information about your database name and credentials.

### Environment file, Laravel/backend dependencies

After cloning the project, create an .env file (should be a copy of .env.example), containing the information about your
database name and credentials. After that, download all Laravel dependencies
through [Composer](https://laravel.com/docs/7.x/installation), by running

```bash
composer install

composer update
```

### Front-end dependencies


After all Laravel dependencies have been downloaded, it's time to download all Javascript libraries and dependencies. We
achieve that by using nodejs and its package manager, [npm](https://www.npmjs.com/).

A convenient way of installing multiple versions of nodejs and npm on a machine, is by installing and using Node Version
Manager, [nvm](https://github.com/nvm-sh/nvm).

So, when in project root directory, and after npm has been installed correctly, run

It is very easy to install multiple versions of NodeJS and npm, by using [Node Version Manager (nvm)](https://github.com/creationix/nvm).

If you are using [`nvm`](https://github.com/nvm-sh/nvm), run this command in order to sync to the correct NodeJS version
for the project:

```bash
nvm install

nvm use 

npm install
```

To download and install all libraries and dependencies.

### Compiling front-end assets

This project uses [Webpack and Laravel Mix](https://laravel.com/docs/7.x/mix) which is a popular toolkit for automating painful or time-consuming tasks, like SASS compiling and js/css concatenation and
minification.

Since it is built upon webpack, you can use the following commands to compile the front-end assets:

```bash
npm run dev #for dev builds

npm run prod #for production builds

npm run watch #for dev builds, also enables hot changes on the files 
```

### Database

Create the database schema:

```bash
php artisan config:clear

php artisan migrate
```

#### Add seed data to DB

Run ```php artisan db:seed``` in order to insert the starter data to the DB by
using [Laravel seeder](https://laravel.com/docs/7.x/seeding)

### Permissions

Fix permissions for storage directory:

```bash
sudo chown -R ${USER}:www-data storage

sudo chmod -R 755 storage bootstrap/cache

cd storage/

sudo find . -type f -exec chmod 664 {} \;

sudo find . -type d -exec chmod 775 {} \;
```

The commands above are also available with the permissions script, in the root directory of the project. 

You can use it like this:

```bash
sudo ./set-file-permissions.sh www-data project_memori .
```

**Note:** `project_memori` should be the name of the server user.

## Apache configuration (optional)

```
cat /etc/apache2/sites-available/memoristudio.conf

<VirtualHost *:80>
    ServerName dev.memoristudio
    DocumentRoot "/path/to/memoristudio/public"
    <Directory "/path/to/memoristudio/public">
        AllowOverride all
    </Directory>
</VirtualHost>
```

Make the symbolic link:

```bash
cd /etc/apache2/sites-enabled && sudo ln -s ../sites-available/memoristudio.conf
```

Enable mod_rewrite and restart apache:

```bash
sudo a2enmod rewrite && sudo service apache2 restart
```

## Without apache custom configuration

Navigate to the root directory of the project and run:

```bash
php artisan serve
```

and navigate to [localhost:8000](http://localhost:8000/).

## Converting audio files to mpr with CBR (constant bit rate)

This project allows users to upload audio files. In order for the desktop application of Memor-i to operate correctly,
these files need to me in .mp3 format and have a CBR (constant bit rate), not a VBR (variable bit rate)
. [See more](https://www.lifewire.com/difference-between-cbr-and-vbr-encoding-2438423)
This project converts uploaded audio files appropriately, on the fly. For this to happen, we
use [avconv](https://libav.org/avconv.html) library. To install this library in a Unix-based machine,
check [this post](http://askubuntu.com/questions/391357/how-do-you-install-avconv-on-ubuntu-server-13-04).

You can see and modify the command we use for coverting the files in ```public/convert_to_mp3.sh```.

## Converting image files to .ico files

This project includes special functionality to convert a game flavor cover image file into a .ico file, for usage when
the game runs. In order to accomplish this, we
use [ImageMagick tool](https://github.com/ImageMagick/ImageMagick/blob/master/LICENSE). ImageMagick can be installed
like this:
```apt-get install imagemagick```

## Building Windows executables for game flavors

When an admin user publishes a game flavor, A .jar file is built for this game flavor. In addition, this application
uses
[Launch4J](http://launch4j.sourceforge.net/) in order to build also the windows executable
and [Inno setup](http://www.jrsoftware.org/isinfo.php) to build the installer (version used: 5.5.9). The launch4J
application is included in ```public/build_app/launch4j``` as a standalone application. Make sure you also install Wine
on your server via [WINE for Linux](https://www.winehq.org/)

When calling the innosetup script located in ```public/build_app/innosetup/iscc.sh``` we pass as a parameter the current
system user. This user has to be set in .env file:

```
APP_LOG_LEVEL=debug
APP_URL=http://localhost
SYSTEM_USER=user
...
```

And have write access to ```/home``` directory.

## Installing wine

- Setup a user where wine will be installed (non-system user), e.g. `project_memori`
- Run `isccBaseSetup.sh` in a shell allowing X server connections (Use e.g. ssh -X project_memori@myserver.gr to get such
  a shell)
  example: ```./isccBaseSetup.sh ~/Downloads/innosetup-5.5.9.exe```
- Change the owner of the user's `.wine` subdirectory to `www-data` (e.g. `chown -R www-data /home/project_memori/.wine/`)
- The user `www-data` can now run the `public/build_app/innosetup/iscc.sh` script with the following arguments to package project_memori: the user
  name (eg project_memori)
  and the `.iss` script file path of the game flavor

## Deploying

You can run either  ```php artisan serve``` or set up a symbolic link to ```/path/to/project/public``` directory and
navigate to http://localhost/{yourLinkName}

## License

This project is open-sourced software licensed under
the [Apache License, Version 2.0](https://www.apache.org/licenses/LICENSE-2.0).

Memor-i studio has been created by [Science For You (SciFY)](https://www.scify.org), a Greek not-for-profit
organization.

The Memor-i Studio project has been funded
by [Public Benefit Foundation John S. Latsis](https://www.latsis-foundation.org/eng)
<br>
<p align="center">
<img src="https://raw.githubusercontent.com/scify/memori-online-games-repository/master/public/assets/img/latsis_logo.jpg" width="300" alt="logo">
</p>
