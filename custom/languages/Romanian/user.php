<?php 
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr2
 *
 *  License: MIT
 *
 *  Romanian Language - Users
 *  Translation By @BaxAndrei ( https://baxandrei.ro )
 *  Last Update: 15/07/2017
 */

$language = array(
	/*
	 *  User Related
	 */
	'guest' => 'Vizitator',
	'guests' => 'Vizitatori',
	
	// UserCP
	'user_cp' => 'Panou Utilizator',
	'user_cp_icon' => '<i class="fa fa-cogs" aria-hidden="true"></i>',
	'overview' => 'Prezentare generală',
	'user_details' => 'Detalii utilizator',
	'profile_settings' => 'Setările profilului',
	'successfully_logged_out' => 'Ați fost deconectat cu succes.',
	'messaging' => 'Mesaje Private',
	'click_here_to_view' => 'Faceți clic aici pentru a vedea.',
	'moderation' => 'Moderare',
	'administration' => 'Administrare',
	'alerts' => 'Alerte',
	'delete_all' => 'Şterge tot',
	
	// Profile settings
	'field_is_required' => 'Câmpul "{x}" este necesar.', // Don't replace {x}
	'settings_updated_successfully' => 'Setările s-au actualizat cu succes.',
	'password_changed_successfully' => 'Parola a fost schimbată cu succes.',
	'change_password' => 'Schimbaţi parola',
	'current_password' => 'Parola actuală',
	'new_password' => 'Parolă nouă',
	'confirm_new_password' => 'Confirma noua parolă',
	'incorrect_password' => 'Parola dvs. este incorectă.',
	'two_factor_auth' => 'Autentificare în doi pași',
	'enable' => 'Activează',
	'disable' => 'Dezactivează',
	'tfa_scan_code' => 'Scanați următorul cod în aplicația de autentificare:',
	'tfa_code' => 'Dacă dispozitivul dvs. nu are o cameră foto sau nu puteți scana codul QR, introduceți următorul cod:',
	'tfa_enter_code' => 'Introduceți codul afișat în aplicația dvs. de autentificare:',
	'invalid_tfa' => 'Cod invalid, va rugam incercati din nou.',
	'tfa_successful' => 'Autentificarea în doi pași a fost configurată cu succes și de acum este activă. Acum trebuie ca de fiecare dată când vă autentificati să introduceți codul afișat în aplicație.',
	'active_language' => 'Limbă site',
    'timezone' => 'Fus orar',
    'upload_new_avatar' => 'Încărcați un nou avatar',
	
	// Alerts
	'user_tag_info' => 'Ai fost etichetat într-o postare de către {x}.', // Don't replace {x}
	'no_alerts' => 'Nu există alerte noi.',
	'view_alerts' => 'Vizualizați alertele',
	'x_new_alerts' => 'Aveți {x} alerte noi.', // Don't replace {x}
	'no_alerts_usercp' => 'Momentan nu aveți alerte.',
	
	// Registraton
	'registration_check_email' => 'Vă mulțumim pentru înregistrare! Verificați e-mailurile pentru un link de validare pentru a vă completa înregistrarea. Dacă nu reușiți să găsiți e-mailul, verificați dosarul de spam.',
	'username' => 'Nume de utilizator',
	'nickname' => 'Poreclă',
	'minecraft_username' => 'Nume Minecraft',
	'email_address' => 'Adresa de e-mail',
	'email' => 'E-mail',
	'password' => 'Parolă',
	'confirm_password' => 'Confirmă parola',
	'i_agree' => 'Sunt de acord',
	'agree_t_and_c' => 'Făcând clic pe <strong class="label label-primary">Înregistrare</strong>, sunteți de acord cu <a href="{x}" target="_blank">Termenii și condițiile</a> noastre.',
	'create_an_account' => 'Creează un cont',
	'terms_and_conditions' => 'Termeni si conditii',
	'validation_complete' => 'Contul dvs. a fost validat, acum vă puteți conecta.',
	'validation_error' => 'A apărut o eroare necunoscută în timpul validări contul dvs., vă rugăm să contactați un administrator de site web.',
	'signature' => 'Semnătură',

    // Registration - Authme
    'connect_with_authme' => 'Conectați-vă contul cu AuthMe',
    'authme_help' => 'Introduceți detaliile contului dvs. AuthMe din joc. Dacă nu aveți deja un cont în joc, Conectați-vă pe server acum și urmați instrucțiunile furnizate.',
    'unable_to_connect_to_authme_db' => 'Nu se poate conecta la baza de date AuthMe. Dacă această eroare persistă, contactați un administrator.',
    'authme_account_linked' => 'Contul a fost conectat cu succes.',
    'authme_email_help_1' => 'În final, introduceți adresa dvs. de e-mail.',
    'authme_email_help_2' => 'În final, introduceți adresa dvs. de e-mail și alegeți și un nume de utilizator pentru contul dvs.',

	// Registration errors
	'username_required' => 'Este necesar un nume de utilizator.',
	'email_required' => 'Este necesară o adresă de e-mail.',
	'password_required' => 'Este necesară o parolă.',
	'mcname_required' => 'Este necesar un nume de utilizator Minecraft.',
	'accept_terms' => 'Trebuie să acceptați termenii și condițiile înainte de a vă înregistra.',
	'username_minimum_3' => 'Numele dvs. de utilizator trebuie să aibă cel puțin 3 caractere.',
	'mcname_minimum_3' => 'Numele de utilizator Minecraft trebuie să aibă cel puțin 3 caractere.',
	'password_minimum_6' => 'Parola dvs. trebuie să aibă cel puțin 6 caractere.',
	'username_maximum_20' => 'Numele dvs. de utilizator trebuie să aibă maximum 20 de caractere.',
	'mcname_maximum_20' => 'Numele de utilizator Minecraft trebuie să aibă maximum 20 de caractere.',
	'password_maximum_30' => 'Parola dvs. trebuie să aibă maximum 30 de caractere.',
	'passwords_dont_match' => 'Parolele nu se potrivesc.',
	'username_mcname_email_exists' => 'Numele de utilizator sau adresa dvs. de e-mail există deja în baza de date.',
	'invalid_mcname' => 'Numele dvs. de utilizator Minecraft este invalid.',
	'invalid_email' => 'E-mailul tău este invalid.',
	'mcname_lookup_error' => 'A apărut o eroare la comunicarea cu serverele Mojang pentru a vă verifica numele de utilizator. Vă rugăm să încercați din nou mai târziu.',
	'invalid_recaptcha' => 'Răspuns reCAPTCHA invalid.',
	'verify_account' => 'Verifica contul',
	'verify_account_help' => 'Urmați instrucțiunile de mai jos pentru a putea verifica dacă sunteți proprietarul contului Minecraft în cauză.',
	'verification_failed' => 'Verificare eșuată. Vă rugăm încercați din nou.',
	'verification_success' => 'Validat cu succes! Acum te poți loga.',
	
	// Login
	'successful_login' => 'V-ați conectat cu succes.',
	'incorrect_details' => 'Ați introdus detalii incorecte.',
	'inactive_account' => 'Contul dvs. este inactiv. Verificați e-mailurile pentru un link de validare, inclusiv în dosarul dvs. de spam.',
	'account_banned' => 'Acest cont este banat.',
	'forgot_password' => 'Ai uitat parola?',
	'remember_me' => 'Ține-mă minte',
	'must_input_username' => 'Trebuie să introduceți un nume de utilizator.',
	'must_input_password' => 'Trebuie să introduceți o parolă.',

    // Forgot password
    'forgot_password_instructions' => 'Introduceți adresa dvs. de e-mail pentru a vă putea trimite instrucțiuni suplimentare privind resetarea parolei.',
    'forgot_password_email_sent' => 'Dacă există un cont cu adresa de e-mail specificata, a fost trimis un e-mail care conține instrucțiuni suplimentare. Dacă nu puteți găsi e-mailul, încercați să verificați dosarul dvs. de spam.',
    'unable_to_send_forgot_password_email' => 'Imposibil de trimis e-mail de parolă uitată. Contactați un administrator.',
    'enter_new_password' => 'Confirmați adresa dvs. de e-mail și introduceți o nouă parolă mai jos.',
    'incorrect_email' => 'Adresa de e-mail pe care ați introdus-o nu corespunde solicitării.',
    'forgot_password_change_successful' => 'Parola dvs. a fost modificată cu succes. Acum te poți loga.',
	
	// Profile pages
	'profile' => 'Profil',
	'follow' => 'Urmăriţi',
	'no_wall_posts' => 'Momentan nu există posturi de perete aici.',
	'change_banner' => 'Schimbați bannerul',
	'post_on_wall' => 'Postați pe peretele lui {x}', // Don't replace {x}
	'invalid_wall_post' => 'Asigurați-vă că postarea dvs. are între 1 și 10000 de caractere.',
	'1_reaction' => 'O reacție',
	'x_reactions' => '{x} reacții', // Don't replace {x}
	'1_like' => 'O apreciere',
	'x_likes' => '{x} aprecieri', // Don't replace {x}
	'1_reply' => 'Un răspuns',
	'x_replies' => '{x} răspunsuri', // Don't replace {x}
	'no_replies_yet' => 'Nu există răspunsuri încă.',
	'feed' => 'Perete',
	'about' => 'Despre',
	'replies' => 'Răspunsuri',
	'new_reply' => 'Adaugă un răspuns nou',
	'registered' => 'Înregistrat:',
	'last_seen' => 'Vazut ultima data:',
	
	// Reports
	'invalid_report_content' => 'Nu s-a putut crea un raport. Asigurați-vă că motivul pentru raport este între 2 și 1024 de caractere.',
	'report_post_content' => 'Introduceți un motiv pentru raportul dvs.',
	'report_created' => 'Raportul a fost creat cu succes.',
	
	// Messaging
	'no_messages' => 'Nu există mesaje noi.',
	'no_messages_full' => 'Momentan nu aveți mesaje.',
	'view_messages' => 'Vizualizați mesajele',
	'x_new_messages' => 'Aveți {x} mesaje noi.', // Don't replace {x}
	'new_message' => 'Mesaj nou',
	'message_title' => 'Titlul mesajului',
	'to' => 'Către',
	'separate_users_with_commas' => 'Separați utilizatorii cu virgule',
	'title_required' => 'Introduceți un titlu.',
	'content_required' => 'Introduceți conținut.',
	'users_to_required' => 'Introduceți minim un destinatar al mesajului.',
	'cant_send_to_self' => 'Nu puteți trimite un mesaj către dvs.!',
	'title_min_2' => 'Titlul trebuie să aibă cel puțin 2 caractere.',
	'content_min_2' => 'Conținutul trebuie să aibă cel puțin 2 caractere.',
	'title_max_64' => 'Titlul trebuie să aibă maximum 64 de caractere.',
	'content_max_20480' => 'Conținutul trebuie să fie de maximum 20480 de caractere.',
	'max_pm_10_users' => 'Puteți trimite un mesaj către maxim 10 utilizatori odată.',
	'message_sent_successfully' => 'Mesaj trimis cu succes.',
	'participants' => 'Participanți',
	'last_message' => 'Ultimul mesaj',
	'by' => 'de',
	'leave_conversation' => 'Părăsește conversația',
	'confirm_leave' => 'Sunteți sigur că doriți să părăsiți această conversație?',
	
	// Reactions
	'reactions' => 'Reacții',
	
	/*
	 *  Infractions area
	 */
	'infractions' => 'Abateri',
	
	
	/*
	 *  Emails
	 */
	'email_greeting' => 'Salut,',
	'email_message' => 'Vă mulțumim pentru înregistrare! Pentru a vă completa înregistrarea, faceți clic pe următorul link:',
    'forgot_password_email_message' => 'Pentru a vă reseta parola, faceți clic pe următorul link. Dacă nu ați solicitat acest lucru, puteți șterge în siguranță acest e-mail.',
	'email_thanks' => 'Cu stimă,'
);
