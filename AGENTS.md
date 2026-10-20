# AGENTS.md

Guidance for AI coding agents working in this repository. `CLAUDE.md` imports this file. Commands, setup and
deployment are in `README.md`; changes are recorded in `CHANGELOG.md`.

## Project

Memor-i Studio (<https://memoristudio.scify.org/>): a Laravel app where members create their own games for the
Memor-i desktop memory game for blind people. A **game** (`GameFlavor`, table `game_flavor`) is based on a **base
version** (`GameVersion`, managed by admins) and holds **card sets** (`EquivalenceSet`) of **cards** (`Card`) with
images and sounds. An admin builds a game (Linux jar, Windows installer) and publishes it; the desktop game reads
published games through the API.

## Working rules

- **Run everything in DDEV** (`ddev composer …`, `ddev artisan …`, `ddev npm …`, `ddev exec …`). The host has a
  different PHP.
- **Ask Paul before `ddev start`, `ddev restart` and `ddev import-db`.** Start and restart run `composer setup` and
  `php artisan migrate` on the DDEV database (post-start hook); `import-db` replaces the database.
- **The developer's DDEV database is Paul's.** Ask before `migrate`, `db:seed` (also a single seeder class) or tinker
  writes against it. For browser checks, create temporary users and data, and delete them afterwards by id.
- **Commit gate**: run each tool as its own command and read its exit code, then commit in a separate command:
  `ddev composer lint:check`, `ddev npm run build`, and the tests (`ddev exec php artisan config:clear`, then
  `ddev exec env COLUMNS=120 php artisan test --compact`).
- Without a terminal, DDEV sets `COLUMNS=0`, and Collision crashes when it renders a failure; scripted test runs pass
  `COLUMNS` (`ddev coverage` does this itself).
- When a change removes code that a `phpstan-baseline.neon` entry ignores, remove that entry in the same commit
  (PHPStan fails on unmatched entries).
- New npm packages: pick versions released at least 7 days ago; `.npmrc` `min-release-age=7` refuses newer ones.
- `composer require` / `composer update` without `-W`; use `-w` with the exact packages instead.
- `master` accepts only pull requests with a passing `ci / CI` check (ruleset). Work on a branch; ask before every
  push, force push, tag and release. Merge with a merge commit: `.git-blame-ignore-revs` lists a commit hash.
- Commits are authored by Paul only, without `Co-Authored-By` lines. Pull request descriptions have no "Test plan"
  section and no mention of AI tools.
- Every user-visible change gets an entry under `## [Unreleased]` in `CHANGELOG.md`; deployment-relevant changes also
  get an upgrade note. Releases follow "Releasing" in `README.md`.

## Tests

- **Tests run on SQLite in memory only** (README "Tests"). Keep all three guards: the `<server>` lines in
  `phpunit.xml` (DDEV's `DB_*` container variables beat `<env>` lines), the connection check in `tests/TestCase.php`
  (`setUpTraits()`) and the cached-config check in `tests/CreatesApplication.php`.
- **Every database test sets `protected bool $seed = true;`.** `RefreshDatabase` migrates once per run and seeds only
  on that first migration, so one test without `$seed` leaves every later test without roles and staff users.
  Seeding needs `DEFAULT_ADMIN_USER_PASSWORD_FOR_SEED`, which `phpunit.xml` sets.
- Helpers: `tests/Feature/Concerns/CreatesGameData.php` (users with a role, base version, games, card sets, cards);
  `UsesTemporaryStorage.php` for every test that writes files (it moves `storage_path()` and the disk roots).
- The `mimetypes` rules read the file content, so an `UploadedFile::fake()` audio file fails them; build the content
  (see the MP3 frames in `tests/Feature/EquivalenceSetCreateTest.php`).
- Laravel skips CSRF and TrustHosts in tests: `tests/Unit/CsrfExceptionsTest.php` checks the CSRF
  exceptions directly; check request-level behaviour in a browser with real sessions.
- For access rules, remove the check once and watch the test fail. Many controllers answer a failed action with
  `common.error_message` and status 200, so assert the real result (a redirect without `flash_message_failure`, or the
  expected view), not "not 403".

## Domain and architecture

- Controllers → `app/BusinessLogicLayer/managers/*Manager` → `app/StorageLayer/*Storage`.
- Roles: `role_id` 1 member, 2 admin (`User::isAdmin()`); `role_id` is not mass-assignable on purpose.
- Access (`app/Policies/GameFlavorPolicy.php`): `manage` = creator or admin (edit, delete, submit for approval, card
  sets, cards, sounds); `publish` = `manage` and `is_built`; `clone` = `manage`, or published with `allow_clone`;
  `view` = published, or `manage` (game files, downloads). Gate `manage-platform` (admin): builds, base version change,
  base version pages, `administration/*`.
- Authorize against the stored record: a card set's game is `flavor_id` (`EquivalenceSet::gameFlavor()` is defined
  wrongly), a card's game comes through its set. Ids that build storage paths are validated as integers first: MySQL
  compares `'5/../x'` as equal to `5`.
- Game files live under `storage/app/data_packs/additional_pack_{id}/`, base version files under
  `storage/app/game_versions/{data,img,jar,exe}/`; `resolveData` serves only these, game files by `view`.
- External programs run through `Process` with argument arrays; stored upload names keep only `[a-z0-9_-]`
  (`ResourceManager::slugifyFileName()`).
- A clone starts unbuilt and not submitted (`GameFlavorManager::cloneGameFlavor()`).
- The desktop game API is the `api/*` group in `routes/web.php` (`web` middleware, CSRF-exempt). Responses are
  `ApiOperationResponse` objects with `ServerResponses` codes. The game polls every 200 ms, so in that group only
  `player/login` and `player/register` are throttled (`player-auth` in `RouteServiceProvider`, keyed by
  `App\Support\RateLimitAddress`). Add game endpoints there: `routes/api.php` (`GET api/games`, analytics) has the
  `api` group's `throttle:60,1`. Players are not authenticated (see Known issues); ids in a request are checked
  against the game request (`GameRequest::hasPlayer()`).
- The servers set the visitor address in nginx (Cloudflare `real_ip`); the app trusts no proxy.

## Frontend

- Vite entries are listed in `vite.config.js`; Blade loads them with `@vite()`. The dev server runs in DDEV on port
  5176.
- The markup and CSS are Bootstrap 3 (`data-toggle`, `.modal('toggle')`), so the JavaScript comes from
  `bootstrap-sass` 3.4.3 (Bootstrap 3.4.1); keep Bootstrap 4 or 5 packages out.
- jQuery: `resources/assets/js/jquery-global.js` sets `window.$`; modules import `$`. Inline Blade scripts that use
  jQuery are `type="module"` (`tests/Unit/InlineScriptsTest.php` enforces it).
- `resources/assets/pleasure-admin-panel/js/pleasure.js` imports the jQuery UI widget files in dependency order:
  without AMD they load no dependencies themselves.
- Pass server data to scripts with `@json` and insert it with jQuery `.attr()` / `.text()`; stored file names can
  contain quotes from older uploads.
- `public/vendor/scify` (cookie consent) is published by Composer on every install and is not in git.

## Known issues

- The desktop player API has no player authentication: any client can act as any player, and `gameRequest/end` and
  `gameRequest/cancel` accept any request id. A fix needs a player token in the Memor-i desktop client.
- The `jar`/`exe` files of base versions and the configuration files at the root of a game pack are readable by
  everyone who can view the game.
- `phpstan-baseline.neon` holds about 50 PHPDoc and relation findings of the old code.
