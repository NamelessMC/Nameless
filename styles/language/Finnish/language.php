<?php 
/*
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

/*
 *  Finnish Language
 */
 
/*
 *  Admin Panel
 */
$admin_language = array(
	// General terms
	'admin_cp' => 'Ylläpito',
	'infractions' => 'Rikkeet',
	'invalid_token' => 'Virheellinen token, yritä myöhemmin uudelleen.',
	'invalid_action' => 'Virheellinen toiminto',
	'successfully_updated' => 'Päivitetty onnistuneesti',
	'settings' => 'Asetukset',
	'confirm_action' => 'Vahvista toiminto',
	'edit' => 'Muokkaa',
	'actions' => 'Toiminnot',
	'task_successful' => 'Tehtävä onnistui.',
	
	// Admin login
	're-authenticate' => 'Uudelleentunnistaudu',
	
	// Admin sidebar
	'index' => 'Yleisnäkymä',
	'announcements' => 'Tiedotteet',
	'core' => 'Core',
	'custom_pages' => 'Mukautetut sivut',
	'general' => 'Yleinen',
	'forums' => 'Foorumit',
	'users_and_groups' => 'Käyttäjät ja Ryhmät',
	'minecraft' => 'Minecraft',
	'style' => 'Ulkoasu',
	'addons' => 'Lisäosat',
	'update' => 'Päivitä',
	'misc' => 'Sekalainen',
	'help' => 'Apu',
	
	// Admin index page
	'statistics' => 'Tilastot',
	'registrations_per_day' => 'Rekisteröinnit per päivä (viimeiset 7 päivää)',
	
	// Admin announcements page
	'current_announcements' => 'Nykyiset tiedotteet',
	'create_announcement' => 'Luo tiedote',
	'announcement_content' => 'Tiedotteen sisältö',
	'announcement_location' => 'Tiedotteen sijainti',
	'announcement_can_close' => 'Tiedotteen voi sulkea?',
	'announcement_permissions' => 'Tiedotteen oikeudet',
	'no_announcements' => 'Tiedotteita ei ole luotu.',
	'confirm_cancel_announcement' => 'Oletko varma, että haluat peruuttaa tämän tiedotteen?',
	'announcement_location_help' => 'Ctrl-click valitaksesi useampia sivuja.',
	'select_all' => 'Valitse kaikki',
	'deselect_all' => 'Poista valinnat',
	'announcement_created' => 'Tietote luotu onnistuneesti',
	'please_input_announcement_content' => 'Kirjoita tiedotteen sisältö ja valitse tyyppi',
	'confirm_delete_announcement' => 'Oletko varma, että haluat poistaa tämän tiedotteen=',
	'announcement_actions' => 'Toiminnot',
	'announcement_deleted' => 'Tiedote poistettu onnistuneesti',
	'announcement_type' => 'Tiedotteen tyyppi',
	'can_view_announcement' => 'Tiedotetta voi katsella?',
	
	// Admin core page
	'general_settings' => 'Yleiset asetukset',
	'modules' => 'Moduulit',
	'module_not_exist' => 'Tätä moduulia ei ole olemassa.',
	'module_enabled' => 'Moduuli käytösä',
	'module_disabled' => 'Moduuli pois käytöstä',
	'site_name' => 'Sivun nimi',
	'language' => 'Kieli',
	'voice_server_not_writable' => 'core/voice_server.php ei ole kirjoitettavissa. Katso tiedoston käyttöoikeudet.',
	'email' => 'Sähköpostiosoite',
	'incoming_email' => 'Saapuva sähköpostiosoite',
	'outgoing_email' => 'Lähtevä sähköpostiosoite',
	'outgoing_email_help' => 'Vaadittu vain, jos php_mail() toiminto on käytössä.',
	'use_php_mail' => 'Käytä php_mail() toimintoa?',
	'use_php_mail_help' => 'Suositeltu: käytössä. Jos sivustosi ei lähetä viestejä, poista tämä käytöstäja muokkaa core/email.php tiedosto sähköpostiasetuksillasi.',
	'use_gmail' => 'Käytä Gmailia lähettämiseen?',
	'use_gmail_help' => 'Saatavilla vain, jos php_mail() toiminto on poissa käytöstä. Jos päätät olla käyttämättä Gmailia, käytetään SMTP:tä. Joka tapauksessa se täytyy konfiguroida core/email.php tiedostoon.',
	'enable_mail_verification' => 'Ota käyttöön sähköpostivahvistus?',
	'enable_email_verification_help' => 'Tämä toiminto käytössä vaatii uusien käyttäjien sähköpostivahvistuksen rekisteröinnin yhteydessä.',
	'explain_email_settings' => 'Seuraavat tiedot on vaadittu, jos "Käytä PHP_mail() toimintoa" on <strong>pois käytöstä</strong>. Löydät dokumentoinnin näihin asetuksiin <a href="https://github.com/NamelessMC/Nameless/wiki/Setting-up-Gmail-or-SMTP-with-Nameless" target="_blank">wikissämme (englanniksi)</a>.',
	'email_config_not_writable' => '<strong>core/email.php</strong> tiedostosi ei ole kirjoitettavissa. Katso tiedoston käyttöoikeudet.',
	'pages' => 'Sivut',
	'enable_or_disable_pages' => 'Ota käyttöön tai poista sivuja käytöstä',
	'enable' => 'Ota käyttöön',
	'disable' => 'Ota pois käytöstä',
	'maintenance_mode' => 'Foorumin huoltotila',
	'forum_in_maintenance' => 'Keskustelupalsta on huollossa.',
	'unable_to_update_settings' => 'Asetusten päivittäminen ei onnistu. Varmista, ettei kohtia jäänyt tyhjäksi.',
	'editing_google_analytics_module' => 'Muokataan Google Analytics moduulia',
	'tracking_code' => 'Seurantakoodi',
	'tracking_code_help' => 'Aseta Google Analyticsin seurantakoodi tähän, sisältäen ympäröivät script tagit.',
	'google_analytics_help' => 'Katso <a href="https://support.google.com/analytics/answer/1008080?hl=en#GA" target="_blank">tämä opas</a> lisätietoja varten, vaiheiden 1-3 mukaisesti.',
	'social_media_links' => 'Sosiaalisen Median linkit',
	'youtube_url' => 'YouTube URL',
	'twitter_url' => 'Twitter URL (Älä lopeta "/")',
	'twitter_dark_theme' => 'Käytä tummaa Twitter teemaa?',
	'twitter_widget_id' => 'Twitter Widget ID',
	'google_plus_url' => 'Google Plus URL',
	'facebook_url' => 'Facebook URL',
	'registration' => 'Rekisteröityminen',
	'registration_warning' => 'Tämä moduuli kytkettynä poissa käytöstä estää uusien jäsenien rekisteröitymisen.',
	'google_recaptcha' => 'Ota käyttöön Google reCAPTCHA',
	'recaptcha_site_key' => 'reCAPTCHA Site Key',
	'recaptcha_secret_key' => 'reCAPTCHA Secret Key',
	'registration_terms_and_conditions' => 'Rekisteröinnin käyttöehdot',
	'voice_server_module' => 'Äänipalvelin moduuli',
	'only_works_with_teamspeak' => 'Tämä moduuli toimii tällähetkellä vain TeamSpeakin ja Discordin kanssa.',
	'discord_id' => 'Discord palvelimen ID',
	'voice_server_help' => 'Anna ServerQuery käyttäjän tiedot.',
	'ip_without_port' => 'IP (ilman porttia)',
	'voice_server_port' => 'Portti (yleensä 10011)',
	'virtual_port' => 'Virtuaalinen portti (yleensä 9987)',
	'permissions' => 'Oikeudet:',
	'view_applications' => 'Tarkastele hakemuksia?',
	'accept_reject_applications' => 'Hyväksy / Hylkää hakemuksia?',
	'questions' => 'Kysymykset:',
	'question' => 'Kysymys',
	'type' => 'Tyyppi',
	'options' => 'Valinnat',
	'options_help' => 'Jokainen valinta uudelle riville; voi jättää tyhjäksi (vain valikossa)',
	'no_questions' => 'Kysymyksiä ei ole vielä lisätty',
	'new_question' => 'Uusi kysymys',
	'editing_question' => 'Muokataan kysymystä',
	'delete_question' => 'Poista kysymys',
	'dropdown' => 'Valikko',
	'text' => 'Teksti',
	'textarea' => 'Tekstikenttä',
	'name_required' => 'Nimi on pakollinen.',
	'question_required' => 'Kysymys on pakollinen.',
	'name_minimum' => 'Nimen täytyy olla vähintään kaksi (2) merkkiä pitkä.',
	'question_minimum' => 'Kysymyksen täytyy olla vähintään kolme (3) merkkiä pitkä.',
	'name_maximum' => 'Nimi saa olla enintään 16 merkkiä pitkä.',
	'question_maximum' => 'Kysymys saa olla enintään 16 merkkiä pitkä.',
	'question_deleted' => 'Kysymys poistettu',
	'use_followers' => 'Käytä seuraajia?',
	'use_followers_help' => 'Mikäli pois käytöstä, ystävälistaa käytetään sen sijaan.',
	
	// Admin custom pages page
	'click_on_page_to_edit' => 'Klikkaa sivua muokataksesi sitä.',
	'page' => 'Sivu:',
	'url' => 'URL:',
	'page_url' => 'Sivun URL',
	'page_url_example' => '(Sisällyttäen "/", esimerkiksi /help/)',
	'page_title' => 'Sivun otsikko',
	'page_content' => 'Sisältö',
	'new_page' => 'Uusi sivu',
	'page_successfully_created' => 'Uusi sivu luotu onnistuneesti',
	'page_successfully_edited' => 'Sivu muokattu onnistuneesti',
	'unable_to_create_page' => 'Sivun luominen ei ole mahdollista.',
	'unable_to_edit_page' => 'Sivun muokkaaminen ei ole mahdollista.',
	'create_page_error' => 'Varmista, että olet laittanut 1-20 merkkiä pitkän URL osoitteen, 1-30 merkkiä pitkän sivun otsikon, ja 5-20480 merkkiä pitkän sisällön.',
	'delete_page' => 'Poista sivu',
	'confirm_delete_page' => 'Oletko varma, että haluat poistaa tämän sivun?',
	'page_deleted_successfully' => 'Sivu poistettu onnistuneesti',
	'page_link_location' => 'Näytä linkki:',
	'page_link_navbar' => 'Navigaatiopalkki',
	'page_link_more' => 'Navigaatiopalkin "Lisää" valikko',
	'page_link_footer' => 'Sivun alatunniste',
	'page_link_none' => 'Ei linkkiä',
	'page_permissions' => 'Sivun oikeudet',
	'can_view_page' => 'Voi tarkastella:',
	'redirect_page' => 'Uudelleenohjaa sivu?',
	'redirect_link' => 'Uudelleenohjauslinkki',
	'page_icon' => 'Sivun ikoni',
	
	// Admin forum page
	'labels' => 'Aiheen etuliite',
	'new_label' => 'Uusi etuliite',
	'no_labels_defined' => 'Etuliitteitä ei ole määritelty',
	'label_name' => 'Etuliitteen nimi',
	'label_type' => 'Etuliitteen tyyppi',
	'label_forums' => 'Etuliitteen foorumit',
	'label_creation_error' => 'Virhe leiman luomisessa. Varmista, ettei leima ole yli 32 merkkiä pitkä ja valitsit leiman tyypin.',
	'confirm_label_deletion' => 'Oletko varma, että haluat poistaa tämän etuliitteen?',
	'editing_label' => 'Muokataan etuliitettä',
	'label_creation_success' => 'Etuliite luotu onnistuneesti',
	'label_edit_success' => 'Etuliite muokattu onnistuneesti',
	'label_default' => 'Oletus',
	'label_primary' => 'Ensisijainen',
	'label_success' => 'Onnistunut',
	'label_info' => 'Info',
	'label_warning' => 'Varoitus',
	'label_danger' => 'Vaara',
	'new_forum' => 'Uusi keskustelualue',
	'forum_layout' => 'Foorumin Layout',
	'table_view' => 'Taulukkonäkymä',
	'latest_discussions_view' => 'Viimeisimmät keskustelut -näkymä',
	'create_forum' => 'Luo foorumi',
	'forum_name' => 'Foorumin nimi',
	'forum_description' => 'Foorumin kuvaus',
	'delete_forum' => 'Poista foorumi',
	'move_topics_and_posts_to' => 'Siirrä aiheet ja viestit',
	'delete_topics_and_posts' => 'Poista aiheet ja viestit',
	'parent_forum' => 'Isäntäfoorumi',
	'has_no_parent' => 'Ei isäntäfoorumia',
	'forum_permissions' => 'Foorumin oikeudet',
	'can_view_forum' => 'Voi tarkastella foorumia',
	'can_create_topic' => 'Voi luoda aiheen',
	'can_post_reply' => 'Voi kirjoittaa viestin',
	'display_threads_as_news' => 'Näytetäänkö ketjut uutisina etusivulla?',
	'input_forum_title' => 'Syötä foorumin otsikko.',
	'input_forum_description' => 'Syötä foorumin kuvaus (voi käyttää HTML).',
	'forum_name_minimum' => 'Foorumin nimen täytyy olla vähintään kaksi (2) merkkiä pitkä.',
	'forum_description_minimum' => 'Foorumin kuvauksen täytyy olla vähintään kaksi (2) merkkiä pitkä.',
	'forum_name_maximum' => 'Foorumin nimi saa olla enintään 150 merkkiä pitkä.',
	'forum_description_maximum' => 'Foorumin kuvaus saa olla enintään 255 merkkiä pitkä.',
	'forum_type_forum' => 'Keskustelualue',
	'forum_type_category' => 'Kategoria',
	
	// Admin Users and Groups page
	'users' => 'Käyttäjät',
	'new_user' => 'Uusi käyttäjä',
	'created' => 'Luotu',
	'user_deleted' => 'Käyttäjä poistettu',
	'validate_user' => 'Vahvista käyttäjä',
	'update_uuid' => 'Päivitä UUID',
	'unable_to_update_uuid' => 'Virhe päivittäessä UUID:ta',
	'update_mc_name' => 'Päivitä Minecraft nimi',
	'reset_password' => 'Nollaa salasana',
	'punish_user' => 'Rankaise käyttäjää',
	'delete_user' => 'Poista käyttäjä',
	'minecraft_uuid' => 'Minecraft UUID',
	'ip_address' => 'IP osoite',
	'ip' => 'IP:',
	'other_actions' => 'Muut toiminnot:',
	'disable_avatar' => 'Poista avatar käytöstä',
	'enable_avatar' => 'Ota avatar käyttöön',
	'confirm_user_deletion' => 'Oletko varma, että haluat poistaa käyttäjän {x}?', // Don't replace "{x}"
	'groups' => 'Ryhmät',
	'group' => 'Ryhmä',
	'group2' => 'Toinen ryhmä',
	'new_group' => 'Uusi ryhmä',
	'id' => 'ID',
	'name' => 'Nimi',
	'create_group' => 'Luo ryhmä',
	'group_name' => 'Ryhmän nimi',
	'group_html' => 'Ryhmän HTML',
	'group_html_lg' => 'Ryhmän HTML suuri',
	'donor_group_id' => 'Lahjoituspaketin ID',
	'donor_group_id_help' => '<p>Tämä on ryhmän paketin ID Buycraftista, MinecraftMarketista tai MCStock:sta.</p><p>Tämän voi jättää tyhjäksi.</p>',
	'donor_group_instructions' => 	'<p>Lahjoittajaryhmät täytyy luoda järjestyksessä, <strong>pienimmästä suurimpaan</strong>.</p>
									<p>Esimerkiksi 10€ ryhmä täytyy luoda ennen 20€ ryhmää.</p>',
	'delete_group' => 'Poista ryhmä',
	'confirm_group_deletion' => 'Oletko varma, että haluat poistaa ryhmän {x}?', // Don't replace "{x}"
	'group_staff' => 'Onko ryhmä ylläpidolle?',
	'group_modcp' => 'Voiko ryhmä nähdä ModCP?',
	'group_admincp' => 'Voiko ryhmä nähdä Ylläpitopaneelin?',
	'group_name_required' => 'Sinun täytyy antaa ryhmälle nimi.',
	'group_name_minimum' => 'Ryhmän nimi täytyy olla vähintään kaksi (2) merkkiä.',
	'group_name_maximum' => 'Ryhmän nimi saa olla enintään 20 merkkiä pitkä.',
	'html_maximum' => 'Ryhmän HTML saa olla enintään 1024 merkkiä pitkä.',
	'select_user_group' => 'Käyttäjän täytyy olla ryhmässä.',
	'uuid_max_32' => 'UUID saa olla enintään 32 merkkiä pitkä.',
	'cant_delete_root_user' => 'Root-käyttäjää ei voida poistaa!',
	'cant_modify_root_user' => 'Root-käyttäjän ryhmää ei voi muokata.',
	
	// Admin Minecraft page
	'minecraft_settings' => 'Minecraft asetukset',
	'use_plugin' => 'Ota käyttöön Nameless API?',
	'force_avatars' => 'Pakota Minecraft avatarit?',
	'uuid_linking' => 'Ota käyttöön UUID linkittäminen?',
	'use_plugin_help' => 'APIn ja palvelimen pluginin käyttöönotto sallii arvojen synkronoinnin, in-game rekisteröinnin sekä raportoinnin.',
	'uuid_linking_help' => 'Jos poistettu käytöstä, tilejä ei linkitetä UUID:llä. Suosittelemme vahvasti tämän pitämistä käytössä.',
	'plugin_settings' => 'Plugin asetukset',
	'confirm_api_regen' => 'Oletko varma, että haluat luoda uuden API avaimen?',
	'servers' => 'Palvelimet',
	'new_server' => 'Uusi palvelin',
	'confirm_server_deletion' => 'Oletko varma, että haluat poistaa tämän palvelimen?',
	'main_server' => 'Pääpalvelin',
	'main_server_help' => 'Palvelin, jonne pelaajat yhdistävät. Normaalisti tämä on Bungee instanssi.',
	'choose_a_main_server' => 'Valitse pääserveri...',
	'external_query' => 'Käytä ulkopuolista kyselyä?',
	'external_query_help' => 'Käytä ulkopuolista API kyselyä Minecraft palvelimelle? Käytä vain, jos sisäänrakennettu kysely ei toimi, vahvasti suositeltua pitää poissa käytöstä.',
	'editing_server' => 'Muokataan palvelinta {x}', // Don't replace "{x}"
	'server_ip_with_port' => 'Palvelimen IP (portilla), numeerinen tai domain.',
	'server_ip_with_port_help' => 'Tämä on IP, joka näytetään käyttäjille. Sitä ei kysellä.',
	'server_ip_numeric' => 'Palvelimen IP (portilla) (vain numeerinen)',
	'server_ip_numeric_help' => 'Tämä IP tullaan kysymään, varmista, että se on vain numeerinen. Tätä ei näytetä käyttäjille.',
	'show_on_play_page' => 'Näytä "Pelaa" sivulla?',
	'pre_17' => 'Pre 1.7 Minecraft versio?',
	'server_name' => 'Palvelimen nimi',
	'invalid_server_id' => 'Virheellinen palvelin ID',
	'show_players' => 'Näytä pelaajalista "Pelaa" sivulla?',
	'server_edited' => 'Palvelin muokattu onnistuneesti',
	'server_created' => 'Palvelin luotu onnistuneesti',
	'query_errors' => 'Kyselyvirheet',
	'query_errors_info' => 'Seuraavien virheiden avulla voit määrittää sisäisen palvelimen kyselyn ongelmat.',
	'no_query_errors' => 'Virheitä ei ole kirjattu.',
	'date' => 'Päivämäärä:',
	'port' => 'Portti:',
	'viewing_error' => 'Näytetään virhe',
	'confirm_error_deletion' => 'Oletko varma, että haluat poistaa tämän virheen?',
	'display_server_status' => 'Näytä palvelimen status moduuli?',
	'server_name_required' => 'Sinun täytyy antaa palvelimen nimi.',
	'server_ip_required' => 'Sinun täytyy antaa palvelimen IP.',
	'server_name_minimum' => 'Palvelimen nimen täytyy olla vähintään kaksi (2) merkkiä pitkä.',
	'server_ip_minimum' => 'Palvelimen IP täytyy olla vähintään kaksi (2) merkkiä pitkä.',
	'server_name_maximum' => 'Palvelimen nimi saa olla enintään 20 merkkiä pitkä.',
	'server_ip_maximum' => 'Palvelimen IP saa olla enintään 64 merkkiä pitkä.',
	'purge_errors' => 'Tyhjennä virheet',
	'confirm_purge_errors' => 'Oletko varma, että haluat tyhjentää kaikki virheet?',
	'avatar_type' => 'Avatarin tyyppi',
	'custom_usernames' => 'Pakota Minecraft käyttäjänimet?',
	'mcassoc' => 'mcassoc',
	'use_mcassoc' => 'Käytä mcassoc?',
	'use_mcassoc_help' => 'mcassoc varmistaa, että käyttäjä omistaa Minecraft tilin millä he rekisteröityvät.',
	'mcassoc_key' => 'mcassoc Shared Key',
	'invalid_mcassoc_key' => 'Virheellinen mcassoc avain.',
	'mcassoc_instance' => 'mcassoc instanssi',
	'mcassoc_instance_help' => 'Luo instanssikoodi <a href="http://jsbin.com/jadofehoqu/1/" target="_blank">täällä</a>',
	'mcassoc_key_help' => 'Hanki mcassoc avain <a href="https://mcassoc.lukegb.com/" target="_blank">täältä</a>',
	'enable_name_history' => 'Ota käyttöön profiilin käyttäjänimihistoria?',
	
	// Admin Themes, Templates and Addons
	'themes' => 'Teemat',
	'templates' => 'Pohjat',
	'installed_themes' => 'Asennetut teemat',
	'installed_templates' => 'Asennetut pohjat',
	'installed_addons' => 'Asennetut lisäosat',
	'install_theme' => 'Asenna teema',
	'install_template' => 'Asenna pohja',
	'install_addon' => 'Asenna lisäosa',
	'install_a_theme' => 'Asenna teema',
	'install_a_template' => 'Asenna pohja',
	'install_an_addon' => 'Asenna lisäosa',
	'active' => 'Aktiivinen',
	'activate' => 'Aktivoi',
	'deactivate' => 'Deaktivoi',
	'theme_install_instructions' => 'Lataa teemat <strong>styles/themes</strong> kansioon. Klikkaa sen jälkeen "Skannaa" painiketta alapuolella.',
	'template_install_instructions' => 'Lataa pohjat <strong>styles/templates</strong> kansioon. Klikkaa sen jälkeen "Skannaa" painiketta alapuolella.',
	'addon_install_instructions' => 'Lataa lisäosat <strong>addons</strong> kansioon. Klikkaa sen jälkeen "Skannaa" painiketta alapuolella.',
	'addon_install_warning' => 'Asennat lisäosat omalla vastuullasi. Varmuuskopioi tiedostot ja tietokanta ennen jatkamista.',
	'scan' => 'Skannaa',
	'theme_not_exist' => 'Teemaa ei ole olemassa!',
	'template_not_exist' => 'Pohjaa ei ole olemassa!',
	'addon_not_exist' => 'Lisäosaa ei ole olemassa!',
	'style_scan_complete' => 'Suoritettu, uudet ulkoasut on asennettu.',
	'addon_scan_complete' => 'Suoritettu, uudet lisäosat on asennettu.',
	'theme_enabled' => 'Teema otettu käyttöön.',
	'template_enabled' => 'Pohja otettu käyttöön.',
	'addon_enabled' => 'Lisäosa otettu käyttöö.',
	'theme_deleted' => 'Teema poistettu.',
	'template_deleted' => 'Pohja poistettu.',
	'addon_disabled' => 'Lisäosa poistettu käytöstä.',
	'inverse_navbar' => 'Käänteinen navigointipalkki',
	'confirm_theme_deletion' => 'Oletko varma, että haluat poistaa teeman <strong>{x}</strong>?<br /><br />Teema poistetaan <strong>styles/themes</strong> kansiostasi.', // Don't replace {x}
	'confirm_template_deletion' => 'Oletko varma, että haluat poistaa pohjan <strong>{x}</strong>?<br /><br />Pohja poistetaan <strong>styles/templates</strong> kansiostasi.', // Don't replace {x}
	'unable_to_enable_addon' => 'Virhe lisäosan käyttöönotossa. Varmista, että se on kelvollinen NamelessMC lisäosa.',
	
	// Admin Misc page
	'other_settings' => 'Muut asetukset',
	'enable_error_reporting' => 'Ota käyttöön virheraportointi?',
	'error_reporting_description' => 'Tätä käytetään vain virheenkorjaustarkoituksissa. Vahvasti suositeltavaa jättämään rauhaan.',
	'display_page_load_time' => 'Näytä sivun latausaika?',
	'page_load_time_description' => 'Kun tämä asetus on päällä, sivun alareunaan ilmestyy mittari, joka näyttää sivun latausajan nopeuden.',
	'reset_website' => 'Nollaa sivusto',
	'reset_website_info' => 'Tämä nollaa sivustosi. <strong>Lisäosat otetaan pois käytöstä, mutta niitä ei poisteta eivätkä asetukset muutu.</strong> Myös määritellyt Minecraft palvelimet säilyvät.',
	'confirm_reset_website' => 'Oletko varma, että haluat resetoida sivustosi?',
	
	// Admin Update page
	'installation_up_to_date' => 'Asennuksesi on ajantasalla.',
	'update_check_error' => 'Virhe päivityksiä tarkistettaessa. Tarkista myöhemmin uudelleen.',
	'new_update_available' => 'Uusi päivitys on saatavilla.',
	'your_version' => 'Sinun versiosi:',
	'new_version' => 'Uusi versio:',
	'download' => 'Lataa',
	'update_warning' => 'Varoitus: Varmista, että olet ladannut tarvittavat tiedostot, sekä ladannut ne palvelimelle ennen aloittamista!'
);

/*
 *  Navbar
 */
$navbar_language = array(
	// Text only
	'home' => 'Etusivu',
	'play' => 'Pelaa',
	'forum' => 'Foorumi',
	'more' => 'Lisää',
	'staff_apps' => 'Ylläpitoahakemukset',
	'view_messages' => 'Näytä viestit',
	'view_alerts' => 'Näytä ilmoitukset',
	
	// Icons - will display before the text
	'home_icon' => '',
	'play_icon' => '',
	'forum_icon' => '',
	'staff_apps_icon' => ''
);

/*
 * User Related
 */
$user_language = array(
	// Registration
	'create_an_account' => 'Luo tili',
	'authme_password' => 'AuthMe salasana',
	'username' => 'Käyttäjänimi',
	'minecraft_username' => 'Minecraft käyttäjänimi',
	'email' => 'Sähköpostiosoite',
	'user_title' => 'Nimike',
	'email_address' => 'Sähköpostiosoite',
	'date_of_birth' => 'Syntymäaika',
	'location' => 'Sijainti',
	'password' => 'Salasana',
	'confirm_password' => 'Varmista salasana',
	'i_agree' => 'Hyväksyn',
	'agree_t_and_c' => 'Painamalla <strong class="label label-primary">Rekisteröidy</strong>, hyväksyt <a href="#" data-toggle="modal" data-target="#t_and_c_m">Käyttöehdot ja Säännöt</a>.',
	'register' => 'Rekisteröidy',
	'sign_in' => 'Kirjaudu sisään',
	'sign_out' => 'Kirjaudu ulos',
	'terms_and_conditions' => 'Käyttöehdot ja Säännöt',
	'successful_signin' => 'Sinut on kirjattu sisään onnistuneesti.',
	'incorrect_details' => 'Virheelliset tiedot',
	'remember_me' => 'Muista minut',
	'forgot_password' => 'Unohdin salasanani',
	'must_input_username' => 'Sinun täytyy antaa käyttäjänimi',
	'must_input_password' => 'Sinun täytyy antaa salasana',
	'inactive_account' => 'Tilisi on tällä hetkellä inaktiivinen. Pyysitkö salasanan palautusta?',
	'account_banned' => 'Tilisi on porttikiellossa.',
	'successfully_logged_out' => 'Sinut on kirjattu ulos onnistuneesti.',
	'signature' => 'Allekirjoitus',
	'registration_check_email' => 'Tarkistä sähköpostisi vahvistuslinkin varalta. Et voi kirjautua sisään, ennen kuin olet klikannut vahvistuslinkkiä.',
	'unknown_login_error' => 'Pahoittelut, tuntematon virhe tapahtui yrittäessä kirjata sinua sisään. Yritä myöhemmin uudelleen.',
	'validation_complete' => 'Kiitos rekisteröitymisestä. Voit nyt kirjautua sisään.',
	'validation_error' => 'Virhe pyynnön käsittelyssä. Yritä klikata linkkiä uudelleen.',
	'registration_error' => 'Varmista, että täytit kaikki kentät, käyttäjänimesi on 3-20 merkkiä pitkä ja salasanasi 6-30 merkkiä pitkä.',
	'username_required' => 'Anna käyttäjätunnus',
	'password_required' => 'Anna salasana',
	'email_required' => 'Anna sähköpostiosoite',
	'mcname_required' => 'Anna Minecraft käyttäjänimi.',
	'accept_terms' => 'Sinun täytyy hyväksyä käyttöehdot ja säännöt ennen kuin voit rekisteröityä.',
	'invalid_recaptcha' => 'Virheellinen reCaptcha.',
	'username_minimum_3' => 'Käyttäjänimesi täytyy olla vähintään kolme (3) merkkiä pitkä.',
	'username_maximum_20' => 'Käyttäjänimesi saa olla enintään 20 merkkiä pitkä.',
	'mcname_minimum_3' => 'Minecraft käyttäjänimesi täytyy olla vähintään kolme (3) merkkiä pitkä.',
	'mcname_maximum_20' => 'Minecraft käyttäjänimesi saa olla enintään 20 merkkiä pitkä.',
	'password_minimum_6' => 'Salasanasi täytyy olla vähintään kuusi (6) merkkiä pitkä.',
	'password_maximum_30' => 'Salasanasi saa olla enintään 30 merkkiä pitkä.',
	'passwords_dont_match' => 'Salasanat eivät täsmää.',
	'username_mcname_email_exists' => 'Käyttäjänimesi, Minecraft käyttäjänimesi tai sähköpostiosoitteesi on jo olemassa. Oletko jo luonut käyttäjän?',
	'invalid_mcname' => 'Minecraft käyttäjänimesi ei ole voimassa oleva tili.',
	'mcname_lookup_error' => 'Virhe yhteydessä Mojangin palvelimiin. Yritä myöhemmin uudelleen.',
	'signature_maximum_900' => 'Allekirjoituksesi saa olla enintään 900 merkkiä pitkä.',
	'invalid_date_of_birth' => 'Virheellinen syntymäaika.',
	'location_required' => 'Anna sijaintisi.',
	'location_minimum_2' => 'Sijaintisi täytyy olla vähintään kaksi (2) merkkiä pitkä.',
	'location_maximum_128' => 'Sijaintisi saa olla enintään 128 merkkiä pitkä.',
	'verify_account' => 'Vahvista tili',
	'verify_account_help' => 'Seuraa allaolevia ohjeita, jotta voimme varmistaa, että omistat kyseessä olevan Minecraft tilin.',
	'verification_failed' => 'Vahvistus epäonnistui, yritä uudelleen.',
	'verification_success' => 'Vahvistus onnistui. Voit nyt kirjautua.',
	'complete_signup' => 'Viimeistele rekisteröinti',
	'registration_disabled' => 'Rekisteröinti on poissa käytöstä.',
	
	// UserCP
	'user_cp' => 'Käyttäjä-CP',
	'no_file_chosen' => 'Ei valittua tiedostoa',
	'private_messages' => 'Yksityisviestit',
	'profile_settings' => 'Profiiliasetukset',
	'your_profile' => 'Profiilisi',
	'topics' => 'Aiheet',
	'posts' => 'Viestit',
	'reputation' => 'Maine',
	'friends' => 'Kaverit',
	'alerts' => 'Ilmoitukset',
	
	// Messaging
	'new_message' => 'Uusi viesti',
	'no_messages' => 'Ei viestejä',
	'and_x_more' => 'ja {x} lisää', // Don't replace "{x}"
	'system' => 'Järjestelmä',
	'message_title' => 'Viestin otsikko',
	'message' => 'Viesti',
	'to' => 'Saaja:',
	'separate_users_with_comma' => 'Erota käyttäjät toisistaan pilkulla (",")',
	'viewing_message' => 'Näytetään viesti',
	'delete_message' => 'Poista viesti',
	'confirm_message_deletion' => 'Oletko varma, että haluat poistaa viestin?',
	
	// Profile settings
	'display_name' => 'Näyttönimi',
	'upload_an_avatar' => 'Lataa avatar (vain .jpg, .png tai .gif):',
	'use_gravatar' => 'Käytä Gravataria?',
	'change_password' => 'Vaihda salasana',
	'current_password' => 'Nykyinen salasana',
	'new_password' => 'Uusi salasana',
	'repeat_new_password' => 'Toista uusi salasana',
	'password_changed_successfully' => 'Salasana vaihdettiin onnistuneesti!',
	'incorrect_password' => 'Nykyinen salasanasi on väärin!',
	'update_minecraft_name_help' => 'Tämä päivittää sivuston nimesi Minecraft nimeksi. Voit suorittaa tämän toiminnon kerran kuukaudessa (30 päivää).',
	'unable_to_update_mcname' => 'Virhe päivittäessä Minecraft nimeä.',
	'display_age_on_profile' => 'Näytä ikäsi profiilissa?',
	'two_factor_authentication' => 'Kaksivaiheinen todennus',
	'enable_tfa' => 'Ota käyttöön kaksivaiheinen todennus',
	'tfa_type' => 'Kaksivaiheisen todennuksen tyyppi:',
	'authenticator_app' => 'Todennussovellus',
	'tfa_scan_code' => 'Skannaa seuraava koodi todennussovelluksessasi:',
	'tfa_code' => 'Jos laitteessasi ei ole kameraa tai se ei tue QR-koodia, kirjoita seuraava koodi:',
	'tfa_enter_code' => 'Kirjoita todennussovelluksessa näkyvä koodi:',
	'invalid_tfa' => 'Virheellinen koodi, yritä uudelleen.',
	'tfa_successful' => 'Kaksivaiheinen todennus otettu käyttöön onnistuneesti. Sinun täytyy todentaa itsesi tästä lähtien joka kerta kun kirjaudut.',
	'confirm_tfa_disable' => 'Oletko varma, että haluat poistaa kaksivaiheisen todennuksen käytöstä?',
	'tfa_disabled' => 'Kaksivaiheinen todennus otettu pois käytöstä.',
	'tfa_enter_email_code' => 'Olemme lähettäneet koodin sähköpostiisi. Anna koodi:',
	'tfa_email_contents' => 'Tilillesi on tehty kirjautumisyritys. Jos se olit sinä, anna kaksivaiheisen todennuksen koodi, mikäli sitä pyydetään. Mikäli kyseessä et ollut sinä, voit jättää tämän sähköpostin huomiotta. Salasanan vaihto on suotavaa. Koodi on voimassa 10 minuuttia.',
	
	// Alerts
	'viewing_unread_alerts' => 'Näytetään lukemattomat ilmoitukset. Merkitse <a href="/user/alerts/?view=read"><span class="label label-success">luetuiksi</span></a>.',
	'viewing_read_alerts' => 'Näytetään luetut ilmoitukset. Merkitse <a href="/user/alerts/"><span class="label label-warning">lukemattomiksi</span></a>.',
	'no_unread_alerts' => 'Sinulla ei ole lukemattomia ilmoituksia.',
	'no_alerts' => 'Ei ilmoituksia',
	'no_read_alerts' => 'Ei luettuja ilmoituksia.',
	'view' => 'Näytä',
	'alert' => 'Ilmoitus',
	'when' => 'Koska',
	'delete' => 'Poista',
	'tag' => 'Käyttäjätagi',
	'tagged_in_post' => 'Sinut on mainittu viestissä',
	'report' => 'Raportoi',
	'deleted_alert' => 'Ilmoitus poistettu onnistuneesti',
	
	// Warnings
	'you_have_received_a_warning' => 'Olet saanut varoituksen {x} päivämäärältä {y}.', // Don't replace "{x}" or "{y}"
	'acknowledge' => 'Kuittaa',
	
	// Forgot password
	'password_reset' => 'Salasanan nollaus',
	'email_body' => 'Sait tämän sähköpostin, koska olet pyytänyt salasanan nollausta. Nollaa salasanasi klikkaamalla seuraavaa linkkiä:', // Body for the password reset email
	'email_body_2' => 'Jos et pyytänyt tätä nollausta, voit jättää sähköpostin huomiotta.',
	'password_email_set' => 'Onnistui. Tarkista sähköpostisi seuraavien ohjeiden varalta.',
	'username_not_found' => 'Käyttäjänimeä ei löytynyt.',
	'change_password' => 'Vaihda salasana',
	'your_password_has_been_changed' => 'Salasanasi on vaihdettu.',
	
	// Profile page
	'profile' => 'Profiili',
	'player' => 'Pelaaja',
	'offline' => 'Offline',
	'online' => 'Online',
	'pf_registered' => 'Rekisteröitynyt:',
	'pf_posts' => 'Viestit:',
	'pf_reputation' => 'Maine:',
	'user_hasnt_registered' => 'Käyttäjä ei ole rekisteröitynyt sivustolle vielä.',
	'user_no_friends' => 'Käyttäjä ei ole vielä lisännyt kavereita.',
	'send_message' => 'Lähetä viesti',
	'remove_friend' => 'Poista kaveri',
	'add_friend' => 'Lisää kaveri',
	'last_online' => 'Viimeksi nähty:',
	'find_a_user' => 'Etsi käyttäjää',
	'user_not_following' => 'Käyttäjä ei seuraa ketään.',
	'user_no_followers' => 'Käyttäjällä ei ole seuraajia.',
	'following' => 'Seuraa',
	'followers' => 'Seuraajat',
	'display_location' => 'Sijainti: {x}.', // Don't replace {x}, which will be the user's location
	'display_age_and_location' => '{x}, sijainnista {y}.', // Don't replace {x} which will be the user's age, and {y} which will be their location
	'write_on_user_profile' => 'Kirjoita jotain {x}:n profiiliin...', // Don't replace {x}
	'write_on_own_profile' => 'Kirjoita jotain profiiliisi...',
	'profile_posts' => 'Profiiliviestit',
	'no_profile_posts' => 'Ei profiiliviestejä',
	'invalid_wall_post' => 'Virheellinen seinäviesti. Varmista, että viestisi on 2-2048 merkin väliltä.',
	'about' => 'Tietoja',
	'reply' => 'Vastaa',
	'x_likes' => '{x} tykkäystä', // Don't replace {x}
	'likes' => 'Tykkäykset',
	'no_likes' => 'Ei tykkäyksiä.',
	'post_liked' => 'Tykätyt viestit.',
	'post_unliked' => 'Ei tykätyt viestit.',
	'no_posts' => 'Ei viestejä.',
	'last_5_posts' => 'Viimeiset viisi (5) viestiä',
	'follow' => 'Seuraa',
	'unfollow' => 'Lopeta seuraaminen',
	'name_history' => 'Nimihistoria',
	'changed_name_to' => 'Vaihtoi nimensä: {x} -> {y}', // Don't replace {x} or {y}
	'original_name' => 'Alkuperäinen nimi:',
	'name_history_error' => 'Virhe yrittäessä ladata historiaa.',
	
	// Staff applications
	'staff_application' => 'Ylläpitohakemus',
	'application_submitted' => 'Hakemus lähetetty onnistuneesti.',
	'application_already_submitted' => 'Olet jo lähettänyt hakemuksen. Odota, että edellinen käsitellään ennen uuden lähettämistä.',
	'not_logged_in' => 'Kirjaudu sisään tarkastellaksesi tätä sivua.',
	'application_accepted' => 'Hakemuksesi on hyväksytty.',
	'application_rejected' => 'Hakemuksesi on hylätty.'
);

/*
 *  Moderation related
 */
$mod_language = array(
	'mod_cp' => 'ModCP',
	'overview' => 'Yleisnäkymä',
	'reports' => 'Ilmiannot',
	'punishments' => 'Rangaistukset',
	'staff_applications' => 'Ylläpitohakemukset',
	
	// Punishments
	'ban' => 'Ban',
	'unban' => 'Unban',
	'warn' => 'Varoita',
	'search_for_a_user' => 'Etsi käyttäjää',
	'user' => 'Käyttäjä:',
	'ip_lookup' => 'IP Lookup:',
	'registered' => 'Rekisteröitynyt:',
	'reason' => 'Syy:',
	'cant_ban_root_user' => 'Root-käyttäjää ei voida rangaista!',
	'invalid_reason' => 'Anna pätevä syy 2-256 merkin väliltä.',
	'punished_successfully' => 'Rangaistus lisätty onnistuneesti.',
	
	// Reports
	'report_closed' => 'Ilmianto suljettu.',
	'new_comment' => 'Uusi kommentti',
	'comments' => 'Kommentit',
	'only_viewed_by_staff' => 'Vain ylläpito voi nähdä tämän',
	'reported_by' => 'Ilmiantanut:',
	'close_issue' => 'Sulje ilmianto',
	'report' => 'Ilmianna:',
	'view_reported_content' => 'Näytä ilmiannettu sisältö',
	'no_open_reports' => 'Ei avoinna olevia ilmiantoja',
	'user_reported' => 'Ilmiannettu käyttäjä',
	'type' => 'Tyyppi',
	'updated_by' => 'Päivittänyt',
	'forum_post' => 'Foorumiviesti',
	'user_profile' => 'Käyttäjäprofiili',
	'comment_added' => 'Kommentti lisätty.',
	'new_report_submitted_alert' => 'Uusi ilmianto käyttäjältä {x} koskien käyttäjää {y}', // Don't replace "{x}" or "{y}"
	'ingame_report' => 'In-game ilmianto',
	
	// Staff applications
	'comment_error' => 'Varmista, että kommenttisi on väliltä 2-2048 merkkiä.',
	'viewing_open_applications' => 'Näytetään <span class="label label-info">avoimia</span> hakemuksia. Merkitse <a href="/mod/applications/?view=accepted"><span class="label label-success">hyväksytyksi</span></a> tai <a href="/mod/applications/?view=declined"><span class="label label-danger">hylätyksi</span></a>.',
	'viewing_accepted_applications' => 'Näytetään <span class="label label-success">hyväksytyt</span> hakemukset. Merkitse <a href="/mod/applications/"><span class="label label-info">avoimeksi</span></a> tai <a href="/mod/applications/?view=declined"><span class="label label-danger">hylätyksi</span></a>.',
	'viewing_declined_applications' => 'Näytetään <span class="label label-danger">hylätyt</span> hakemukset. Merkitse <a href="/mod/applications/"><span class="label label-info">avoimeksi</span></a> tai <a href="/mod/applications/?view=accepted"><span class="label label-success">hyväksytyksi</span></a>.',
	'time_applied' => 'Hakenut',
	'no_applications' => 'Tässä kategoriassa ei ole hakemuksia.',
	'viewing_app_from' => 'Näytetään hakemukset käyttäjältä {x}', // Don't replace "{x}"
	'open' => 'Avoimet',
	'accepted' => 'Hyväksytyt',
	'declined' => 'Hylätyt',
	'accept' => 'Hyväksy',
	'decline' => 'Hylkää',
	'new_app_submitted_alert' => 'Uusi hakemus käyttäjältä {x}' // Don't replace "{x}"
);

/* 
 *  General
 */
$general_language = array(
	// Homepage
	'news' => 'Uutiset',
	'social' => 'Sosiaalinen',
	'join' => 'Liity',
	
	// General terms
	'submit' => 'Lähetä',
	'close' => 'Sulje',
	'cookie_message' => '<strong>Tämä sivusto käyttää evästeitä käyttökokemuksen parantamiseksi</strong><p>Jatkamalla sivuston käyttöä, hyväksyt evästeiden käytön.</p>',
	'theme_not_exist' => 'Valittu teema ei ole olemassa.',
	'confirm' => 'Vahvista',
	'cancel' => 'Peruuta',
	'guest' => 'Vieras',
	'guests' => 'Vieraat',
	'back' => 'Takaisin',
	'search' => 'Etsi',
	'help' => 'Apu',
	'success' => 'Onnistui',
	'error' => 'Virhe',
	'view' => 'Näytä',
	'info' => 'Info',
	'next' => 'Seuraava',
	
	// Play page
	'connect_with' => 'Yhdistä palvelimelle IP:llä {x}', // Don't replace {x}
	'online' => 'Online',
	'offline' => 'Offline',
	'status' => 'Status:',
	'players_online' => 'Pelaajia paikalla:',
	'queried_in' => 'Kysely suoritettu:',
	'server_status' => 'Palvelimen Status',
	'no_players_online' => 'Pelaajia ei ole paikalla.',
	'1_player_online' => '1 pelaaja paikalla!!!',
	'x_players_online' => '{x} pelaajaa paikalla.', // Don't replace {x}
	
	// Other
	'page_loaded_in' => 'Sivu ladattu {x} sekunnissa.', // Don't replace {x}; 's' stands for 'seconds'
	'none' => 'Ei yhtään',
	'404' => 'Emme löytäneet sivua.'
);

/* 
 *  Forum
 */
$forum_language = array(
	// Latest discussions view
	'forums' => 'Foorumit',
	'discussion' => 'Keskustelu',
	'stats' => 'Tilastot',
	'last_reply' => 'Viimeisin vastaus',
	'ago' => 'sitten',
	'by' => 'kirjoittanut',
	'in' => 'in',
	'views' => 'nähty',
	'posts' => 'viestit',
	'topics' => 'aiheet',
	'topic' => 'Aihe',
	'statistics' => 'Tilastot',
	'overview' => 'Yleisnäkymä',
	'latest_discussions' => 'Viimeisimmät keskustelut',
	'latest_posts' => 'Viimeisimmät viestit',
	'users_registered' => 'Käyttäjiä rekisteröitynyt:',
	'latest_member' => 'Viimeisin jäsen:',
	'forum' => 'Foorumi',
	'last_post' => 'Viimeisin viesti',
	'no_topics' => 'Ei aiheita',
	'new_topic' => 'Uusi aihe',
	'subforums' => 'Alifoorumit:',
	
	// View topic view
	'home' => 'Etusivu',
	'topic_locked' => 'Ketju lukittu',
	'new_reply' => 'Uusi vastaus',
	'mod_actions' => 'Moderoi',
	'lock_thread' => 'Lukitse ketju',
	'unlock_thread' => 'Avaa ketju',
	'merge_thread' => 'Yhdistä ketju',
	'delete_thread' => 'Poista ketju',
	'confirm_thread_deletion' => 'Oletko varma, että haluat poistaa tämän viestiketjun?',
	'move_thread' => 'Siirrä ketju',
	'sticky_thread' => 'Sticky ketju',
	'report_post' => 'Ilmianna viesti',
	'quote_post' => 'Lainaa viestiä',
	'delete_post' => 'Poista viesti',
	'edit_post' => 'Muokkaa viestiä',
	'reputation' => 'maine',
	'confirm_post_deletion' => 'Oletko varma, että haluat poistaa tämän viestin?',
	'give_reputation' => 'Anna mainetta',
	'remove_reputation' => 'Poista mainetta',
	'post_reputation' => 'Viestin maine',
	'no_reputation' => 'Ei mainetta tällä viestillä',
	're' => 'VS:',
	
	// Create post view
	'create_post' => 'Luo viesti',
	'post_submitted' => 'Viesti lähetetty',
	'creating_post_in' => 'Luodaan viestiä ketjussa: ',
	'topic_locked_permission_post' => 'Tämä ketju on lukittu, sinulla on kuitenkin oikeus lähettää viesti.',
	
	// Edit post view
	'editing_post' => 'Muokataan viestiä',
	
	// Sticky threads
	'thread_is_' => 'Ketju ',
	'now_sticky' => 'on nyt sticky ketju.',
	'no_longer_sticky' => 'ei ole enää sticky ketju.',
	
	// Create topic
	'topic_created' => 'Ketju luotu',
	'creating_topic_in_' => 'Luodaan ketjua foorumissa ',
	'thread_title' => 'Ketjun otsikko',
	'confirm_cancellation' => 'Oletko varma?',
	'label' => 'Etuliite',
	
	// Reports
	'report_submitted' => 'Ilmianto lähetetty.',
	'view_post_content' => 'Näytä viestin sisältö',
	'report_reason' => 'Ilmiannon syy',
	
	// Move thread
	'move_to' => 'Siirrä:',
	
	// Merge threads
	'merge_instructions' => 'Yhdistettävän ketjun <strong>täytyy olla</strong> samassa foorumissa. Siirrä ketju, jos tarpeellista.',
	'merge_with' => 'Yhdistä:',
	
	// Other
	'forum_error' => 'Pahoittelut, emme löytäneet foorumia tai ketjua.',
	'are_you_logged_in' => 'Oletko kirjautunut sisään?',
	'online_users' => 'Paikalla olevat käyttäjät:',
	'no_users_online' => 'Käyttäjiä ei ole paikalla.',
	
	// Search
	'search_error' => 'Syötä hakusana 1-32 merkin väliltä.',
	'no_search_results' => 'Hakua täyttäviä kohteita ei löytynyt.',
	
	//Share on a social-media.
	'sm-share' => 'Jaa',
	'sm-share-facebook' => 'Jaa Facebookissa',
	'sm-share-twitter' => 'Jaa Twitterissä',
);

/*
 *  Emails
 */
$email_language = array(
	// Registration email
	'greeting' => 'Hei,',
	'message' => 'kiitos rekisteröitymisestäsi! Viimeistelläksesi rekisteröinnin, klikkaa seuraavaa linkkiä:',
	'thanks' => 'Kiitos,'
);

/*
 *  Time language, eg "1 minute ago"
 *  DON'T replace "{x}" in any translations
 */
$time_language = array(
	'seconds_short' => 's', // Shortened "seconds", eg "s"
	'less_than_a_minute' => 'alle minuutti sitten',
	'1_minute' => 'minuutti sitten',
	'_minutes' => '{x} minuuttia sitten',
	'about_1_hour' => 'noin tunti sitten',
	'_hours' => '{x} tuntia sitten',
	'1_day' => '1 päivä sitten',
	'_days' => '{x} päivää sitten',
	'about_1_month' => 'noin kuukausi sitten',
	'_months' => '{x} kuukautta sitten',
	'about_1_year' => 'noin vuosi sitten',
	'over_x_years' => 'yli {x} vuotta sitten'
);
 
/*
 *  Table language; used for "DataTables" Javascript tables
 */
$table_language = array(
	'display_records_per_page' => 'Näytä _MENU_ tulosta per sivu', // Don't replace "_MENU_"
	'nothing_found' => 'Ei tuloksia.',
	'page_x_of_y' => 'Näytetään sivu _PAGE_ _PAGES_ sivusta.', // Don't replace "_PAGE_" or "_PAGES_"
	'no_records' => 'Ei tuloksia saatavilla.',
	'filtered' => '(suodatettu _MAX_ tuloksesta yhteensä)' // Don't replace "_MAX_"
);
 
/*
 *  API language
 */
$api_language = array(
	'register' => 'Viimeistele rekisteröinti'
);
 
?>
