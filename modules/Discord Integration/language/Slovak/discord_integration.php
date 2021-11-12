<?php
/*
 *  Made by RobiNN
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr12
 *
 *  License: MIT
 *
 *  Slovak Language for Discord Integration module
 */

$language = [

    // Misc
    'discord' => 'Discord',
    'enable_discord_integration' => 'Povoliť Discord integráciu?',
    'discord_role_id' => 'ID Discord role',
    'discord_role_id_numeric' => 'Discord Role ID musí byť číselné.',
    'discord_role_id_length' => 'Discord Role ID musí mať dĺžku 18 číslic.',
    'discord_guild_id' => 'ID Discord serveru',
    'discord_widget_theme' => 'Téma Discord widgetu',
    'discord_widget_disabled' => 'Widget je pre zadaný Discord server zakázaný. Prejdite na kartu \'Widgety\' v nastaveniach Discord servera a uistite sa, že je povolený Discord widget a či je ID správne.',
    'discord_id_length' => 'Uistite sa, že vaše Discord ID má 18 znakov.',
    'discord_id_numeric' => 'Uistite sa, že vaše Discord ID je číselné (iba čísla).',
    'discord_invite_info' => 'Ak chcete pozvať Nameless Link bota na svoj Discord server, kliknite <a target="_blank" href="https://namelessmc.com/discord-bot-invite">sem</a>. Potom spustite príkaz <code>/apiurl</code> na prepojenie bota s vašou webstránkou. Prípadne môžete <a target="_blank" href="https://github.com/NamelessMC/Nameless-Link/wiki/Own-instance">hostiť robota sami</a>.',
    'discord_bot_must_be_setup' => 'Discord integráciu nie je možné povoliť, kým nenastavíte bota. Pre informáciu prosím <a href="https://github.com/NamelessMC/Nameless-Link/wiki/Setup" target="_blank">kliknite tu</a>.',
    'discord_bot_setup' => 'Bot nastavený?',
    'discord_integration_not_setup' => 'Integrácia Discordu nie je nastavená',
    'discord_username' => 'Discord používateľské meno',

    // Discord bot Errors
    'discord_bot_error_badparameter' => 'Neplatný text žiadosti.',
    'discord_bot_error_error' => 'Vyskytla sa interná chyba bota.',
    'discord_bot_error_invguild' => 'Poskytnuté Guild ID je neplatné alebo v ňom bot nie je.',
    'discord_bot_error_invuser' => 'Poskytnuté ID užívateľa je neplatné alebo nie je v určenom Guildu.',
    'discord_bot_error_notlinked' => 'Bot nie je prepojený s týmto webovým serverom pre poskytnuté Guild ID.',
    'discord_bot_error_unauthorized' => 'API kľúč je neplatný',
    'discord_bot_error_invrole' => 'Poskytnuté ID role je neplatné.',
    'discord_bot_check_logs' => 'Mali by ste skontrolovať konkrétnejšiu chybu (ak existuje) v StaffCP -> Zabezpečenie -> Všetky záznamy.',
    'discord_bot_error_partsuccess' => 'Bot nemohol upraviť jednu alebo viacero rolí v dôsledku nesprávnej konfigurácie Discord hierarchie.',

    // API Errors
    'discord_integration_disabled' => 'Discord integrácia je zakázaná.',
    'unable_to_set_discord_id' => 'Nepodarilo sa nastaviť Discord ID.',
    'unable_to_set_discord_bot_url' => 'Nepodarilo sa nastaviť URL Discord bota',
    'provide_one_discord_settings' => 'Zadajte aspoň jednu z nasledujúcich možností: \'url\', \'guild_id\'',
    'no_pending_verification_for_token' => 'Pod dodaným tokenom nie sú čakajúce žiadne overenia.',
    'unable_to_update_discord_username' => 'Nepodarilo sa aktualizovať Discord užívateľské meno.',
    'unable_to_update_discord_roles' => 'Nepodarilo sa aktualizovať zoznam Discord rolí.',
    'unable_to_update_discord_bot_username' => 'Nepodarilo sa aktualizovať Discord užívateľské meno bota.',

    // API Success
    'discord_id_set' => 'Discord ID bolo úspešne nastavené',
    'discord_settings_updated' => 'Nastavenia Discordu sa úspešne aktualizovali',
    'discord_usernames_updated' => 'Discord užívateľské mená boli úspešne aktualizované',

    // User Settings
    'discord_link' => 'Discord prepojenie',
    'linked' => 'Prepojené',
    'not_linked' => 'Neprepojené',
    'discord_user_id' => 'ID Discord používateľa',
    'discord_id_unlinked' => 'Vaše Discord ID bolo úspešne odpojené.',
    'discord_id_confirm' => 'Spustite príkaz "/verify {token}" v Discorde, aby ste dokončili prepojenie svojho Discord účtu.',
    'pending_link' => 'Čaká sa',
    'discord_id_taken' => 'Toto Discord ID už bolo použité.',
    'discord_invalid_id' => 'Toto Discord užívateľské ID už bolo použité.',
    'discord_already_pending' => 'Už máte čakajúce overenie.',
    'discord_database_error' => 'Databáza Nameless Link je momentálne nefunkčná. Skúste neskôr prosím.',
    'discord_communication_error' => 'Pri komunikácii s Discord botom sa vyskytla chyba. Skontrolujte, či je bot spustený a či je URL vášho adresa bota správna.',
    'discord_unknown_error' => 'Pri synchronizácii Discord rolí sa vyskytla neznáma chyba. Prosím kontaktujte správcu.',
    'discord_id_help' => 'Informácie o tom, kde nájdete Discord ID, si prečítajte <a href="https://support.discord.com/hc/en-us/articles/206346498-Where-can-I-find-my-User-Server-Message-ID-" target="_blank">toto.</a>',
];
