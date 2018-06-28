/**
 * @license Modifica e usa come vuoi
 *
 * Creato da TurboLab.it - 01/01/2014 (buon anno!)
 */
CKEDITOR.plugins.add( 'tliyoutube2', {
    icons: 'tliyoutube2',
    lang: [ 'en', 'pt', 'ja', 'hu', 'it', 'fr', 'tr', 'ru', 'de', 'ar', 'nl', 'pl', 'vi', 'zh', 'el', 'he', 'es', 'nb', 'nn', 'fi', 'et', 'sk', 'cs'],
    init: function( editor ) {
        editor.addCommand( 'tliyoutube2Dialog', new CKEDITOR.dialogCommand( 'tliyoutube2Dialog' ) );
        editor.ui.addButton( 'tliyoutube2', {
				label : editor.lang.tliyoutube2.button,
            command: 'tliyoutube2Dialog',
            toolbar: 'insert'
        });

        CKEDITOR.dialog.add( 'tliyoutube2Dialog', this.path + 'dialogs/tliyoutube2.js' );
    }
});
