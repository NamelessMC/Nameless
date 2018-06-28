CKEDITOR.plugins.add( 'spoiler' , {
	lang: 'en,ru',
	icons: 'spoiler',
	init: function( editor ) {
		if ( editor.blockless )
			return;

		function registerCssFile( url ) {
			var head = editor.document.getHead();
			var link = editor.document.createElement( 'link' , {
				attributes: {
					type: 'text/css',
					rel: 'stylesheet',
					href: url
				}
			} );
			head.append( link );
		}

		function toggle( element ) {
			element.setStyle( 'display' , ( ( element.getStyle('display') == 'none' ) ? '' : 'none' ) );
		}

		function toggleClass( element, className ) {
			if ( element.hasClass( className ) ) {
				element.removeClass( className );
			}
			else {
				element.addClass( className );
			}
		}

		function setSwitcher( element )
		{
			toggleClass( element, 'hide-icon' );
			toggleClass( element, 'show-icon' );
			var content = element.getParent().getParent().getLast();
			toggle( content );
		}

		function createSpoiler() {
			var spoilerContainer = editor.document.createElement( 'div', { 'attributes' : { 'class': 'spoiler' } } );
			var spoilerToggle = editor.document.createElement( 'div', { 'attributes' : { 'class': 'spoiler-toggle hide-icon' } } );
			var spoilerTitle = editor.document.createElement( 'div', { 'attributes' : { 'class': 'spoiler-title' } } );
			var spoilerContent = editor.document.createElement( 'div', { 'attributes' : { 'class': 'spoiler-content' } } );
			spoilerToggle.on( 'click', function( event ) {
				setSwitcher( event.sender );
			});
			spoilerTitle.append( spoilerToggle );
			spoilerTitle.appendHtml( '<br>' );
			spoilerContent.appendHtml( '<p><br></p>' );
			spoilerContainer.append( spoilerTitle );
			spoilerContainer.append( spoilerContent );
			return spoilerContainer;
		}

		function getDivWithClass( className )
		{
			var divs =  editor.document.getElementsByTag( 'div' ),
				len = divs.count(),
				elements = [],
				element;
			for ( var i = 0; i < len; ++i ) {
				element = divs.getItem( i );
				if ( element.hasClass( className ) ) {
					elements.push( element );
				}
			}
			return elements;
		}

		editor.addCommand( 'spoiler', {
			exec: function( editor ) {
				var spoiler = createSpoiler();
				editor.insertElement( spoiler );
			},
			allowedContent: 'div{*}(*);br'
		});

		editor.ui.addButton( 'Spoiler', {
			label: editor.lang.spoiler.toolbar,
			command: 'spoiler',
			toolbar: 'insert'
		});

		var path = this.path;
		editor.on( 'mode', function() {
			if ( this.mode != 'wysiwyg' ) {
				return;
			}
			registerCssFile( path + 'css/spoiler.css' );
			var elements = getDivWithClass( 'spoiler-toggle' ),
				len = elements.length;
			for ( var i = 0; i < len; ++i )
			{
				elements[i].on( 'click', function( event ) {
					setSwitcher( event.sender );
				});
			}
		});
	}
});
