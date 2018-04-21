CKEDITOR.dialog.add( 'emojioneDialog', function( editor ) {
	
	var emojioneHtmlContent = '';
	
	var iconArr = [];
	
	String.prototype.replaceAll = function(search, replacement) {
		var target = this;
		return target.replace(new RegExp(search, 'g'), replacement);
	};
	
	var dialog;
	var onClick = function( e ) {
		var target = e.data.getTarget(),
			targetName = target.getName();

		if ( targetName == 'a' )
			target = target.getChild( 0 );
		else if ( targetName != 'img' )
			return;

		var src = target.getAttribute( 'src' ),
			title = target.getAttribute( 'title' );

		var img = editor.document.createElement( 'img', {
			attributes: {
				src: src,
				title: title,
				alt: title,
				width: target.$.width,
				height: target.$.height
			}
		} );

		editor.insertElement( img );
		dialog.hide();
		e.data.preventDefault();
	};

	
	 for(let e in emojis){
		 if(emojis[e].category != "modifier" && emojis[e].category != "flags" && emojis[e].category != "extras" && emojis[e].category != "regional"){
		 	/*
			 if(emojis[e].shortname.indexOf('tone') != -1){
				 var tone = (emojis[e].shortname).substring(emojis[e].shortname.indexOf('tone'),emojis[e].shortname.indexOf('tone')+5);
				 if(iconArr[tone] === undefined){
					 iconArr[tone] = [];
				 }
				  iconArr[tone].push([emojis[e].shortname,emojis[e].unicode,e]);
			 }else{
			 */
				 if(iconArr[emojis[e].category] === undefined){
					iconArr[emojis[e].category] = [];
				 }
				 
				 iconArr[emojis[e].category].push([emojis[e].shortname,emojis[e].unicode,e]);
			 //}
			 
		 } 
	}
	var $content = [];
	Object.keys(iconArr).forEach(function(key,index) {
		emojioneHtmlContent = "";
		for(let i=0; i<iconArr[key].length; i++){
			emojioneHtmlContent += '<a href="javascript:void(0)"><img class="ck_emojione" style="width: 25px !important; height: 25px !important; padding: 3px; cursor: pointer;" alt="'+iconArr[key][i][0]+'" title="'+iconArr[key][i][2]+'" src="'+CKEDITOR.tools.htmlEncode('//cdn.jsdelivr.net/emojione/assets/png/'+iconArr[key][i][1]+'.png?v=2.2.6')+'"></a> '
		}
		$content.push({
			id: 'tab-'+key,
			label: key,
			elements: [
				{
					type: 'html',
					html: '<p style="word-wrap: break-word; white-space: pre-wrap;">'+emojioneHtmlContent+'</p>',
					onClick: onClick
				}                   
			]
		});
	});

    return {
        title: 'Emojis',
		resizable:      CKEDITOR.DIALOG_RESIZE_BOTH,
        minWidth: 800,
        height: 200,
        contents: $content,
		buttons: [ CKEDITOR.dialog.okButton ],
        onOk: function() {
            this.hide();
        }
    };
});