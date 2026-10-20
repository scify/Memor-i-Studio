# Changelog

All notable changes to this project are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/), and this project adheres to
[Semantic Versioning](https://semver.org/spec/v2.0.0.html). The version is defined in `package.json`; see
"Releasing" in `README.md`.

## [Unreleased]

### Upgrade notes

This release changes how the application is installed, built, configured and secured. Before you deploy it to a
server that runs 2.0.0, in this order:

1. Back up the database and `storage/app`.
2. Server software: PHP 8.4 or newer (`composer.lock` requires it), Node 24.21.0 or newer (`.nvmrc`; npm refuses older
   versions), ffmpeg (or the older avconv) and ImageMagick.
3. If the server's working tree has local changes in `public/vendor/scify`, run `git checkout -- public/vendor/scify`:
   these files are no longer in git, and the pull deletes them.
4. `.env`, before the build:
   - set `VITE_SENTRY_DSN_PUBLIC` instead of `MIX_SENTRY_DSN_PUBLIC` (Vite reads it at build time);
   - set `APP_ENV=production`, `APP_DEBUG=false`, and `APP_URL=https://memoristudio.scify.org` (requests for other
     hosts now get HTTP 400);
   - delete `APP_VERSION` (the version comes from `package.json`) and `DOCKER_USER_ID`/`DOCKER_GROUP_ID`;
   - set `DEFAULT_ADMIN_USER_PASSWORD_FOR_SEED` only where you run `db:seed` (the staff users are seeded only in
     `local` and `testing`).
5. Check the proxy setup: the app trusts only the Cloudflare ranges in `config/proxies.php` (README "Deployment"). If
   another proxy sits between Cloudflare and PHP, add it there and make it accept only Cloudflare traffic.
6. Install and build, with Composer scripts enabled (they publish the cookie-consent assets; until then every page
   answers HTTP 500, so consider maintenance mode):

   ```bash
   composer install --no-dev --optimize-autoloader
   npm ci
   npm run build
   php artisan migrate --force
   ```

   `npm ci` must install the devDependencies. There are no new migrations; `migrate` reports nothing to do.
7. Delete the old Laravel Mix output: `public/js`, `public/css`, `public/mix-manifest.json`.
8. Run `sudo ./set-file-permissions.sh www-data <server user> .` once, so storage folders get the setgid bit.
9. Give every account that still uses the password of the old `UsersTableSeeder` a new password: its hash stays in
   the git history.

### Security

- Uploaded file names could inject shell commands into the audio and icon conversion; stored names now keep only
  letters, digits, `-` and `_`, and the conversions run without a shell.
- `resolveData` could read any file on the server with `../`; it now serves only game packs (with the game's access
  rule) and base version files.
- The public game lists (web and `GET /api/games`) showed unpublished games; the web list took the user from the
  request body.
- Members could change other members' games, card sets, cards and sounds, the base versions' texts and languages, and
  build or publish games without approval. A `GameFlavorPolicy` now decides: the creator or an admin manages a game,
  publishing needs a built game, builds and base versions are admin actions, and a clone starts unbuilt.
- Files and downloads of unpublished games are available only to their creator and admins.
- Actions that change data accept only POST or DELETE with a CSRF token; `gameVersion/*` no longer skips the CSRF
  check.
- The desktop player API no longer returns password hashes, checks that players belong to the game request, validates
  its input, and limits logins and registrations per address and user name. The polling routes stay unlimited.
- Behind Cloudflare, the client address comes from `CF-Connecting-IP`; TrustHosts accepts only the `APP_URL` host.
- `role_id` is no longer mass-assignable; the games page no longer embeds the full user row.
- The seeded staff users get the password from `DEFAULT_ADMIN_USER_PASSWORD_FOR_SEED` instead of a committed hash.
- Card preview images and game list errors are no longer built as HTML strings.

### Added

- DDEV for local development (`ddev start`), with `composer setup`, `composer test`, `ddev coverage`,
  `composer lint:check` and `composer lint:fix`.
- Shared SciFY CI and security workflows: Pint, Larastan (level 5, baseline), ESLint, the build, the tests, audits and
  Gitleaks.
- Feature and unit tests (SQLite in memory; the tests refuse any other database and a cached configuration).
- npm supply-chain settings (`.npmrc`: `ignore-scripts`, `min-release-age=7`, `engine-strict`).
- `CHANGELOG.md` and the "Releasing" section in the README.

### Changed

- Laravel Mix is replaced by Vite 7; the dev server runs on the DDEV port 5176.
- The JavaScript loads Bootstrap 3.4.1 from `bootstrap-sass`, as production did; unused npm packages are removed.
- PHP 8.4 and Node 24.21.0 are required.
- The app version comes from `package.json`.
- The card set page passes its data with `@json`; `laracasts/utilities` is removed. `madnest/madzipper` is installed
  in production.
- The code is formatted with Pint (one format-only commit, listed in `.git-blame-ignore-revs`).
- The README follows the shared SciFY layout.

### Fixed

- The desktop player API answered 500 on every route (constructor cycle between two managers).
- Online games could not read the opponent's moves.
- Failed builds reported success and sent the congratulations email.
- After a card edit, the card dialog posted the next new card set to the edit route.
- Game reports lost the comment, name and email.
- A failed card creation left an empty card set.
- `db:seed` failed (missing `DB` imports), migration rollbacks failed on MySQL and SQLite, and SQLite could not run
  the migrations.
- Audio conversion without `avconv` (ffmpeg fallback with the same constant bit rate).

### Removed

- Docker Compose (`docker-compose.yml`, `docker/`, `Makefile`).
- The debug and test routes `/testEmail`, `/debug-sentry`, `/storeUserAction` and
  `/administration/test-email/{email}`.

## [2.0.0]

The state of the application before release tags were introduced (commit `b03ba33`). It is not tagged.

[Unreleased]: https://github.com/scify/Memor-i-Studio/compare/b03ba33...HEAD
