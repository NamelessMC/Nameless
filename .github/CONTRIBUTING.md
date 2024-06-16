# Contributing
We welcome all contributions of code and translations. Please feel free to fork the repository on GitHub and create any pull requests!

Document in progress!

## Tips
Here are some things you should know when contributing:
- We generally keep a todo list in the Milestones tab of the Issues page.
- We use Composer to manage dependencies. Before you can install the dependencies, you need to install Composer on your local computer. Installation instructions are [here](https://getcomposer.org/doc/00-intro.md).
    - To install the Composer packages we depend on, run the following command in the root directory of the NamelessMC repository:
        ```console
        composer install --dev
        ```
        - This could take up to about a minute depending on your internet connection.
- We use npm to manage frontend dependencies.
    - Use `npm ci` to install dependencies exactly according to `package-lock.json`, or `npm install` to install the latest compatible versions.
- You can use the CLI (command line) install script to reset your development environment in less than 5 seconds!
    - Run the following command when in the root directory:
        ```console
        php scripts/cli_install.php --iSwearIKnowWhatImDoing --reinstall
        ```
- To populate the database with some fake data, you can use the seeder.
    - Run the following command when in the root directory:
        ```console
        php scripts/seeder/db_seeder.php --wipe
        ```
        - This will wipe the database and populate it with lots of fake users, forums, and much, much more.
        - *Note: You can modify the number of different records to be created in each seeder by editing the defined variables in `scripts/seeder/db_seeder.php`*
        - *Note: Login to the admin account with `admin@localhost` and `password` after running the seeder*
- To make changes to the database schema (add, modify or remove a table), please create a new migration with Phinx:
    - Run the following command when in the root directory:
        ```console
        vendor/bin/phinx create MigrationNameInCamelCase -c core/migrations/phinx.php
        ```
    - This will create a new migration file in the `./core/migrations` directory, where you can use the Phinx table builder to make your changes.
      Please try to stick with the conventions of pre-existing migrations.
    - To execute the migration, run the following command:
      ```console
      vendor/bin/phinx migrate -c core/migrations/phinx.php
      ```

## Versioning
As of NamelessMC 2.0.0, we use a unique versioning system.
Similar to [semver](https://semver.org), we follow the `major.minor.patch` versioning pattern, however there are a few things to note:
- We use the naming `constant.major.patch` compared to semver.
- The `constant` version is pinned at `2` for the time being.
- The `major` version is only changed when *breaking changes are made*. These include:
  - Changes that require modules to update
  - Changes that require templates to update
  - Changes that require updates to the Nameless-Link Discord bot
  - Changes that require updates to the Nameless-Link Discord bot
  - Changes that require updates to the debug.namelessmc.com repo
  - **If the minor version is bumped, we will inform users about which of the above aspects were affected.**
- The `patch` version will change for any releases which do not include breaking changes, in our case this will generally mean a bug fix release.

Examples:
- `2.0.1`
  - A bug fix release. Fully backwards compatible with `2.0.0` modules, templates & other integrations.
- `2.1.0`
  - A "major" release. Modules and/or templates and/or other integrations will need to be updated to support this release.
- `2.1.4`
    - A bug fix release. Fully backwards compatible with `2.1.0` modules, templates & other integrations, but not compatible with (at least 1) `2.0.0` integration

Deprecations rule of thumb:
- We might deprecate methods, classes or constants within a minor release.
- These will get tagged with `@deprecated` and will be announced.
- When something is marked as deprecated, it will not be removed until at least the next major release.

## Backporting

* Security fixes and bug fixes should always be backported, if possible.
* Enhancements should usually be backported. New features should rarely be backported. Changes involving language files should be avoided, Weblate only updates the development branch.
* Changes should be as small as possible to reduce conflicts.
* Squash when merging. This makes backporting easier. If you think your changes deserve multiple commits, consider splitting them into multiple pull requests.
* Mark the pull request with the appropriate milestone. [@Derkades](https://github.com/Derkades) keeps an eye on merged PRs and cherry-pick changes to the appropriate release branch.

## Adding new modules to core

After adding a new module to core, you need to do the following:
1. Update the `Dockerfile.phpdoc` file to include the new module classes folder (this generates our [PHPDoc](https://phpdoc.namelessmc.com/) site)
2. Update `composer.json` to autoload the new module classes folder
3. Add a new term to the `modules/Core/language/en_UK.json` file for the module description to be shown during instal
    - The term should be in the format `module_{module_name}_description`
    - Don't forget to add it to the `WHITELISTED_TERMS` array in `dev/scripts/find_unused_language_terms.sh`
4. Create new database entry to install it by default
    - Update `core/installation/includes/upgrade_perform.php` around line 637
    - Add a new entry in `core/classes/Database/DatabaseInitialiser.php` around line 83

## Releasing a new version

1. Ensure you have a clean copy of the source code without leftover files from testing. For example, clone the Nameless repository into a new directory
2. Run ./dev/scripts/release.sh. Release zip files are produced and placed in `./release`.
3. TODO: Add instructions for publishing a release
