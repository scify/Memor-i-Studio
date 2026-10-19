# Memor-i Studio

<p align="center">
<img src="https://raw.githubusercontent.com/scify/Memor-i/master/src/main/resources/img/memori.png" width="400" alt="game logo">
</p>
<br>

[Memor-i](https://www.youtube.com/watch?v=uRpUeqyN1eA) is a Memory card game especially tailored to meet the needs of
blind people.

[Memor-i Studio](https://memoristudio.scify.org/), is an online games repository that people can use in order to create
their own flavors of Memor-i game!

## Requirements

- [DDEV](https://ddev.readthedocs.io/) with Docker. DDEV provides PHP 8.4, MySQL 8.0, Node (version from `.nvmrc`),
  Composer, ImageMagick and ffmpeg.

## Getting started

1. Clone the repository and start DDEV:

   ```bash
   git clone https://github.com/scify/Memor-i-Studio.git
   cd Memor-i-Studio
   ddev start
   ```

   Every `ddev start` runs `composer setup` and then the database migrations. `composer setup` creates `.env` from
   `.env.example` (only if `.env` is missing), installs the PHP dependencies and, when `package-lock.json` changed, the
   npm dependencies (`npm ci`), generates `APP_KEY` (only if it is empty) and builds the frontend. It is safe to run
   again at any time, also by hand: `ddev composer setup`. DDEV writes the database and `APP_URL` settings into
   `.env`.

2. Edit `.env` if you need the external services (`WINDOWS_SETUP_SERVICE_URL`, Sentry, Firebase). DDEV catches all
   mail in [Mailpit](https://mailpit.axllent.org/) (`ddev mailpit`).

3. Create the starter data:

   ```bash
   ddev artisan db:seed
   ```

4. For frontend work, start the Vite dev server (hot reload through port 5176):

   ```bash
   ddev npm run dev
   ```

   `ddev composer setup` (and every `ddev start`) builds the assets for production with `npm run build`.
   `VITE_*` values in `.env` are read at build time, so set them before you build.

5. Open <https://memori-studio.ddev.site:8443>.

### Everyday commands

| Task | Command |
|---|---|
| Start or stop the environment | `ddev start`, `ddev stop` |
| Run Artisan, Composer or npm in the container | `ddev artisan ...`, `ddev composer ...`, `ddev npm ...` |
| Open a MySQL shell | `ddev mysql` |
| Open Mailpit (all outgoing mail) | `ddev mailpit` |
| Show URLs and services | `ddev describe` |

### npm supply-chain settings

`.npmrc` hardens npm for this project:

- `ignore-scripts=true`: npm does not run install scripts of dependencies
- `min-release-age=7`: npm installs only package versions that are at least 7 days old
- `engine-strict=true`: npm refuses to install with a Node or npm version outside `engines` in `package.json`

Use `npm ci` to install exactly what `package-lock.json` specifies (`composer setup` does this).

## Tests

```bash
ddev composer test   # run the test suite
ddev coverage        # the same, with a coverage summary and an HTML report in coverage/
```

The tests run on SQLite in memory (see the `<server>` values in `phpunit.xml`). They never use the DDEV MySQL database:
`<server>` values override the database variables that DDEV sets in the container, `tests/TestCase.php` refuses any
database except SQLite in memory, and the tests refuse to start with a cached configuration (`composer test` clears it
first). `ddev coverage` turns Xdebug on for the run and then restores its previous state (also when a test fails),
because Xdebug slows down every request.

## Server setup

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

## Required steps for the server

### 1. Allow the uploading large files

In order for the app to work as expected, max size of files and timeout time must be set on the appropriate
configuration files for `php-fpm` and `nginx`.

1. For nginx

   edit the `/etc/nginx/sites-enabled/memoristudio.server.org` file and add `fastcgi_read_timeout 300;`
2. For php-fpm

   edit `/etc/php/8.4/fpm/` (or the corresponding php version) and change:

```text
 max_input_time = 300
 
 post_max_size = 200M
 
 upload_max_filesize = 200M
```

### 2. Converting audio files to mpr with CBR (constant bit rate)

This project allows users to upload audio files. In order for the desktop application of Memor-i to operate correctly,
these files need to me in .mp3 format and have a CBR (constant bit rate), not a VBR (variable bit rate)
. [See more](https://www.lifewire.com/difference-between-cbr-and-vbr-encoding-2438423)
This project converts uploaded audio files appropriately, on the fly. For this to happen, the server needs
[ffmpeg](https://ffmpeg.org/) (`apt-get install ffmpeg`) or the older `avconv`; the script uses `avconv` when it is
installed. DDEV installs ffmpeg.

You can see and modify the command we use for converting the files in `public/convert_file_to_mp3.sh`.

### 3. Converting image files to .ico files

This project includes special functionality to convert a game flavor cover image file into a .ico file, for usage when
the game runs. In order to accomplish this, we
use [ImageMagick tool](https://github.com/ImageMagick/ImageMagick). ImageMagick can be installed
like this:
```apt-get install imagemagick```

### 4. Building Windows executables

For building the Windows installer for a game, this project
uses [Inno Setup-exe-builder](https://github.com/scify/innosetup-exe-builder?tab=readme-ov-file#inno-setup-exe-builder),
an external service for compiling executables to Windows installers.

So you will need to setup and have this service running, and then change the `WINDOWS_SETUP_SERVICE_URL` variable in `.env`. 

<hr>

## Deploying

The server needs PHP 8.4 or newer (`composer.lock` requires it), MySQL 8.0, and Node with the version from `.nvmrc`.
Point the web server to the `public` directory.

After every `git pull`, install the PHP dependencies with Composer scripts enabled:

```bash
composer install --no-dev --optimize-autoloader
```

The `post-autoload-dump` script publishes the cookie-consent assets into `public/vendor/scify`, which git does not
track. Without them every page answers 500, so do not use `--no-scripts`, and give the deploy user write access to
`public/vendor`.

## Required steps for Production server

### Required steps for uploading large files

In order for the app to work as expected, max size of files and timeout time must be set on the appropriate
configuration files for `php-fpm` and `nginx`.

1. For nginx

   edit the `/etc/nginx/sites-enabled/memoristudio.scify.org` file and add `fastcgi_read_timeout 300;`
2. For php-fpm

   edit `/etc/php/VERSION/fpm/` (or the corresponding php version) and change:
    * max*input*time = 300
    * post*max*size = 200M
    * upload_max_filesize = 200M

3. Make sure that the `project_memori` user belongs to group `www-data`

### Mailgun Considerations

Make sure that the production server IP is whitelisted in Mailgun.

## License

This project is open-sourced software licensed under
the [Apache License, Version 2.0](https://www.apache.org/licenses/LICENSE-2.0).

Memor-i Studio has been created by [Science For You (SciFY)](https://www.scify.org), a Greek not-for-profit
organization.

The Memor-i Studio project has been funded
by [Public Benefit Foundation John S. Latsis](https://www.latsis-foundation.org/eng)
<br>
<p align="center">
<img src="https://raw.githubusercontent.com/scify/memori-online-games-repository/master/public/assets/img/latsis_logo.jpg" width="300" alt="logo">
</p>
