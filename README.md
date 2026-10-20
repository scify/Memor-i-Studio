# Memor-i Studio

[![CI](https://github.com/scify/Memor-i-Studio/actions/workflows/ci.yml/badge.svg?branch=master)](https://github.com/scify/Memor-i-Studio/actions/workflows/ci.yml)
[![Security](https://github.com/scify/Memor-i-Studio/actions/workflows/security.yml/badge.svg)](https://github.com/scify/Memor-i-Studio/actions/workflows/security.yml)
[![License](https://img.shields.io/badge/License-Apache%202.0-blue.svg)](LICENCE.md)

<p align="center">
<img src="https://raw.githubusercontent.com/scify/Memor-i/master/src/main/resources/img/memori.png" width="400" alt="game logo">
</p>

[Memor-i](https://www.youtube.com/watch?v=uRpUeqyN1eA) is a memory card game for blind people.
[Memor-i Studio](https://memoristudio.scify.org/) is the online repository where people create their own Memor-i
games: they upload card images and sounds, an administrator builds and approves each game, and the Memor-i desktop
game downloads the published ones.

## Contents

- [Tech stack](#tech-stack)
- [Requirements](#requirements)
- [Getting started](#getting-started)
- [Tests](#tests)
- [Code quality](#code-quality)
- [Continuous integration](#continuous-integration)
- [Deployment](#deployment)
- [License](#license)

## Tech stack

- Laravel 12 on PHP 8.4, MySQL 8.0
- Vite 7 with jQuery 3.7, Bootstrap 3.4 and the "Pleasure" admin theme (`resources/assets`)
- Node 24 (version in `.nvmrc`), npm with supply-chain settings in `.npmrc`
- External tools on the server: ffmpeg (audio), ImageMagick (game icons), zip/unzip (game packs), and the
  [Inno Setup exe builder](https://github.com/scify/innosetup-exe-builder) service for Windows installers

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

2. Edit `.env`:
   - `DEFAULT_ADMIN_USER_PASSWORD_FOR_SEED`: the password of the admin users that the seeders create (required for
     `db:seed`; it has no default)
   - the external services if you need them (`WINDOWS_SETUP_SERVICE_URL`, Sentry, Firebase). DDEV catches all mail in
     [Mailpit](https://mailpit.axllent.org/) (`ddev mailpit`).

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

`.npmrc` hardens npm for this project. CI checks these settings:

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

## Code quality

The project uses three tools:

- [Laravel Pint](https://laravel.com/docs/pint) for the PHP code style (rules in `pint.json`)
- [Larastan](https://github.com/larastan/larastan) (PHPStan) for static analysis of `app/` (settings in `phpstan.neon`;
  known errors are in `phpstan-baseline.neon`, and new code must not add entries)
- [ESLint](https://eslint.org/) for `resources/assets/js` (rules in `eslint.config.js`; the vendored theme JavaScript
  is not linted)

```bash
ddev composer lint:check   # run all three tools; report problems, change nothing
ddev composer lint:fix     # fix the PHP style and the JavaScript, then report what PHPStan still finds
```

`.git-blame-ignore-revs` lists the format-only Pint commit; GitHub skips it in blame views, and locally
`git config blame.ignoreRevsFile .git-blame-ignore-revs` does the same.

## Continuous integration

GitHub Actions run the shared [SciFY workflows](https://github.com/scify/.github):

- `ci.yml` (on pushes to `master` and on pull requests): Pint, PHPStan, ESLint, the Vite build and the tests on PHP 8.4
- `security.yml` (on pull requests and weekly): `composer audit`, `npm audit`, the npm supply-chain settings and
  Gitleaks

The `master` branch should require the check `ci / CI`; `dependabot_auto_merge.yml` relies on it.

## Deployment

### Server

- PHP 8.4 or newer (`composer.lock` requires it) with php-fpm, MySQL 8.0, and Node with the version from `.nvmrc`.
- The web server points to the `public` directory.
- ffmpeg (`apt-get install ffmpeg`): uploaded audio is converted to MP3 with a constant bit rate (CBR), which the
  desktop game needs ([CBR and VBR](https://www.lifewire.com/difference-between-cbr-and-vbr-encoding-2438423)). The
  command is in `public/convert_file_to_mp3.sh`; it uses the older `avconv` when that is installed.
- ImageMagick (`apt-get install imagemagick`): the cover image of a game becomes the `.ico` file of its Windows
  executable.
- The [Inno Setup exe builder](https://github.com/scify/innosetup-exe-builder?tab=readme-ov-file#inno-setup-exe-builder)
  service builds the Windows installers; set its address in `WINDOWS_SETUP_SERVICE_URL`.
- Mailgun sends the mail; the server's IP address must be on the Mailgun allow list.

Large uploads need these limits:

- nginx (the site file, for example `/etc/nginx/sites-enabled/memoristudio.scify.org`): `fastcgi_read_timeout 300;`
- php-fpm (`/etc/php/8.4/fpm/php.ini`): `max_input_time = 300`, `post_max_size = 200M`,
  `upload_max_filesize = 200M`

File permissions: the deploy user must belong to the web server group (for example `www-data`). The permissions script
adds the user to that group and sets the owner, the group, the modes and the setgid bit on the storage and cache
folders:

```bash
sudo ./set-file-permissions.sh www-data project_memori .
```

`project_memori` is the name of the server user.

### Production `.env`

- `APP_ENV=production` and `APP_DEBUG=false` (`.env.example` has local values; debug mode shows stack traces with
  configuration values).
- `APP_URL` is the public address (`https://memoristudio.scify.org`). The app answers only requests for this host and
  its subdomains; the desktop game uses the same host.
- The site runs behind Cloudflare. The app takes the client address from `CF-Connecting-IP` when the request comes from
  a Cloudflare address listed in `config/proxies.php`. If another proxy (for example a local nginx or load balancer)
  sits between Cloudflare and PHP, add its address to that list; otherwise every player shares one login limit.
  Then that proxy must accept only Cloudflare traffic (firewall or Authenticated Origin Pulls) or overwrite
  `CF-Connecting-IP` for other senders; otherwise a direct request can choose its own address.
- Update `config/proxies.php` when Cloudflare publishes new ranges (<https://www.cloudflare.com/ips/>).

### Updating

After every `git pull`, install the PHP dependencies with Composer scripts enabled:

```bash
composer install --no-dev --optimize-autoloader
```

The `post-autoload-dump` script publishes the cookie-consent assets into `public/vendor/scify`, which git does not
track. Without them every page answers 500, so do not use `--no-scripts`, and give the deploy user write access to
`public/vendor`.

Then build the frontend and run the migrations:

```bash
npm ci
npm run build
php artisan migrate --force
```

- `npm ci` must install the devDependencies (do not use `--omit=dev`): Vite and Sass are devDependencies.
- Every page answers 500 until `public/build/manifest.json` exists, so build before the site serves the new code, or
  use maintenance mode (`php artisan down` / `php artisan up`).
- Vite reads `VITE_*` values from `.env` at build time. Set them before `npm run build`; for example
  `VITE_SENTRY_DSN_PUBLIC` enables browser error reports to Sentry.

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
