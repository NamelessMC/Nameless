<?php
/*
 *  Made by ArisC
 *  https://github.com/Ar1sC
 *  https://twitter.com/Ar1cC
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr12
 *
 *  License: MIT
 *
 *  Greek Language - Admin
 */

$language = [
    /*
     *  Admin Control Panel
     */
    // Login
    're-authenticate' => 'Παρακαλώ Πιστοποιήστε την αυθεντικότητα',

    // Sidebar
    'dashboard' => 'Dashboard',
    'configuration' => 'Configuration',
    'layout' => 'Layout',
    'user_management' => 'User Management',
    'overview' => 'Επισκόπηση',
    'core' => 'Πυρήνας',
    'integrations' => 'Integrations',
    'minecraft' => 'Minecraft',
    'modules' => 'Modules',
    'security' => 'Ασφάλεια',
    'styles' => 'Styles',
    'users_and_groups' => 'Χρήστες και Ομάδες',

    // Overview
    'running_nameless_version' => 'Τρέχουσα NamelessMC έκδοση {{version}}',
    'statistics' => 'Στατιστικά',

    // Core
    'settings' => 'Ρυθμίσης',
    'general_settings' => 'Γενικές Ρυθμίσεις',
    'sitename' => 'Ονομα ιστοσελίδας',
    'punished_id' => 'Punished User ID',
    'punisher_id' => 'Punisher User ID',
    'default_language' => 'Προεπιλεγμένη γλώσσα',
    'default_language_help' => 'Οι χρήστες θα έχουν τη δυνατότητα να επιλέξουν από τις εγκατεστημένες γλώσσες.',
    'install_language' => 'Install Language',
    'update_user_languages' => 'Update User Languages',
    'update_user_languages_warning' => 'This will update the language for all users on your site, even if they have already selected one!',
    'updated_user_languages' => 'User languages have been updated.',
    'installed_languages' => 'Κάθε νέα γλώσσα έχει εγκατασταθεί με επιτυχία.',
    'default_timezone' => 'Προεπιλεγμένη ζώνη ώρας',
    'registration' => 'Εγγραφή',
    'enable_registration' => 'Ενεργοποίηση εγγραφής?',
    'verify_with_mcassoc' => 'Verify user accounts με MCAssoc?',
    'email_verification' => 'Ενεργοποίηση επαλήθευσης ηλεκτρονικού ταχυδρομείου?',
    'registration_settings_updated' => 'Registration settings updated successfully.',
    'homepage_type' => 'Αρχική Σελίδα τύπος',
    'portal' => 'Πύλη',
    'missing_sitename' => 'Παρακαλώ εισάγετε ένα όνομα ιστοσελίδα μήκους μεταξύ 2 έως 64 χαρακτήρων.',
    'missing_contact_address' => 'Please insert a contact email address between 3 and 255 characters long.',
    'use_friendly_urls' => 'Φιλικό URLs',
    'use_friendly_urls_help' => 'ΣΗΜΑΝΤΙΚΟ: Ο διακομιστής σας πρέπει να ρυθμιστεί ώστε να επιτρεπεί την χρήση mod_rewrite και αρχεία .htaccess για να λειτουργήσει.',
    'successfully_updated' => 'Επιτυχής ενημέρωση',

    // Reactions
    'type' => 'Τύπος',
    'positive' => 'Θετικός',
    'neutral' => 'Ουδέτερος',
    'negative' => 'Αρνητικός',
    'editing_reaction' => 'Επεξεργασία Αντίδρασης',
    'html' => 'HTML',
    'creating_reaction' => 'Δημιουργία Αντίδρασης',

    // Custom profile fields
    'custom_fields' => 'Προσαρμοσμένα Πεδία προφίλ',
    'required' => 'Απαιτείται',
    'editable' => 'Editable',
    'public' => 'Δημόσιο',
    'forum_posts' => 'Display on Forum',
    'text' => 'Κείμενο',
    'textarea' => 'Περιοχή κειμένου',
    'date' => 'Ημερομηνία',
    'creating_profile_field' => 'Δημιουργία προφίλ Πεδίο',
    'editing_profile_field' => 'Επεξεργασία Προφίλ πεδίο',
    'field_name' => 'Ονομα πεδίου',
    'profile_field_required_help' => 'Τα υποχρεωτικά πεδία πρέπει να συμπληρωθούν από το χρήστη, και θα εμφανίζονται κατά την εγγραφή.',
    'profile_field_public_help' => 'Δημόσια πεδία θα εμφανίζονται σε όλους τους χρήστες, αν αυτό είναι απενεργοποιημένο μόνο οι διαμεσολαβητές να δουν τις τιμές.',
    'profile_field_error' => 'Εισάγετε ένα όνομα πεδίου μήκους μεταξύ 2 εώς 16 χαρακτήρες.',
    'description' => 'Description',
    'display_field_on_forum' => 'Θέλω να δείξω το πεδίο στο φόρουμ?',
    'profile_field_forum_help' => 'Αν είναι ενεργοποιημένο, το πεδίο θα εμφανιστεί από το χρήστη δίπλα στης δημοσιεύσεις.',

    // Modules
    'enabled' => 'Ενεργοποιήθηκε',
    'disabled' => 'Απενεργοποιήθηκε',
    'enable' => 'Ενεργοποιώ',
    'disable' => 'Απενεργοποιώ',
    'module_enabled' => 'Το τμήμα Ενεργοποιήθηκε.',
    'module_disabled' => 'Τμήμα Απενεργοποιήθηκε.',

    // Styles
    'active' => 'Ενεργός',
    'deactivate' => 'Απενεργοποίηση',
    'activate' => 'Ενεργοποιώ',
    'warning_editing_default_template' => 'Προειδοποίηση! Συνιστάται να μην επεξεργαστείτε το προεπιλεγμένο template.',
    'images' => 'Eικόνες',
    'upload_new_image' => 'Μεταφόρτωση Νέας Εικόνας',
    'reset_background' => 'Επαναφορά Background',
    'install' => 'Εγκαταστασή',
    'template_updated' => 'Template ενημερώθηκε με επιτυχία.',
    'default' => 'Προκαθορισμένο',
    'make_default' => 'Κάντε Προεπιλογή',
    'default_template_set' => 'Προεπιλεγμένο template οριστεί σε {{template}} επιτυχώς.',
    'template_deactivated' => 'Template απενεργοποιήθηκε.',
    'template_activated' => 'Template ενεργοποιήθηκε.',
    'permissions' => 'Άδειες',

    // Users & groups
    'users' => 'Χρήστες',
    'groups' => 'Ομάδες',
    'group' => 'Ομάδα',
    'new_user' => 'Νέος Χρήστης',
    'creating_new_user' => 'Δημιουργία νέου χρήστη',
    'registered' => 'Εγγεγραμμένος',
    'user_created' => 'Ο Χρήστης δημιουργήθηκε με επιτυχία.',
    'cant_delete_root_user' => 'Can\'t delete the root user!',
    'cant_modify_root_user' => 'Can\'t modify this user\'s main group!',
    'main_group' => 'Main Group',
    'user_deleted' => 'Ο Χρήστης διαγράφηκε με επιτυχία.',
    'confirm_user_deletion' => 'Είστε σίγουροι ότι θέλετε να διαγράψετε το χρήστη {{user}}?',
    'validate_user' => 'Επικύρωση Χρήστη',
    'update_uuid' => 'Ενήμερωση UUID',
    'update_mc_name' => 'Ενήμερωση Minecraft Username',
    'delete_user' => 'Διαγραφή χρήστη',
    'minecraft_uuid' => 'Minecraft UUID',
    'other_actions' => 'Αλλες ενέργειες',
    'disable_avatar' => 'Απενεργοποίηση Avatar',
    'select_user_group' => 'You must select a user\'s group.',
    'uuid_max_32' => 'The UUID must be a maximum of 32 characters.',
    'title_max_64' => 'The user title must be a maximum of 64 characters.',
    'group_id' => 'Group ID',
    'name' => 'Όνομα',
    'title' => 'Τίτλος Xρήστη',
    'new_group' => 'Νέα Ομάδα',
    'group_name_required' => 'Εισάγετε ένα όνομα ομάδας.',
    'group_name_minimum' => 'Βεβαιωθείτε ότι το όνομα της ομάδας σας είναι τουλάχιστον 2 χαρακτήρες.',
    'group_name_maximum' => 'Βεβαιωθείτε ότι το όνομα της ομάδας σας είναι το μέγιστο μήκος 20 χαρακτήρων.',

    // General Admin language
    'task_successful' => 'Task successful.',
    'invalid_action' => 'Μη έγκυρη ενέργεια.',
    'enable_night_mode' => 'Ενεργοποιήστε τη λειτουργία νύχτας',
    'disable_night_mode' => 'Απενεργοποιήστε τη λειτουργία νύχτας',
    'view_site' => 'Προβολή ιστότοπου',
    'warning' => 'Προειδοποίηση',

    // Maintenance
    'maintenance_enabled' => 'Λειτουργία συντήρησης είναι ενεργοποιημένη.',
    'enable_maintenance_mode' => 'Ενεργοποίηση λειτουργίας συντήρησης?',
    'maintenance_mode_message' => 'Μήνυμα λειτουργία συντήρησης',
    'maintenance_message_max_1024' => 'Βεβαιωθείτε οτι το μήνυμα συντήρηση σας είναι το μέγιστο 1024 χαρακτήρες.',

    // Security
    'please_select_logs' => 'Επιλέξτε τα αρχεία καταγραφής για να δείτε',
    'ip_address' => 'Διεύθυνση IP',
    'template_changes' => 'Template Αλλαγές',
    'file_changed' => 'Το αρχείο άλλαξε',

    // Updates
    'update' => 'Ενημέρωση',
    'current_version_x' => 'Τρέχουσα Έκδοση: {{version}}',
    'new_version_x' => 'Καινούργια Έκδοση: {{version}}',
    'new_update_available' => 'Υπάρχει μια νέα διαθέσιμη ενημέρωση',
    'new_urgent_update_available' => 'There is a new urgent update available. Please update as soon as possible!',
    'up_to_date' => 'Your   NamelessMC installation is up to date!',
    'urgent' => 'Αυτή η ενήμερωση είναι μια επείγουσα ενημέρωση',
    'instructions' => 'Οδηγίες',
    'download' => 'Λήψη',
    'install_confirm' => 'Παρακαλούμε βεβαιωθείτε ότι έχετε κατεβάσει το πακέτο και να έχετε ανεβάσει τα αρχεία πρώτα!',

    // File uploads
    'drag_files_here' => 'Σύρετε αρχεία εδώ για μεταφόρτωση.',
    'invalid_file_type' => 'Μη έγκυρος τύπος αρχείου!',
    'file_too_big' => 'Πολύ μεγάλο αρχείο! Το αρχείο σας ήταν {{filesize}} και το όριο είναι {{maxFilesize}}', // Don't replace {{filesize}} or {{maxFilesize}}
];
