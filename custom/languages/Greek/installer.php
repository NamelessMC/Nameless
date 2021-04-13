<?php
/*
 *  Made by ArisC
 *  https://github.com/Ar1sC
 *  https://twitter.com/Ar1cC
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  EnglishUS Language - Installation
 */

$language = array(
    /*
     *  Installation
     */
    'install' => 'Εγκατάσταση',
    'pre-release' => 'προδηµοσιεύσει',
    'installer_welcome' => 'Καλωσήρθες στο NamelessMC έκδοση 2.0 προδηµοσιεύσει.',
    'pre-release_warning' => 'Παρακαλείστε να σημειώσετε ότι αυτή η προδηµοσιεύσει δεν προορίζεται για χρήση σε δημόσιο χώρο.',
    'installer_information' => 'Το πρόγραμμα εγκατάστασης θα σας καθοδηγήσει στη διαδικασία εγκατάστασης.',
    'terms_and_conditions' => 'By continuing you agree to the terms and conditions.',
    'new_installation_question' => 'Πρώτον, είναι μια νέα εγκατάσταση?',
    'new_installation' => 'Νέα Εγκατάσταση &raquo;',
    'upgrading_from_v1' => 'Αναβάθμιση από v1 &raquo;',
    'requirements' => 'Απαιτήσεις:',
    'config_writable' => 'core/config.php με δυνατότητα εγγραφής',
    'cache_writable' => 'Cache με δυνατότητα εγγραφής',
    'template_cache_writable' => 'Template Cache με δυνατότητα εγγραφής',
    'exif_imagetype_banners_disabled' => 'Without the exif_imagetype function, server banners will be disabled.',
    'requirements_error' => 'Πρέπει να έχετε όλες τις απαιτούμενες επεκτάσεις εγκατεστημένες, και να έχουν οριστεί σωστά δικαιώματα, προκειμένου να προχωρήσει με την εγκατάσταση.',
    'proceed' => 'συνεχίζω',
    'database_configuration' => 'Database Configuration',
    'database_address' => 'Database Address',
    'database_port' => 'Database Port',
    'database_username' => 'Database Username',
    'database_password' => 'Database Password',
    'database_name' => 'Database Name',
    'nameless_path' => 'Installation Path',
    'nameless_path_info' => 'This is the path Nameless is installed in, relative to your domain. For example, if Nameless is installed at example.com/forum, this needs to be <strong>forum</strong>. Leave empty if Nameless is not in a subfolder.',
    'friendly_urls' => 'Friendly URLs',
    'friendly_urls_info' => 'Friendly URLs will improve the readability of URLs in your browser.<br />For example: <br /><code>example.com/index.php?route=/forum</code><br />would become:<br /><code>example.com/forum</code><br /><div class="ui inverted orange segment"><i class="exclamation circle icon"></i><strong>Important!</strong><br />Your server must be configured correctly for this to work. You can see whether you can enable this option by clicking <a href="./rewrite_test" target="_blank" style="color:#2185D0">here</a>.</div>',
    'enabled' => 'Enabled',
    'disabled' => 'Disabled',
    'character_set' => 'Character Set',
    'database_engine' => 'Database Storage Engine',
    'host' => 'Hostname',
    'host_help' => 'The hostname is the <strong>base URL</strong> for your website. Do not include the subfolders from the Installation Path field, or http(s):// here!',
    'database_error' => 'Βεβαιωθείτε οτι όλα τα πεδία έχουν συμπληρωθεί.',
    'submit' => 'Υποβολή',
    'installer_now_initialising_database' => 'Το πρόγραμμα εγκατάστασης προετοιμάζει την βάσης δεδομένων. Αυτό μπορεί να πάρει λίγο χρόνο...',
    'configuration' => 'Διαμόρφωση',
    'configuration_info' => 'Εισάγετε τις βασικές πληροφορίες για την site σας. Οι τιμές αυτές μπορούν να αλλάξουν αργότερα από την διαχείριση.',
    'configuration_error' => 'Εισάγετε ένα έγκυρο όνομα του site σας μήκους μεταξύ 1 έως 32 χαρακτήρες, και έγκυρες διευθύνσεις ηλεκτρονικού ταχυδρομείου μήκους μεταξύ 4 έως 64 χαρακτήρες.',
    'site_name' => 'Ονομα ιστοσελίδας',
    'contact_email' => 'Email Επικοινωνίας',
    'outgoing_email' => 'Εξερχόμενο Email',
    'language' => 'Language',
    'initialising_database_and_cache' => 'Προετοιμασία της βάσης δεδομένων και προσωρινής μνήμης, Παρακαλώ περιμένετε...',
    'unable_to_login' => 'Αδύνατη η πρόσβαση.',
    'unable_to_create_account' => 'Δεν είναι δυνατή η δημιουργία του λογαριασμού',
    'input_required' => 'Παρακαλώ εισάγετε ένα έγκυρο όνομα χρήστη, διεύθυνση ηλεκτρονικού ταχυδρομείου και τον κωδικό πρόσβασης.',
    'input_minimum' => 'Παρακαλούμε βεβαιωθείτε ότι το όνομα χρήστη σας είναι τουλάχιστον 3 χαρακτήρες, η διεύθυνση ηλεκτρονικού ταχυδρομείου σας είναι τουλάχιστον 4 χαρακτήρες και ο κωδικός πρόσβασής σας είναι τουλάχιστον 6 χαρακτήρες.',
    'input_maximum' => 'Παρακαλούμε βεβαιωθείτε ότι το όνομα χρήστη σας είναι το πολύ 20 χαρακτήρες, και η διεύθυνση ηλεκτρονικού ταχυδρομείου και ο κωδικό πρόσβασής σας είναι το πολύ 64 χαρακτήρες.',
    'email_invalid' => 'Your email is not valid.',
    'passwords_must_match' => 'Οι κωδικοί πρόσβασης πρέπει να ταιριάζουν.',
    'creating_admin_account' => 'Δημιουργία Λογαριασμού Διαχειριστή',
    'enter_admin_details' => 'Παρακαλώ εισάγετε τα στοιχεία του λογαριασμού διαχειριστή.',
    'username' => 'Username',
    'email_address' => 'Email Address',
    'password' => 'Password',
    'confirm_password' => 'Confirm Password',
    'upgrade' => 'Αναβάθμιση',
    'input_v1_details' => 'Εισάγετε τα στοιχεία της βάσης δεδομένων για το Nameless έκδοση 1 εγκατάστασης.',
    'installer_upgrading_database' => 'Παρακαλώ περιμένετε ενώ το πρόγραμμα εγκατάστασης αναβαθμίζει τη βάση δεδομένων σας...',
    'errors_logged' => 'Έχουν καταγραφεί σφάλματα. Πατήστε Συνέχεια για να συνεχίσετε με την αναβάθμιση.',
    'continue' => 'Συνέχεια',
    'convert' => 'Μετατροπή',
    'convert_message' => 'Τέλος, θέλετε να μετατρέψετε από ένα διαφορετικό λογισμικό φόρουμ?',
    'yes' => 'Ναι',
    'no' => 'Όχι',
    'converter' => 'Converter',
    'back' => 'Back',
    'unable_to_load_converter' => 'Unable to load converter!',
    'finish' => 'Τέλος',
    'finish_message' => 'Ευχαριστώ για την εγκατάσταση NamelessMC! Μπορείτε τώρα να προχωρήσει στο StaffCP, όπου μπορείτε να ρυθμίσετε περαιτέρω την ιστοσελίδα σας.',
    'support_message' => 'Αν χρειάζεστε υποστήριξη, ελέγξτε την ιστοσελίδα μας <a href="https://namelessmc.com" target="_blank">here</a>, ή μπορείτε επίσης να επισκεφθείτε τον δικό μας <a href="https://discord.gg/nameless" target="_blank">Discord server</a> η το δικό μας <a href="https://github.com/NamelessMC/Nameless/" target="_blank">GitHub repository</a>.',
    'credits' => 'Συντελεστές',
    'credits_message' => 'Ένα τεράστιο ευχαριστώ σε όλους <a href="https://github.com/NamelessMC/Nameless/graphs/contributors" target="_blank">NamelessMC contributors</a> από το 2014',

    'step_home' => 'Home',
    'step_requirements' => 'Requirements',
    'step_general_config' => 'General Configuration',
    'step_database_config' => 'Database Configuration',
    'step_site_config' => 'Site Configuration',
    'step_admin_account' => 'Admin Account',
    'step_conversion' => 'Conversion',
    'step_finish' => 'Finish',

    'general_configuration' => 'General Configuration',
    'reload' => 'Reload',
    'reload_page' => 'Reload page',
    'no_converters_available' => 'There are no converters available.',
    'config_not_writable' => 'The config file is not writable.',

    'session_doesnt_exist' => 'Unable to detect session. Sessions saving are a requirement to use Nameless. Please try again, and if the issue persists, please contact your web host for support.'
);
