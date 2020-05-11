# NamelessMC - v2 pre-release 7
NamelessMC is a free, easy to use & powerful website software for your Minecraft server, which includes a large range of features.

NamelessMC version 2.0.0 is still a pre-release, and is not yet recommended for production use. Keep up to date with development in our [Spigot thread](https://www.spigotmc.org/threads/nameless-minecraft-website-software.34810) and our [Discord server](https://discord.gg/k8J97xf).

The official project website, providing support and additional downloads such as modules and templates, can be found at [https://namelessmc.com/](https://namelessmc.com/).

## Features
The following list is a brief summary of the features available in v2 pre-release 5.
- API - if used in conjunction with the [Nameless Plugin for v2](https://www.spigotmc.org/resources/nameless-plugin-for-v2.59032/), integration with your Minecraft server can be provided
- Custom page system - create your own HTML pages and even restrict access to them depending on rank
- Forum system
- Minecraft integration - possible to verify registered accounts, allow offline mode accounts, and even disable altogether
- New powerful module system allowing for further NamelessMC integration
- New template and language systems, allowing for total customisation
- Pretty URL option (requires mod_rewrite)

#### Customising Nameless
Developer documentation is coming soon, both for module and template development.

## Installation
Installing NamelessMC v2 is fairly straightforward, provided you already have a web host.

#### Requirements
- PHP 5.4+ (7.2+ recommended) with:
    - php-curl
    - php-exif (optional)
    - php-gd
    - php-mbstring
    - php-mysql or php-mysqlnd
    - php-pdo
    - php-xml
- A MySQL database

#### Installation Instructions
1) Download the latest release from [https://github.com/NamelessMC/Nameless/releases](https://github.com/NamelessMC/Nameless/releases)
2) Unzip + upload the contents to your web host
3) Visit your website in your web browser and follow the installer's instructions

#### Updating from NamelessMC v1
Please follow instructions in your v1 website's AdminCP -> Update tab on updating to v2.

#### Converting from other forum software
Currently there are no conversion scripts available, however these are planned and will be available in the future.

## Support
Support can be found in one of the following places:
- Discord [<img src="https://discordapp.com/api/guilds/246705793066467328/widget.png?style=shield">](https://discord.gg/QWdS9CB)
- [Official support forum](https://namelessmc.com/forum)
- [SpigotMC](https://www.spigotmc.org/threads/nameless-minecraft-website-software.34810/)

Feature requests can be posted on the [forum](https://namelessmc.com/forum/view/7-web-feature-requests/), and bugs can be reported in the [GitHub Issues](https://github.com/NamelessMC/Nameless/issues) tab.

## Plugin
For Minecraft integration, you can install the Nameless Plugin in your Spigot server. Currently the plugin is only available for spigot, but we're working on bringing it to different server software, such as Sponge and BungeeCord. You can find a list of features and installation instructions in the [plugin readme](https://github.com/NamelessMC/Nameless-Plugin/blob/master/README.md)

## Translations
NamelessMC translations are kindly provided by the community. Currently available translations can be found [here](https://github.com/NamelessMC/Nameless/tree/v2/custom/languages). Please note, not all translations may be up to date.

#### Contributing translation updates
If you would like to assist with the NamelessMC development by providing an updated translation, please feel free to fork the repository here on GitHub and create any pull requests.

#### Translation credits
Translation credits can be found within CONTRIBUTORS.md

## Special Thanks
- All NamelessMC contributors (CONTRIBUTORS.md)
- [JetBrains](https://www.jetbrains.com/), whose products are used to develop the NamelessMC project