<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr5
 *
 *  License: MIT
 *
 *  Default template
 */

class Default_Panel_Template extends TemplateBase {
	// Private variable to store language + user
	private $_language, $_user, $_pages;

	// Constructor - set template name, version, Nameless version and author here
	public function __construct($cache, $smarty, $language, $user, $pages){
		$this->_language = $language;
		$this->_user = $user;
		$this->_pages = $pages;

		parent::__construct(
			'Default',  // Template name
			'2.0.0-pr5',  // Template version
			'2.0.0-pr5',  // Nameless version template is made for
			'<a href="https://namelessmc.com/">Samerton</a>'  // Author, you can use HTML here
		);

		$this->addCSSFiles(array(
			(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/custom/panel_templates/Default/assets/plugins/font-awesome/css/font-awesome.min.css' => array(),
			(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/custom/panel_templates/Default/assets/css/adminlte.min.css' => array(),
			'https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700' => array(),
			(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/custom/panel_templates/Default/assets/css/custom.css' => array(),
		));

		$this->addJSFiles(array(
			(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/custom/panel_templates/Default/assets/plugins/jquery/jquery.min.js' => array(),
			(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/custom/panel_templates/Default/assets/plugins/bootstrap/js/bootstrap.bundle.min.js' => array(),
			(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/custom/panel_templates/Default/assets/js/adminlte.min.js' => array()
		));

		$this->addJSScript('
			$(document).ready(function(){
				$(\'[data-toggle="tooltip"]\').tooltip();
			});
			$(document).ready(function(){
				$(\'[data-toggle="popover"]\').popover({trigger:\'hover\',html:true});
			});
		');
	}

	public function onPageLoad(){
		if(defined('PANEL_PAGE')){
			switch(PANEL_PAGE){
				case 'dashboard':
					$this->addJSFiles(array(
						(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/moment/moment.min.js' => array(),
						(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/charts/Chart.min.js' => array()
					));
					break;

				case 'api':
					$this->addCSSFiles(array(
						(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/switchery/switchery.min.css' => array(),
						(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/toastr/toastr.min.css' => array()
					));

					$this->addJSFiles(array(
						(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/switchery/switchery.min.js' => array(),
						(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/toastr/toastr.min.js' => array()
					));

					$this->addJSScript('
					var elems = Array.prototype.slice.call(document.querySelectorAll(\'.js-switch\'));

					elems.forEach(function(html) {
					  var switchery = new Switchery(html, {color: \'#23923d\', secondaryColor: \'#e56464\'});
					});
					');

					break;

				case 'avatars':
					$this->addCSSFiles(array(
						(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/switchery/switchery.min.css' => array(),
						(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/dropzone/dropzone.min.css' => array(),
						(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/image-picker/image-picker.css' => array()
					));

					$this->addJSFiles(array(
						(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/switchery/switchery.min.js' => array(),
						(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/dropzone/dropzone.min.js' => array(),
						(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/image-picker/image-picker.min.js' => array(),
					));

					$this->addJSScript('
					var elems = Array.prototype.slice.call(document.querySelectorAll(\'.js-switch\'));

					elems.forEach(function(html) {
					  var switchery = new Switchery(html, {color: \'#23923d\', secondaryColor: \'#e56464\'});
					});
					
					// Dropzone options
					Dropzone.options.upload_avatar_dropzone = {
					    maxFilesize: 2,
					    dictDefaultMessage: "' . $this->_language->get('admin', 'drag_files_here') . '",
					    dictInvalidFileType: "' . $this->_language->get('admin', 'invalid_file_type') . '",
					    dictFileTooBig: "' . $this->_language->get('admin', 'file_too_big') . '"
					};
			
					$(".image-picker").imagepicker();
					');

					break;

				case 'custom_profile_fields':
				case 'emails':
					$this->addCSSFiles(array(
						(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/switchery/switchery.min.css' => array()
					));

					$this->addJSFiles(array(
						(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/switchery/switchery.min.js' => array()
					));

					$this->addJSScript('
					var elems = Array.prototype.slice.call(document.querySelectorAll(\'.js-switch\'));

					elems.forEach(function(html) {
					  var switchery = new Switchery(html, {color: \'#23923d\', secondaryColor: \'#e56464\'});
					});
					');

					break;

				case 'debugging_and_maintenance':
					$this->addCSSFiles(array(
						(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/switchery/switchery.min.css' => array()
					));

					$this->addCSSStyle('
					.error_log {
                        width: 100%;
                        height: 400px;
                        padding: 0 10px;
                        -webkit-box-sizing: border-box;
                        -moz-box-sizing: border-box;
                        box-sizing: border-box;
                        overflow-y: scroll;
                        overflow-x: scroll;
                        white-space: initial;
                        background-color: #eceeef;
                    }
					');

					$this->addJSFiles(array(
						(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/switchery/switchery.min.js' => array(),
						(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/emoji/js/emojione.min.js' => array(),
						(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/ckeditor/plugins/spoiler/js/spoiler.js' => array(),
						(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/ckeditor/ckeditor.js' => array(),
						(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/ckeditor/plugins/emojione/dialogs/emojione.json' => array()
					));

					$this->addJSScript(Input::createEditor('InputMaintenanceMessage'));
					$this->addJSScript('
					var elems = Array.prototype.slice.call(document.querySelectorAll(\'.js-switch\'));

					elems.forEach(function(html) {
					  var switchery = new Switchery(html, {color: \'#23923d\', secondaryColor: \'#e56464\'});
					});
					');

					break;
			}
		}
	}
}

$template = new Default_Panel_Template($cache, $smarty, $language, $user, $pages);