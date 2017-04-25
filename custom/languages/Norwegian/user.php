<?php 
/*
 *  Translations by Thesevs and OscarWoHA
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC versjon 2.0.0-dev
 *
 *  License: MIT
 *
 *  Norsk språk - Brukere
 */

$language = array(
	/*
	 *  User Related
	 */
	'guest' => 'Gjest',
	'guests' => 'Gjester',
	
	// UserCP
	'user_cp' => 'BrukerKP',
	'user_cp_icon' => '<i class="fa fa-cogs" aria-hidden="true"></i>',
	'overview' => 'Oversikt',
	'user_details' => 'Dine detaljer',
	'profile_settings' => 'Profilinstillinger',
	'successfully_logged_out' => 'Du har nå logget ut.',
	'messaging' => 'Meldinger',
	'click_here_to_view' => 'Trykk her for å se',
	'moderation' => 'Moderasjon',
	'administration' => 'Administrasjon',
	'alerts' => 'Viktige meldinger',
	'delete_all' => 'Slett alle',
	
	// Profile settings
	'field_is_required' => '{x} er påkrevd.', // Don't replace {x}
	'settings_updated_successfully' => 'Instillinger oppdatert.',
	'password_changed_successfully' => 'Passord har blitt endret.',
	'change_password' => 'Endre passord',
	'current_password' => 'Din passord',
	'new_password' => 'Ny passord',
	'confirm_new_password' => 'Bekreft ny passord',
	'incorrect_password' => 'Feil passord',
	'two_factor_auth' => 'To-stegs-verifikasjon',
	'enable' => 'Aktiver',
	'disable' => 'Deaktiver',
	'tfa_scan_code' => 'Vennligst skann denne koden med tostegsverifikasjons-applikasjonen:',
	'tfa_code' => 'Om enheten din ikke har kamera kan du skrive inn denne koden:',
	'tfa_enter_code' => 'Skriv inn koden som dukker opp på skjermen din',
	'invalid_tfa' => 'Ugyldig kode, prøv igjen.',
	'tfa_successful' => 'To-stegs-verifikasjon fullført.',
	'active_language' => 'Språk',
	
	// Alerts
	'user_tag_info' => 'Du har blitt tagget i et innlegg av {x}.', // Don't replace {x}
	'no_alerts' => 'Ingen nye viktige meldinger',
	'view_alerts' => 'Vis meldinger',
	'x_new_alerts' => 'Du har {x} nye viktige meldinger.', // Don't replace {x}
	'no_alerts_usercp' => 'Du har ingen nye viktige meldinger',
	
	// Registraton
	'registration_check_email' => 'Takk for at du registrerte deg! Sjekk e-posten din for en bekreftelseslenke. Det kan være du må lete i Spam-mappen.',
	'username' => 'Brukernavn',
	'nickname' => 'Kallenavn',
	'minecraft_username' => 'Minecraft brukernavn',
	'email_address' => 'E-postadresse',
	'email' => 'E-post',
	'password' => 'Passord',
	'confirm_password' => 'Bekreft passordet',
	'i_agree' => 'Jeg godtar',
	'agree_t_and_c' => 'Ved å klikke på <strong class="label label-primary">Registrer</strong> vedkjenner du at du aksepterer våre <a href="{x}" target="_blank">Vilkår for tjenesten</a>.',
	'create_an_account' => 'Opprett ny brukerkonto',
	'terms_and_conditions' => 'Vilkår for tjenesten',
	'validation_complete' => 'Brukerkontoen er nå bekreftet.',
	'validation_error' => 'Det oppstod en feil under bekreftelsen av kontoen din. Kontakt en nettstedsadministrator med en gang!',
	'signature' => 'Signatur',

    	 // Registration - Authme
	'connect_with_authme' => 'Koble til brukeren din med AuthMe.',
    	'authme_help' => 'Vennligst skriv inn dine ingame AuthMe brukers pålogginsinformsajon. Hvis du ikke har en bruker ingame, logg inn på serveren og følg instruksjonene.',
   	'unable_to_connect_to_authme_db' => 'En feil oppstod under tilkoblingen til AuthMe databasen. Hvis denne feilen gjentar seg, vennligst kontakt en administrator.',
   	'authme_account_linked' => 'Bruker suksessfullt tilkoblet.',
    	'authme_email_help_1' => 'Til slutt, vennligst skriv inn ditt email adresse.',
    	'authme_email_help_2' => 'Til slutt, vennligst skriv inn ditt email adresse, og velg et nytt brukernavn til din bruker.',
	// Registration errors
	'username_required' => 'Fyll inn et brukeravn.',
	'email_required' => 'Fyll inn en email.',
	'password_required' => 'Fyll inn et passord.',
	'mcname_required' => 'Fyll inn et minecraft brukernavn.',
	'accept_terms' => 'Du må akseptere terms and conditions før du registrerer deg.',
	'username_minimum_3' => 'Ditt brukernavn må være minst 3 tegn.',
	'mcname_minimum_3' => 'Ditt minecraft brukernavn må være minst 3 tegn.',
	'password_minimum_6' => 'Ditt passord må være minst 6 tegn.',
	'username_maximum_20' => 'Ditt brukernavn må være maks 20 tegn.',
	'mcname_maximum_20' => 'Ditt minecraft brukernavn må være minst 20 tegn.',
	'password_maximum_30' => 'Ditt passord må være minst 30 tegn.',
	'passwords_dont_match' => 'Passordene stemmer ikke.',
	'username_mcname_email_exists' => 'Dette brukernavnet eller emailen eksisterer allerede.',
	'invalid_mcname' => 'Det minecraft brukernavnet er ugyldig.',
	'invalid_email' => 'Ditt email er ugyldig',
	'mcname_lookup_error' => 'Det oppstod en feil mens vi prøvde å verifisere brukernavnet ditt med Mojang, prøv igjen senere.',
	'invalid_recaptcha' => 'Feil reCAPTCHA',
	'verify_account' => 'Verifiser bruker.',
	'verify_account_help' => 'Vennligst følg instruksjonene så vi kan verifisere at dette er din bruker.',
	'verification_failed' => 'Verifisering feilet, prøv igjen.',
	'verification_success' => 'Suksessfullt verifisert, du kan nå logge inn!',
	
	// Login
	'successful_login' => 'Du har nå logget inn.',
	'incorrect_details' => 'Du har skrevet inn feil detaljer.',
	'inactive_account' => 'Din bruker er inaktiv. Sjekk mailen din for verifiseringslink.',
	'account_banned' => 'Denne brukeren er bannet.',
	'forgot_password' => 'Glemt passord?',
	'remember_me' => 'Husk meg',
	'must_input_username' => 'Du må skrive inn et brukernavn.',
	'must_input_password' => 'Du må skrive inn et passord.',
	
	// Profile pages
	'profile' => 'Profil',
	'follow' => 'Følg',
	'no_wall_posts' => 'Det er ingen innlegg på forumet ditt',
	'change_banner' => 'Endre banner',
	'post_on_wall' => 'Post på {x}s vegg', // Don't replace {x}
	'invalid_wall_post' => 'Sjekk at lengden på posten ikke overskrider 10000 tegn.',
	'1_reaction' => '1 reaksjon',
	'x_reactions' => '{x} reaksjoner', // Don't replace {x}
	'1_like' => '1 likerklikk',
	'x_likes' => '{x} likerklikk', // Don't replace {x}
	'1_reply' => '1 svar',
	'x_replies' => '{x} svar', // Don't replace {x}
	'no_replies_yet' => 'Ingen svar enda',
	'feed' => 'Strøm',
	'about' => 'Om',
	'reactions' => 'Reaksjoner',
	'replies' => 'Svar',
	'new_reply' => 'Nytt svar',
	'registered' => 'Registrerte seg:',
	'last_seen' => 'Sist sett:',
	
	// Reports
	'invalid_report_content' => 'Kunne ikke fullføre rapporteringen. Har du sjekket at den er mellom 2 og 1024 tegn?',
	'report_post_content' => 'Fyll inn grunnlaget for rapporteringen.',
	'report_created' => 'Rapportert.',
	
	// Messaging
	'no_messages' => 'Ingen nye meldinger.',
	'no_messages_full' => 'Du har ingen meldinger.',
	'view_messages' => 'Vis meldinger.',
	'x_new_messages' => 'Du har {x} nye meldinger', // Don't replace {x}
	'new_message' => 'Ny melding',
	'message_title' => 'Meldingstittel',
	'to' => 'Til',
	'separate_users_with_commas' => 'Send til flere brukere ved å skrive \',\' i mellom dem',
	'title_required' => 'Fyll inn en tittel',
	'content_required' => 'Fyll inn en tekst',
	'users_to_required' => 'Fyll inn minst én bruker i \'Til\'-feltet',
	'cant_send_to_self' => 'Du kan ikke sende melding til deg selv!',
	'title_min_2' => 'Tittelen må være maks 2 tegn!',
	'content_min_2' => 'Innlegget må være minst 2 tegn!',
	'title_max_64' => 'Tittelen må være maks 64 tegn!',
	'content_max_20480' => 'Innlegget må være maksimum 20480 tegn.',
	'max_pm_10_users' => 'Meldingen kan sendes til maks 10 personer samtidig.',
	'message_sent_successfully' => 'Meldingen har blitt sendt.',
	'participants' => 'Deltakere',
	'last_message' => 'Siste melding',
	'by' => 'av',
	'leave_conversation' => 'Forlat samtale',
	'confirm_leave' => 'Er du sikker på at du vil forlate denne samtalen?',
	
	// Reactions
	'reactions' => 'Reaksjoner',
	
	/*
	 *  Infractions area
	 */
	'infractions' => 'Straff',
	
	
	/*
	 *  Emails
	 */
	'email_greeting' => 'Hei!',
	'email_message' => 'Takk for at du registrerte deg på vårt nettsted. For å fullføre registreringen må du følge denne lenken:',
	'email_thanks' => 'Takk,'
);
