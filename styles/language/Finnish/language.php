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
	'admin_cp' => 'AdminCP', 
	'invalid_token' => 'Erääntynyt istunto, yritä uudelleen.',
	'invalid_action' => 'Virheellinen toiminto',
	'successfully_updated' => 'Päivitetty onnistuneesti',
	'settings' => 'Asetukset',
	'confirm_action' => 'Varmista toiminto',
	'edit' => 'Muokkaa',
	'actions' => 'Toiminnot',
	'task_successful' => 'Tehtävä suoritettu onnistuneesti',
	
	// Admin login
	're-authenticate' => 'Uudelleentodenna itsesi',
	
	// Admin sidebar
	'index' => 'Yleisnäkymä',
	'core' => 'Ydin',
	'custom_pages' => 'Omat sivut',
	'general' => 'Yleinen',
	'forums' => 'Foorumit',
	'users_and_groups' => 'Käyttäjät ja käyttäjäryhmät',
	'minecraft' => 'Minecraft',
	'style' => 'Tyyli',
	'addons' => 'Lisäosat',
	'update' => 'Päivitys',
	'misc' => 'Sekalainen',
	
	// Admin index page
	'statistics' => 'Statistiikat',
	'registrations_per_day' => 'Rekisteröinnit päivässä (viimeiset 7 päivää)',
	
	// Admin core page
	'general_settings' => 'Pääasetukset',
	'modules' => 'Moduulit',
	'module_not_exist' => 'Moduulia ei ole olemassa!',
	'module_enabled' => 'Moduuli käytössä.',
	'module_disabled' => 'Moduuli pois käytöstä.',
	'site_name' => 'Sivuston nimi',
	'language' => 'Kieli',
	'voice_server_not_writable' => 'core/voice_server.php ei ole muokattava. Tarkista tiedosto-oikeudet.',
	'email' => 'Sähköposti',
	'incoming_email' => 'Saapuva sähköpostiosoite',
	'outgoing_email' => 'Lähtevä sähköpostiosoite',
	'outgoing_email_help' => 'Vaadittu vain jos php mail on käytössä',
	'use_php_mail' => 'Käytä PHP_mail() funktiota?',
	'use_php_mail_help' => 'Suositeltu: käytössä. Jos sivustosi ei lähetä sähköpostia, muokkaa ja poista käytöstä core/email.php sähköpostiasetuksista.',
	'use_gmail' => 'Käytä Gmailia sähköpostin lähettämiseen?',
	'use_gmail_help' => 'Saatavilla vain, jos PHP_mail() funktio ei ole käytössä. Jos et valitse käytettäväksi Gmailia, käytetään SMTP. Joka tapauksessa tämä tarvitsee konfiguroinnin core/email.php.',
	'enable_mail_verification' => 'Ota sähköpostivarmistus käyttöön?',
	'enable_email_verification_help' => 'Tämä lähettää sähköpostin käyttäjän valitsemaan sähköpostiin, joka tulee vahvistaa suorittaakseen rekisteröinnin kokonaan.',
	'explain_email_settings' => 'The following is required if the "Use PHP mail() function" option is <strong>disabled</strong>. You can find documentation on these settings <a href="https://github.com/NamelessMC/Nameless/wiki/Setting-up-Gmail-or-SMTP-with-Nameless" target="_blank">on our wiki</a>.',
	'email_config_not_writable' => 'Your <strong>core/email.php</strong> file is not writable. Please check file permissions.',
	'pages' => 'Sivut',
	'enable_or_disable_pages' => 'Ota käyttöön/poista käytöstä sivut täällä.',
	'enable' => 'Ota käyttöön',
	'disable' => 'Ota pois käytöstä',
	'maintenance_mode' => 'Foorumin huoltokatko -tila',
	'forum_in_maintenance' => 'Foorumi on huoltokatkolla.',
	'unable_to_update_settings' => 'Asetuksia ei voitu päivittää. Varmista, ettei jäänyt tyhjiä kohtia.',
	'editing_google_analytics_module' => 'Muokataan Google Analytics -moduulia.',
	'tracking_code' => 'Seurantakoodi',
	'tracking_code_help' => 'Aseta Google Analytics seurantakoodi tähän ympäröivien script tagien kanssa.',
	'google_analytics_help' => 'Katso <a href="https://support.google.com/analytics/answer/1008080?hl=en#GA" target="_blank">tämä ohje</a> lisätietoihim, seuraten askeleita 1-3.',
	'social_media_links' => 'Some linkit',
	'youtube_url' => 'YouTube URL',
	'twitter_url' => 'Twitter URL',
	'twitter_widget_id' => 'Twitter Widget ID',
	'google_plus_url' => 'Google Plus URL',
	'facebook_url' => 'Facebook URL',
	'registration' => 'Rekisteröinti',
	'registration_warning' => 'Tämä moduuli otettuna pois käytöstä estää uusien käyttäjien rekisteröitymisen.',
	'google_recaptcha' => 'Ota Google reCAPTCHA käyttöön',
	'recaptcha_site_key' => 'reCAPTCHA Sivustoavain',
	'recaptcha_secret_key' => 'reCAPTCHA Salainen avain',
	'registration_terms_and_conditions' => 'Rekisteröitymisen käyttöehdot ja säännöt',
	'voice_server_module' => 'Äänipalvelin moduuli',
	'only_works_with_teamspeak' => 'Tämä moduuli toimii tällähetkellä vain TeamSpeakin kanssa.',
	'discord_id' => 'Discord Server ID',
	'voice_server_help' => 'Kirjoita yksityiskohdat käyttäjän ServerQuerya varten.',
	'ip_without_port' => 'IP (ilman porttia)',
	'voice_server_port' => 'Portti (yleensä 10011)',
	'virtual_port' => 'Virtuaaliportti (yleensä 9987)',
	'permissions' => 'Oikeuset:',
	'view_applications' => 'Näytä hakemukset?',
	'accept_reject_applications' => 'Hyväksy/Hylkää hakemuksia?',
	'questions' => 'Kysymykset',
	'question' => 'Kysymys',
	'type' => 'Tyyppi',
	'options' => 'Valinnat',
	'options_help' => 'Jokainen valinta omalla rivillään; voi jättää tyhjäkai (vain pudotusvalikoille)',
	'no_questions' => 'Kysymyksiä ei ole lisätty vielä.',
	'new_question' => 'Uusi kysymys',
	'editing_question' => 'Muokkaa kysymystä',
	'delete_question' => 'Poista kysymys',
	'dropdown' => 'Pudotusvalikko',
	'text' => 'Teksti',
	'textarea' => 'Tekstialue',
	'question_deleted' => 'Kysymys poistettu.',
	'use_followers' => 'Use followers?',
	'use_followers_help' => 'If disabled, the friends system will be used.',
	
	// Admin custom pages page
	'click_on_page_to_edit' => 'Klikkaa sivua muokataksesi sitä.',
	'page' => 'Sivu',
	'url' => 'Osoite:',
	'page_url' => 'Sivun osoite',
	'page_url_example' => '("/" edessä, esimerkiksi /help/)',
	'page_title' => 'Sivun otsikko',
	'page_content' => 'Sisältö',
	'new_page' => 'Uusi sivu',
	'page_successfully_created' => 'Sivu luotu onnistuneesti.',
	'page_successfully_edited' => 'Sivu muokattu onnistuneesti.',
	'unable_to_create_page' => 'Sivua ei ole mahdollista luoda.',
	'unable_to_edit_page' => 'Sivua ei ole mahdollista muokata.',
	'create_page_error' => 'Varmista, että osoite on 1-20 merkkiä pitkä, sivun otsikko 1-30 ja sisältö 5-20480 merkkiä pitkä.',
	'delete_page' => 'Poista sivu',
	'confirm_delete_page' => 'Oletko varma, että haluat poistaa tämän sivun?',
	'page_deleted_successfully' => 'Sivu poistettu onnistuneesti',
	'page_link_location' => 'Näytä sivun linkki:',
	'page_link_navbar' => 'Navigaatiopalkki',
	'page_link_more' => 'Navigaatiopalkki "Lisää" pudotusvalikko',
	'page_link_footer' => 'Sivun alatunniste',
	'page_link_none' => 'Sivulla ei ole linkkiä',
	'page_permissions' => 'Page Permissions',
	'can_view_page' => 'Can view page:',
	'redirect_page' => 'Redirect page?',
	'redirect_link' => 'Redirect link',
	
	// Admin forum page
	'labels' => 'Aiheleima',
	'new_label' => 'Uusi leima',
	'no_labels_defined' => 'Leimoja ei ole määritetty.',
	'label_name' => 'Leiman nimi',
	'label_type' => 'Leiman tyyppi',
	'label_forums' => 'Leiman foorumit',
	'label_creation_error' => 'Leimaa ei voitu luoda. Varmista ettei leima ole yli 32 merkkiä pitkä ja että sinulla on määritetty tyyppi.',
	'confirm_label_deletion' => 'Oletko varma, rttä haluat poistaa tämän leiman?',
	'editing_label' => 'Muokkaa leimaa',
	'label_creation_success' => 'Leima luotu onnistuneesti',
	'label_edit_success' => 'Leima muokattu onnistuneesti',
	'label_default' => 'Oletus',
	'label_primary' => 'Ensisijainen',
	'label_success' => 'Onnistunut',
	'label_info' => 'Ilmoitus',
	'label_warning' => 'Varoitus',
	'label_danger' => 'Vaara',
	'new_forum' => 'Uusi foorumi',
	'forum_layout' => 'Foorumin pohjapiirros',
	'table_view' => 'Taulukkonäkymä',
	'latest_discussions_view' => 'Viimeisimmät keskustelut -näkymä',
	'create_forum' => 'Luo foorumi',
	'forum_name' => 'Foorumin nimi',
	'forum_description' => 'Foorumin kuvaus',
	'delete_forum' => 'Poista foorumi',
	'move_topics_and_posts_to' => 'Siirrä viestiketjut ja viestit kohteeseen',
	'delete_topics_and_posts' => 'Poista viestiketjut ja viestit',
	'parent_forum' => 'Äitifoorumi',
	'has_no_parent' => 'Foorumilla ei ole äitifoorumia',
	'forum_permissions' => 'Foorumin oikeudet',
	'can_view_forum' => 'Voi katsoa foorumia:',
	'can_create_topic' => 'Voi luoda viestiketjun:',
	'can_post_reply' => 'Voi lähettää vastauksia:',
	'display_threads_as_news' => 'Näytetäänkö ketjut etusivun Uutisosiossa?',
	'input_forum_title' => 'Kirjoita foorumin otsikko.',
	'input_forum_description' => 'Kirjoita foorumin kuvaus.',
	'forum_name_minimum' => 'Foorumin nimi pitää olla vähintään 2 merkkiä pitkä.',
	'forum_description_minimum' => 'Foorumin kuvaus pitää olla vähintään 2 merkkiä pitkä.',
	'forum_name_maximum' => 'Foorumin nimi voi olla korkeintaan 150 merkkiä.',
	'forum_description_maximum' => 'Foorumin kuvaus voi olla korkeintaan 255 merkkiä.',
	'forum_type_forum' => 'Discussion Forum',
	'forum_type_category' => 'Category',
	
	// Admin Users and Groups page
	'users' => 'Käyttäjät',
	'new_user' => 'Uusi käyttäjä',
	'created' => 'Luotu',
	'user_deleted' => 'Käyttäjä poistettu',
	'validate_user' => 'Vahvista käyttäjä',
	'update_uuid' => 'Päivitä UUID',
	'unable_to_update_uuid' => 'UUID:tä ei voitu päivittää.',
	'update_mc_name' => 'Päivitä Minecraft nimi',
	'reset_password' => 'Tyhjennä salasana',
	'punish_user' => 'Rankaise käyttäjää',
	'delete_user' => 'Poista käyttäjä',
	'minecraft_uuid' => 'Minecraft UUID',
	'ip_address' => 'IP-osoite',
	'ip' => 'IP:',
	'other_actions' => 'Muut toiminnot:',
	'disable_avatar' => 'Ota avatar pois käytöstä',
	'confirm_user_deletion' => 'Oletko varma, että haluat poistaa käyttäjän {x}?', // Don't replace "{x}"
	'groups' => 'Käyttäjäryhmät',
	'group' => 'Ryhmä',
	'new_group' => 'Uusi ryhmä',
	'id' => 'ID',
	'name' => 'Nimi',
	'create_group' => 'Luo ryhmä',
	'group_name' => 'Ryhmän nimi',
	'group_html' => 'Ryhmän HTML',
	'group_html_lg' => 'Ryhmän HTML (suuri)',
	'donor_group_id' => 'Lahjoituspaketin ID',
	'donor_group_id_help' => '<p>Tämä on ryhmän paketin Buycraft, MinecraftMarket tai MCStock ID.</p><p>Tämän voi jättää tyhjäksi.</p>',
	'donor_group_instructions' => 	'<p>Lahjoitusten täytyy olla järjestyksessä <strong>pienimmästä arvosta suurimpaan arvoon</strong>.</p>
									<p>Esimerkiksi 10€ paketti luodaan ennen 20€ pakettia.</p>',
	'delete_group' => 'Poista ryhmä',
	'confirm_group_deletion' => 'Oletko varma, että haluat poistaa {x} ryhmän?', // Don't replace "{x}"
	'group_staff' => 'Kuuluuko tämä ylläpitoon?',
	'group_modcp' => 'Näytetäänkö ModCP?',
	'group_admincp' => 'Näytetäänkö AdminCP?',
	'group_name_required' => 'Sinun täytyy antaa ryhmälle nimi.',
	'group_name_minimum' => 'Ryhmän nimi täytyy olla vähintään 2 merkkiä pitkä.',
	'group_name_maximum' => 'Ryhmän nimi voi olla enintään 20 merkkiä pitkä.',
	'html_maximum' => 'Ryhmän HTML voi olla enintään 1024 merkkiä pitkä.',
	'select_user_group' => 'Käyttäjän pitää olla ryhmässä.',
	'uuid_max_32' => 'UUID:n täytyy olla enintään 32 merkkiä pitkä.',
	'cant_delete_root_user' => 'Can\'t delete the root user!',
	'cant_modify_root_user' => 'Can\'t modify the root user\'s group.',
	
	// Admin Minecraft page
	'minecraft_settings' => 'Minecraft asetukset',
	'use_plugin' => 'Käytä Nameless Minecraft pluginia?',
	'force_avatars' => 'Pakota Minecraft avatarit?',
	'uuid_linking' => 'Ota UUID yhdistäminen käyttöön?',
	'use_plugin_help' => 'Pluginin käyttö mahdollistaa rankin synkronoinnin, serverillä rekisteröitymisen ja tiketin jättämisen',
	'uuid_linking_help' => 'Jos otettu pois käytöstä, käyttäjien UUID:tä ei yhdistetä foorumitiliin. Suositellaan syvästi tämän käyttöä.',
	'plugin_settings' => 'Plugin asetukset',
	'confirm_api_regen' => 'Oletko varma, että haluat luoda uuden APIn?',
	'servers' => 'Palvelimet',
	'new_server' => 'Uusi palvelin',
	'confirm_server_deletion' => 'Oletko varma, että haluat poistaa tämän palvelimen?',
	'main_server' => 'Pääpalvelin',
	'main_server_help' => 'Palvelin, jonka lävitse pelaajat yhdistävät. Yleensä BungeeCord.',
	'choose_a_main_server' => 'Valitse pääpalvelin...',
	'external_query' => 'Käytä ulkoista kyselyä?',
	'external_query_help' => 'Use an external API to query the Minecraft server? Only use this if the built in query doesn\'t work; it\'s highly recommended that this is unticked.',
	'editing_server' => 'Muokkaa palvelinta {x}', // Don't replace "{x}"
	'server_ip_with_port' => 'Palvelimen IP (portilla) (numeerinen tai domain)',
	'server_ip_with_port_help' => 'Tämä on IP, jonka käyttäjät näkevät. Sitä ei kysellä.',
	'server_ip_numeric' => 'Palvelimen IP (portilla) (VAIN numeerinen)',
	'server_ip_numeric_help' => 'Tälle suoritetaan kysely, joten varmista, että se on numeerinen. Sitä ei näytetä käyttäjille.',
	'show_on_play_page' => 'Näytä Pelaamassa -sivulla?',
	'pre_17' => 'MC 1.7 edeltävä?',
	'server_name' => 'Palvelimen nimi',
	'invalid_server_id' => 'Virheellinen palvelin ID',
	'show_players' => 'Näytä pelaajalista Pelaamassa -sivulla?',
	'server_edited' => 'Palvelin muokattu onnistuneesti',
	'server_created' => 'Palvelin luotu onnistuneesti',
	'query_errors' => 'Kyselyvirheet',
	'query_errors_info' => 'Seuraavat virheet antavat sinun tutkia ongelmia palvelimen kyselyn kanssa.',
	'no_query_errors' => 'Kyselyvirheitä ei ole kirjattu.',
	'date' => 'Päivämäärä:',
	'port' => 'Portti:',
	'viewing_error' => 'Näytetään virhe',
	'confirm_error_deletion' => 'Oletko varma, että haluat poistaa tämän virheen?',
	'display_server_status' => 'Näytä palvelimen statusmoduuli?',
	'server_name_required' => 'Sinun täytyy laittaa palvelimelle nimi.',
	'server_ip_required' => 'Sinun täytyy laittaa palvelimen IP.',
	'server_name_minimum' => 'Palvelimen nimi täytyy olla väh. 2 merkkiä.',
	'server_ip_minimum' => 'Palvelimen IP täytyy olla väh. 2 merkkiä.',
	'server_name_maximum' => 'Palvelimen nimi voi olla enintään 20 merkkiä.',
	'server_ip_maximum' => 'Palvelimen nimi voi olla enintään 64 merkkiä.',
	'purge_errors' => 'Purge Errors',
	'confirm_purge_errors' => 'Are you sure you want to purge all query errors?',
	
	// Admin Themes, Templates and Addons
	'themes' => 'Teemat',
	'templates' => 'Mallit',
	'installed_themes' => 'Asennetut teemat',
	'installed_templates' => 'Asennetut mallit',
	'installed_addons' => 'Asennetut lisäosat',
	'install_theme' => 'Asenna teema',
	'install_template' => 'Asenna malli',
	'install_addon' => 'Asenna lisäosa',
	'install_a_theme' => 'Asenna teema',
	'install_a_template' => 'Asenna malli',
	'install_an_addon' => 'Asenna lisäosa',
	'active' => 'Aktiivinen',
	'activate' => 'Aktivoi',
	'deactivate' => 'Deaktivoi',
	'theme_install_instructions' => 'Lataa teeman tiedostot <strong>styles/themes</strong> kansioon. Klikkaa sen jälkeen "Skannaa".',
	'template_install_instructions' => 'Lataa mallit <strong>styles/templates</strong> kansioon. Klikkaa sen jälkeen "Skannaa".',
	'addon_install_instructions' => 'Lataa lisäosan tiedostot <strong>addons</strong> kansioon. Klikkaa sen jälkeen "Skannaa".',
	'addon_install_warning' => 'Lisäosat asennetaan omalla vastuullasi. Varmuuskopioi tiedostosi ennen kuin jatkat.',
	'scan' => 'Skannaa',
	'theme_not_exist' => 'Tätä teemaa ei ole olemassa!',
	'template_not_exist' => 'Tätä mallia ei ole olemassa!',
	'addon_not_exist' => 'Tätä lisäosaa ei ole olemassa!',
	'style_scan_complete' => 'Suoritettu. Kaikki uudet tyylit on asennettu.',
	'addon_scan_complete' => 'Suoritettu. Kaikki uudet lisäosat on asennettu.',
	'theme_enabled' => 'Teema käytössä.',
	'template_enabled' => 'Malli käytössä.',
	'addon_enabled' => 'Lisäosa käytössä.',
	'theme_deleted' => 'Teema poistettu.',
	'template_deleted' => 'Malli poistettu.',
	'addon_disabled' => 'Lisäosa otettu pois käytöstä.',
	'inverse_navbar' => 'Käänteinen navigaatiopalkki',
	'confirm_theme_deletion' => 'Oletko varma, että haluat poistaa teeman <strong>{x}</strong>?<br /><br />Teema poistetaan myös <strong>styles/themes</strong> kansiosta.', // Don't replace {x}
	'confirm_template_deletion' => 'Oletko varma, että haluat poistaa mallin <strong>{x}</strong>?<br /><br />Malli poistetaan myös <strong>styles/templates</strong> kansiosta.', // Don't replace {x}
	
	// Admin Misc page
	'other_settings' => 'Muut asetukset',
	'enable_error_reporting' => 'Ota virheilmoitukset käyttöön?',
	'error_reporting_description' => 'Tätä käytetään vain virheenkorjaukseen, suositellaan jätettäväksi pois käytöstä.',
	'display_page_load_time' => 'Näytä sivun latausaika?',
	'page_load_time_description' => 'Tämä näyttää nopeusmittarin sivun alatunnisteessa, josta näkee latausajan.',
	'reset_website' => 'Tyhjennä sivusto',
	'reset_website_info' => 'Tämä tyhjentää sivustosi. <strong>Lisäosat poistetaan käytöstä, eikä niiden asetuksia muuteta.</strong> Myös palvelimet säilyvät.',
	'confirm_reset_website' => 'Oletko varma, että hakuat tyhjentää sivuston?',
	
	// Admin Update page
	'installation_up_to_date' => 'Asennus on viimeisimmässä versiossaan.',
	'update_check_error' => 'Päivityksiä ei voi tarkistaa. Yritä myöhemmin uudelleen.',
	'new_update_available' => 'Päivitys saatavilla',
	'your_version' => 'Nykyinen versio:',
	'new_version' => 'Uusi versio:',
	'download' => 'Lataa',
	'update_warning' => 'Varoitus: Varmista, että olet ladannut päivityksen ja ladannut sisällön palvelimelle!'
);

/*
 *  Navbar
 */
$navbar_language = array(
	// Text only
	'home' => 'Etusivu',
	'play' => 'Pelaamassa',
	'forum' => 'Foorumi',
	'more' => 'Lisää',
	'staff_apps' => 'Ylläpitohakemukset',
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
	'create_an_account' => 'Luo käyttäjätili',
	'authme_password' => 'AuthMe salasana',
	'username' => 'Käyttäjänimi',
	'minecraft_username' => 'Minecraft -nimi',
	'email' => 'Sähköposti',
	'email_address' => 'Sähköpostiosoite',
	'date_of_birth' => 'Date of Birth',
	'location' => 'Location',
	'password' => 'Salasana',
	'confirm_password' => 'Vahvista salasana',
	'i_agree' => 'Hyväksyn',
	'agree_t_and_c' => 'Kun painat <strong class="label label-primary">Rekisteröidy</strong>, hyväksyt meidän <a href="#" data-toggle="modal" data-target="#t_and_c_m">Käyttöehdot ja säännöt</a>.',
	'register' => 'Rekisteröidy',
	'sign_in' => 'Kirjaudu sisään',
	'sign_out' => 'Kirjaudu ulos',
	'terms_and_conditions' => 'Käyttöehdot ja säännöt',
	'successful_signin' => 'Kirjauduit sisään onnistuneesti',
	'incorrect_details' => 'Väärät tiedot',
	'remember_me' => 'Muista minut!',
	'forgot_password' => 'Unohdettu salasana',
	'must_input_username' => 'Sinun täytyy laittaa käyttäjänimi.',
	'must_input_password' => 'Sinun täytyy laittaa salasana.',
	'inactive_account' => 'Tilisi on tällähetkellä inaktiiviinen. Pyysitkö salasanan nollausta?',
	'account_banned' => 'Tilillesi on asetettu porttikielto.',
	'successfully_logged_out' => 'Kirjauduit ulos onnistuneesti.',
	'signature' => 'Allekirjoitus',
	'registration_check_email' => 'Tarkistathan sähköpostiisi tulleen vahvistuslinkin. Et voi kirjautua sisään ennen vahvistusta.',
	'unknown_login_error' => 'Tuntematon virhe tapahtui. Yritä myöhemmin uudelleen.',
	'validation_complete' => 'Kiitos kun rekisteröidyit! Voit nyt kirjautua sisään.',
	'validation_error' => 'Virhe pyyntöäsi käsitellessä. Yritä klikata linkkiä uudestaan.',
	'registration_error' => 'Varmiata, että täytit kaikki kentät, käyttäjänimi on 3-20 merkkiä ja salasana 6-20 merkkiä pitkä.',
	'username_required' => 'Kirjoita käyttäjänimi.',
	'password_required' => 'Kirjoita salasana.',
	'email_required' => 'Kirjoita sähköpostiosoite.',
	'mcname_required' => 'Kirjoita Minecraft -nimesi.',
	'accept_terms' => 'Sinun täytyy hyväksyä käyttöehdot ja säännöt ennen kuin voit rekisteröityä.',
	'invalid_recaptcha' => 'Virheellinen reCAPTCHA.',
	'username_minimum_3' => 'Käyttäjänimesi täytyy olla vähintään 3 merkkiä pitkä.',
	'username_maximum_20' => 'Käyttäjänimesi voi olla enintään 20 merkkiä pitkä.',
	'mcname_minimum_3' => 'Minecraft nimesi täytyy olla vähintään 3 merkkiä pitkä.',
	'mcname_maximum_20' => 'Minecraft nimesi voi olla enintään 20 merkkiä pitkä.',
	'password_minimum_6' => 'Salasana täytyy olla vähintään 6 merkkiä pitkä.',
	'password_maximum_30' => 'Salasana voi olla enintään 20 merkkiä pitkä.',
	'passwords_dont_match' => 'Salasanasi eivät täsmää!.',
	'username_mcname_email_exists' => 'Your username, Minecraft username or email address already exists. Have you already created an account?',
	'invalid_mcname' => 'Minecraft nimesi ei vastaa yhtäkään pelitiliä.',
	'mcname_lookup_error' => 'Mojangin palvelimiin ei saatu yhteyttä. Yritä myöhemmin uudelleen.',
	'signature_maximum_900' => 'Allekirjoituksesi voi olla enintään 900 merkkiä.',
	'invalid_date_of_birth' => 'Invalid date of birth.',
	'location_required' => 'Please enter a location.',
	'location_minimum_2' => 'Your location must be a minimum of 2 characters.',
	'location_maximum_128' => 'Your location must be a maximum of 128 characters.',
	
	// UserCP
	'user_cp' => 'KäyttäjäCP',
	'no_file_chosen' => 'Ei valittua tiedostoa',
	'private_messages' => 'Yksityisviestit',
	'profile_settings' => 'Profiiliasetukset',
	'your_profile' => 'Profiilisi',
	'topics' => 'Ketjut',
	'posts' => 'Viestit',
	'reputation' => 'Maine',
	'friends' => 'Ystävät',
	'alerts' => 'Ilmoitukset',
	
	// Messaging
	'new_message' => 'Uusi viesti',
	'no_messages' => 'Ei viestejä',
	'and_x_more' => 'ja {x} lisää', // Don't replace "{x}"
	'system' => 'Järjestelmä',
	'message_title' => 'Viestin otsikko',
	'message' => 'Viesti',
	'to' => 'Vastaanottaja(t):',
	'separate_users_with_comma' => 'Käyttäjät erotetaan pilkulla (",")',
	'viewing_message' => 'Näytetään viesti',
	'delete_message' => 'Poista viesti',
	'confirm_message_deletion' => 'Oletko varma, että haluat poistaa tämän viestin?',
	
	// Profile settings
	'display_name' => 'Näytettävä nimi',
	'upload_an_avatar' => 'Lataa avatar (.jpg, .png or .gif):',
	'use_gravatar' => 'Käytä Gravataria?',
	'change_password' => 'Vaihda salasana',
	'current_password' => 'Nykyinen salasana',
	'new_password' => 'Uusi salasana',
	'repeat_new_password' => 'Toista uusi salasana',
	'password_changed_successfully' => 'Salasana vaihdettu onnistuneesti',
	'incorrect_password' => 'Nykyinen salasana on väärin',
	'update_minecraft_name_help' => 'This will update your website username to your current Minecraft username. You can only perform this action once every 30 days.',
	'unable_to_update_mcname' => 'Unable to update Minecraft username.',
	'display_age_on_profile' => 'Display age on profile?',
	'two_factor_authentication' => 'Two Factor Authentication',
	'enable_tfa' => 'Enable Two Factor Authentication',
	'tfa_type' => 'Two Factor Authentication type:',
	'authenticator_app' => 'Authentication App',
	'tfa_scan_code' => 'Please scan the following code within your authentication app:',
	'tfa_code' => 'If your device does not have a camera, or you are unable to scan the QR code, please input the following code:',
	'tfa_enter_code' => 'Please enter the code displaying within your authentication app:',
	'invalid_tfa' => 'Invalid code, please try again.',
	'tfa_successful' => 'Two factor authentication set up successfully. You will need to authenticate every time you log in from now on.',
	'confirm_tfa_disable' => 'Are you sure you wish to disable two factor authentication?',
	'tfa_disabled' => 'Two factor authentication disabled.',
	'tfa_enter_email_code' => 'We have sent you a code within an email for verification. Please enter the code now:',
	'tfa_email_contents' => 'A login attempt has been made to your account. If this was you, please input the following two factor authentication code when asked to do so. If this was not you, you can ignore this email, however a password reset is advised. The code is only valid for 10 minutes.',
	
	// Alerts
	'viewing_unread_alerts' => 'Näytetään lukemattomat ilmoitukset. Merkitse <a href="/user/alerts/?view=read"><span class="label label-success">luetuiksi</span></a>.',
	'viewing_read_alerts' => 'Näytetään luetut ilmoitukset. Merkitse <a href="/user/alerts/"><span class="label label-warning">lukemattomiksi</span></a>.',
	'no_unread_alerts' => 'Sinulla ei ole lukemattomia ilmoituksia.',
	'no_alerts' => 'Ei näytettäviä ilmoituksia',
	'no_read_alerts' => 'Sinulla ei ole luettuja ilmoituksia.',
	'view' => 'Näytä',
	'alert' => 'Ilmoitus',
	'when' => 'Koska:',
	'delete' => 'Poista',
	'tag' => 'Käyttäjätagi',
	'tagged_in_post' => 'Sinut on merkitty viestissä',
	'report' => 'Raportoi',
	'deleted_alert' => 'Ilmoitus poistettu onnistuneesti',
	
	// Warnings
	'you_have_received_a_warning' => 'Olet saanut varoituksen ylläpitäjältä {x} {y}.', // Don't replace "{x}" or "{y}"
	'acknowledge' => 'Myönnä',
	
	// Forgot password
	'password_reset' => 'Salasanan tyhjennys',
	'email_body' => 'Sait tämän sähköpostin, koska pyysit salasanan tyhjennystä. Nollataksesi salasanan käytä seuraavaa linkkiä:', // Body for the password reset email
	'email_body_2' => 'Jos et pyytänyt tätä, voit jättää huomiotta tämän sähköpostin.',
	'password_email_set' => 'Onnistui. Tarkista sähköpostisi seuraavista ohjeista.',
	'username_not_found' => 'Käyttäjänimeä ei ole olemassa.',
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
	'user_hasnt_registered' => 'Tämä käyttäjä ei ole vielä rekisteröitynyt',
	'user_no_friends' => 'Tämä käyttäjä ei ole lisännyt ystäviä',
	'send_message' => 'Lähetä viesti',
	'remove_friend' => 'Poista ystävistä',
	'add_friend' => 'Lisää ystäväksi',
	'last_online' => 'Viimeksi online:',
	'find_a_user' => 'Find a user',
	'user_not_following' => 'This user does not follow anyone.',
	'user_no_followers' => 'This user has no followers.',
	'following' => 'FOLLOWING',
	'followers' => 'FOLLOWERS',
	'display_location' => 'From {x}.', // Don't replace {x}, which will be the user's location
	'display_age_and_location' => '{x}, from {y}.', // Don't replace {x} which will be the user's age, and {y} which will be their location
	'write_on_user_profile' => 'Write something on {x}\'s profile...', // Don't replace {x}
	'write_on_own_profile' => 'Write something on your profile...',
	'profile_posts' => 'Profile Posts',
	'no_profile_posts' => 'No profile posts yet.',
	'invalid_wall_post' => 'Invalid wall post. Please ensure your post is between 2 and 2048 characters.',
	'about' => 'About',
	'reply' => 'Reply',
	'x_likes' => '{x} likes', // Don't replace {x}
	'likes' => 'Likes',
	'no_likes' => 'No likes.',
	'post_liked' => 'Post liked.',
	'post_unliked' => 'Post unliked.',
	'no_posts' => 'No posts.',
	'last_5_posts' => 'Last 5 posts',
	
	// Staff applications
	'staff_application' => 'Ylläpitohakemus',
	'application_submitted' => 'Hakemus lähetetty onnistuneesti.',
	'application_already_submitted' => 'Olet jo lähettänyt hakemuksen. Odota, että se on käsitelty ennen kuin voit lähettää uuden.',
	'not_logged_in' => 'Kirjaudu sisään nähdäksesi tämän sivun.',
	'application_accepted' => 'Hakemuksesi on hyväksytty.',
	'application_rejected' => 'Hakemuksesi on hylätty.'
);

/*
 *  Moderation related
 */
$mod_language = array(
	'mod_cp' => 'ModCP',
	'overview' => 'Yleisnäkymä',
	'reports' => 'Raportit',
	'punishments' => 'Rangaistukset',
	'staff_applications' => 'Ylläpitohakemukset',
	
	// Punishments
	'ban' => 'Ban',
	'unban' => 'Unban',
	'warn' => 'Varoitus',
	'search_for_a_user' => 'Etsi käyttäjää',
	'user' => 'Käyttäjä:',
	'ip_lookup' => 'IP:n katselu:',
	'registered' => 'Rekisteröitynyt:',
	'reason' => 'Syy:',
	
	// Reports
	'report_closed' => 'Raportti suljettu.',
	'new_comment' => 'Uusi kommentti',
	'comments' => 'Kommentit',
	'only_viewed_by_staff' => 'Vain ylläpito voi katsoa',
	'reported_by' => 'Raportoijana',
	'close_issue' => 'Sulje ongelma.',
	'report' => 'Raportti:',
	'view_reported_content' => 'Näytä raportoitu sisältö',
	'no_open_reports' => 'Ei avonaisia raportteja',
	'user_reported' => 'Käyttäjä raportoitu',
	'type' => 'Tyyppi',
	'updated_by' => 'Päivittänyt',
	'forum_post' => 'Foorumiviesti',
	'user_profile' => 'Käyttäjäprofiili',
	'comment_added' => 'Kommentti lisätty.',
	'new_report_submitted_alert' => 'Uusi raportti käyttäjältä {x} koskien käyttäjää {y}', // Don't replace "{x}" or "{y}"
	
	// Staff applications
	'comment_error' => 'Varmista, että kommenttisi on vähintään 2 ja enintään 2048 merkkiä pitkä.',
	'viewing_open_applications' => 'Näytetään <span class="label label-info">avoimet</span> hakemukset. Vaihda <a href="/mod/applications/?view=accepted"><span class="label label-success">hyväksytyksi</span></a> tai <a href="/mod/applications/?view=declined"><span class="label label-danger">hylätyksi</span></a>.',
	'viewing_accepted_applications' => 'Näytetään <span class="label label-success">hyväksytyt</span> hakemukset. Vaihda <a href="/mod/applications/"><span class="label label-info">avoimeksi</span></a> tai <a href="/mod/applications/?view=declined"><span class="label label-danger">hylätyksi</span></a>.',
	'viewing_declined_applications' => 'Näytetään <span class="label label-danger">hylätyt</span> hakemukset. Vaihda <a href="/mod/applications/"><span class="label label-info">avoimeksi</span></a> tai <a href="/mod/applications/?view=accepted"><span class="label label-success">hyväksytyksi</span></a>.',
	'time_applied' => 'Aika jolloin haettu',
	'no_applications' => 'Ei hakemuksia tässä kategoriassa',
	'viewing_app_from' => 'Näytetään hakemus käyttäjältä {x}', // Don't replace "{x}"
	'open' => 'Avoin',
	'accepted' => 'Hyväksytty',
	'declined' => 'Hylätty',
	'accept' => 'Hyväksy',
	'decline' => 'Hylkää',
	'new_app_submitted_alert' => 'Uusi hakemus lähetetty käyttäjältä {x}' // Don't replace "{x}"
);

/* 
 *  General
 */
$general_language = array(
	// Homepage
	'news' => 'Uutiset',
	'social' => 'Sosiaaliset',
	'join' => 'Join',
	
	// General terms
	'submit' => 'Lähetä',
	'close' => 'Sulje',
	'cookie_message' => '<strong>Sivusto käyttää evästeitä parantaakseen käyttökokemusta.</strong><p>Jos jatkat, hyväksyt evästeiden käytön.</p>',
	'theme_not_exist' => 'Valittua teemaa ei ole olemassa.',
	'confirm' => 'Vahvista',
	'cancel' => 'Peruuta',
	'guest' => 'Vieras',
	'guests' => 'Vieraat',
	'back' => 'Takaisin',
	'search' => 'Hae',
	'help' => 'Apua',
	'success' => 'Onnistui',
	'error' => 'Virhe',
	'view' => 'Näytä',
	'info' => 'Info',
	'next' => 'Next',
	
	// Play page
	'connect_with' => 'Palvelimen IP {x}', // Don't replace {x}
	'online' => 'Online',
	'offline' => 'Offline',
	'status' => 'Status:',
	'players_online' => 'Pelaajia Online:',
	'queried_in' => 'Kysytty ajassa:',
	'server_status' => 'Serverin Status',
	'no_players_online' => 'Pelaajia ei ole online!',
	'x_players_online' => 'Tällähetkellä {x} pelaajaa online.', // Don't replace {x}
	
	// Other
	'page_loaded_in' => 'Sivu ladattu ajassa {x} sekunnissa.', // Don't replace {x}; 's' stands for 'seconds'
	'none' => 'Ei yhtään',
	'404' => 'Emme löytäneet sivua.'
);

/* 
 *  Forum
 */
$forum_language = array(
	// Latest discussions view
	'forums' => 'Foorumit',
	'discussion' => 'Keskustelut',
	'stats' => 'Tilastot',
	'last_reply' => 'Viimeisin viesti',
	'ago' => 'sitten',
	'by' => 'by',
	'in' => '',
	'views' => 'näyttöjä',
	'posts' => 'viestejä',
	'topics' => 'ketjuja',
	'topic' => 'Ketju',
	'statistics' => 'Tilastot',
	'overview' => 'Yleisnäkymä',
	'latest_discussions' => 'Viimeisimmät keskustelut',
	'latest_posts' => 'Viimeisimmät viestit',
	'users_registered' => 'Käyttäjiä rekisteröitynä:',
	'latest_member' => 'Uusin jäsen:',
	'forum' => 'Foorumi',
	'last_post' => 'Viimeisin viesti',
	'no_topics' => 'Ei viestiketjuja',
	'new_topic' => 'Uusi viestiketju',
	'subforums' => 'Alifoorumit:',
	
	// View topic view
	'home' => 'Etusivu',
	'topic_locked' => 'Ketju lukittu',
	'new_reply' => 'Uusi vastaus',
	'mod_actions' => 'Mod-toiminnot',
	'lock_thread' => 'Lukitse ketju',
	'unlock_thread' => 'Avaa ketju',
	'merge_thread' => 'Yhdistä ketju',
	'delete_thread' => 'Poista ketju',
	'confirm_thread_deletion' => 'Oletko varma, että haluat poistaa tämän ketjun?',
	'move_thread' => 'Siirrä ketju',
	'sticky_thread' => '"Liimaa" ketju',
	'report_post' => 'Raportoi viesti',
	'quote_post' => 'Lainaa viestiä',
	'delete_post' => 'Poista viesti',
	'edit_post' => 'Muokkaa viestiä',
	'reputation' => 'maine',
	'confirm_post_deletion' => 'Oletko varma, että haluat poistaa tämän viestin?',
	'give_reputation' => 'Anna mainetta',
	'remove_reputation' => 'Poista mainetta',
	'post_reputation' => 'Viestin maine',
	'no_reputation' => 'Ei mainetta tälle viestille',
	're' => '',
	
	// Create post view
	'create_post' => 'Luo viesti',
	'post_submitted' => 'Viesti lähetetty',
	'creating_post_in' => 'Luodaan viestiä: ',
	'topic_locked_permission_post' => 'Viestiketju on lukittu. Voit silti lähettää viestin.',
	
	// Edit post view
	'editing_post' => 'Muokataan viestiä',
	
	// Sticky threads
	'thread_is_' => 'Ketju ',
	'now_sticky' => 'on nyt liimattu',
	'no_longer_sticky' => ' ei ole enää liimattu',
	
	// Create topic
	'topic_created' => 'Viestiketju luotu.',
	'creating_topic_in_' => 'Luodaan viestiketjua foorumissa ',
	'thread_title' => 'Ketjun otsikko',
	'confirm_cancellation' => 'Oletko varma?',
	'label' => 'Leima',
	
	// Reports
	'report_submitted' => 'Raportti lähetetty.',
	'view_post_content' => 'Näytä viestin sisältö',
	'report_reason' => 'Raportin syy',
	
	// Move thread
	'move_to' => 'Siirrä:',
	
	// Merge threads
	'merge_instructions' => 'Ketju jonka haluat yhdistää <strong>täytyy</strong> olla samassa foorumissa. Siirrä ketju tarvittaessa.',
	'merge_with' => 'Yhdistä:',
	
	// Other
	'forum_error' => 'Emme löytäneet foorumia tai viestikietjua.',
	'are_you_logged_in' => 'Oletko kirjautunut sisään?',
	'online_users' => 'Käyttäjiä paikalla',
	'no_users_online' => 'Käyttäjiä ei ole paikalla.',
	
	// Search
	'search_error' => 'Laita hakukyselyyn 1-20 merkkiä.',
	
	//Share on a social-media.
	'sm-share' => 'Jakaa',
	'sm-share-facebook' => 'Jaa Facebookissa',
	'sm-share-twitter' => 'Jaa Twitterissä',
);

/*
 *  Emails
 */
$email_language = array(
	// Registration email
	'greeting' => 'Hei',
	'message' => 'Kiitos rekisteröitymisestä! Suorittaaksesi rekisteröinnin, klikkaa seuraavaa linkkiä:',
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
	'1_day' => 'päivä sitten',
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
	'display_records_per_page' => 'Näytä _MENU_ kappaletta per sivu', // Don't replace "_MENU_"
	'nothing_found' => 'Ei tuloksia',
	'page_x_of_y' => 'Näytetään sivu _PAGE_  _PAGES_:sta sivusta', // Don't replace "_PAGE_" or "_PAGES_"
	'no_records' => 'Ei saatavilla',
	'filtered' => '(suodatettiin _MAX_ tuloksesta yhteensä)' // Don't replace "_MAX_"
);
 
/*
 *  API language
 */
$api_language = array(
	'register' => 'Complete Registration'
);
 
?>
