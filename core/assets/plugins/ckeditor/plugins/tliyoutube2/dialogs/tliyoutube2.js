/**
 * @license Modifica e usa come vuoi
 *
 * Creato da TurboLab.it - 01/01/2014 (buon anno!)
 */
CKEDITOR.dialog.add( 'tliyoutube2Dialog', function( editor ) {

    return {
					title : editor.lang.tliyoutube2.title,
        minWidth: 400,
        minHeight: 75,
        contents: [
            {
                id: 'tab-basic',
                label: 'Basic Settings',
                elements: [
                    {
                        type: 'text',
                        id: 'youtubeURL',
			label : editor.lang.tliyoutube2.txtUrl,
                    }
                ]
            }
        ],
        onOk: function() {
            var dialog = this;
			var url=dialog.getValueOf( 'tab-basic', 'youtubeURL').trim();
			var regExURL=/v=([^&$]+)/i;
			var id_video=url.match(regExURL);

			if(id_video==null || id_video=='' || id_video[0]=='' || id_video[1]=='')
				{
				alert( editor.lang.youtube.invalidUrl);
				return false;
				}

            var oTag = editor.document.createElement( 'iframe' );

            oTag.setAttribute( 'width', '560' );
			oTag.setAttribute( 'height', '315' );
			oTag.setAttribute( 'src', '//www.youtube.com/embed/' + id_video[1] + '?rel=0');
			oTag.setAttribute( 'frameborder', '0' );
			oTag.setAttribute( 'allowfullscreen', '1' );

            editor.insertElement( oTag );
        }
    };
});
