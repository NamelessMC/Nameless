# NamelessMC - v2 pre-release 13
![NamelessMC Banner](https://i.imgur.com/gt8uezk.png)
NamelessMC is a free, easy to use & powerful website software for your Minecraft server, which includes a large range of features.

NamelessMC version 2.0.0 is still a pre-release, and is not yet recommended for production use. Keep up to date with development in our [Spigot thread](https://www.spigotmc.org/threads/nameless-minecraft-website-software.34810) and our [Discord server](https://discord.gg/nameless).

The official project website, providing support and additional downloads such as modules and templates, can be found at [https://namelessmc.com/](https://namelessmc.com/).

<img src="https://user-images.githubusercontent.com/26070412/137838580-168ebd24-a222-4a64-a220-d3029650f0ab.png" alt="Features" width="700">

The following list is a brief summary of the features available in v2 pre-release 13:
- 🙋 Forums
- 📃 Custom pages: create your own HTML pages and even restrict access to them depending on group.
- 👥 Social logins: allow your users to register/login with services such as Discord and Google.
- 🎮 Minecraft integration
  - Account verification using mcassoc or in-game the plugin
  - [In-game plugin](https://www.spigotmc.org/resources/nameless-plugin-for-v2.59032)
    - Require in-game verification for NamelessMC accounts
    - Allow registering for accounts in-game
    - Synchronize Vault ranks with NamelessMC groups (unidirectional, game -> website)
    - Display website announcements in chat
    - Whitelist players who have a website account
    - Ban players who are banned from a website
    - Display PlaceholderAPI placeholders on website user profiles or in leaderboards
- 🗨️ Discord integration
  - Webhook: receive updates for new members, forum posts, resources, etc.
  - [Nameless-Link Discord bot](https://docs.namelessmc.com/link/2.0/setup)
    - Link Discord accounts with NamelessMC accounts
    - Synchronize Discord roles with NamelessMC groups (bidirectional)
- ⚙️ [API](https://docs.namelessmc.com/development/2.0/api) - Write your own integrations or use one of ours (see above)
- 🧩 New powerful module system allowing for further NamelessMC integration.
- ✏️ New template and language systems, allowing for total customisation.
- ✨ Pretty URL option (requires mod_rewrite or special nginx config).
- 🎛 Widgets: allows modules to create widgets which can be displayed on most user-facing pages and display almost anything.
- 🚩 Translated into [over 20 languages](https://github.com/NamelessMC/Nameless/tree/v2/custom/languages)

#### Customising Nameless
- Check out [this wiki article](https://docs.namelessmc.com/development/2.0/modules) for the Module Developer Documenation.
- Developer documentation is coming soon for template and widget development.

<img src="https://user-images.githubusercontent.com/26070412/137838954-c0f26ae0-d5f9-429e-89ed-db22441a2057.png" alt="Support" width="700">

Support can be found in one of the following places:
- [Discord <img src="https://discordapp.com/api/guilds/246705793066467328/widget.png?style=shield">](https://discord.gg/nameless)
- [Official support forum](https://namelessmc.com/forum)
- [SpigotMC](https://www.spigotmc.org/threads/nameless-minecraft-website-software.34810/)

Feature requests and bugs can be posted on the [GitHub Issues](https://github.com/NamelessMC/Nameless/issues) tab.

## Plugin
For Minecraft integration, you can install the Nameless Plugin in your Spigot server. Currently the plugin is only available for spigot, but we're working on bringing it to different server software, such as Sponge and BungeeCord. You can find a list of features and installation instructions in the [plugin readme](https://docs.namelessmc.com/plugin/2.0/about.md).

## Contributing
We welcome all contributions of code and translations. Please feel free to fork the repository on GitHub and create any pull requests.
- We generally keep a todo list in the Milestones tab of the the Issues page.
- We use Composer to manage dependencies. Before you can install the dependencies, you need to install Composer on your local computer. Installation instructions are [here](https://getcomposer.org/doc/00-intro.md).
- To install the Composer packages we depend on, run the following command in the root directory of the NamelessMC repository:
    ```
    composer install --dev
    ```
  - This could take up to about a minute depending on your internet connection.

## Translations
If you would like to assist with the NamelessMC development by providing an updated translation, please feel free to fork the repository here on GitHub and create any pull requests. Or, if you don't want to deal with creating a pull request, please send us a zip file and we'll add it to the repository for you. 
Just make sure you translate the [latest development version](https://github.com/NamelessMC/Nameless/archive/refs/heads/v2.zip), not the latest release or we won't be able to merge your translations! 
Currently other available translations can be found [here](https://translate.namelessmc.com). Please note, not all translations may be up to date. To discuss changes with fellow translators, visit the [NamelessMC Translators](https://discord.gg/7Dku3fE) discord server.
NamelessMC translations are kindly provided by the community. 
Currently available translations can be found [here](https://github.com/NamelessMC/Nameless/tree/v2/custom/languages). Please note, not all translations may be up to date.

## Special Thanks
* All [NamelessMC contributors](https://github.com/NamelessMC/Nameless/graphs/contributors).
* All [NamelessMC Translation contributors](https://github.com/NamelessMC/Nameless/CONTRIBUTORS.md).
* [JetBrains](https://www.jetbrains.com/), whose products are used to develop the NamelessMC project.
