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
- You can use the CLI (command line) install script to reset your development environment in less than 5 seconds!
    - Run the following command when in the root directory:
        ```console
        php scripts/cli_install.php --iSwearIKnowWhatImDoing --reinstall
        ```
- To populate the database with some fake data, you can use the seeder.
    - Run the following command when in the root directory:
        ```console
        php scripts/seeder/db_seeder.php wipe
        ```
        - This will wipe the database and populate it with lots of fake users, forums, and much, much more.
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
As of NamelessMC 2.0.0 (to be released at time of writing), we use a unique versioning system.
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
