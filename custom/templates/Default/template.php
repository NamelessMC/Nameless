<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr1
 *
 *  License: MIT
 *
 *  Default template
 */
 
$template_version = 'v2.0.0-pr1'; // Version number of template
$nl_template_version = '2.0.0-pr1'; // Nameless version template is designed for

// Paths to CSS files
$css = array(
	(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/custom/templates/Default/css/bootstrap.min.css',
	(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/css/custom.css',
	(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/css/font-awesome.min.css',
	(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/custom/templates/Default/css/custom.css'
);

$js_sources = array(
	(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/custom/templates/Default/js/jquery.min.js',
	(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/custom/templates/Default/js/tether.min.js',
	(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/custom/templates/Default/js/bootstrap.min.js'
);

if(defined('PAGE') && PAGE == 'cc_messaging'){
	$js_sources[] = (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/custom/templates/Default/js/bootstrap3-typeahead.min.js';
}

// Page load time
$page_load = microtime(true) - $start;
if(isset($page_loading) && $page_loading == '1'){
	$js = '
	<script type="text/javascript">
	var timer = \'' . str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')) . '\';
	$(\'#page_load_tooltip\').attr(\'title\', timer).tooltip(\'fixTitle\');
	</script>';
} else $js = '';

// Popovers
$js.= '
 <script>
 $(".pop").popover({ trigger: "manual" , html: true, animation:false, placement: "top" })
	.on("mouseenter", function () {
		var _this = this;
		$(this).popover("show");
		$(".popover").on("mouseleave", function () {
			$(_this).popover(\'hide\');
		});
	}).on("mouseleave", function () {
		var _this = this;
		setTimeout(function () {
			if (!$(".popover:hover").length) {
				$(_this).popover(\'hide\');
			}
		}, 300);
 });
 </script>
';

// Registration page/login page checkbox
if(isset($page) && ($page == 'login' || $page = 'register')){
	$js .= '
	<script>
	$(function () {
		$(\'.button-checkbox\').each(function () {
			// Settings
			var $widget = $(this),
				$button = $widget.find(\'button\'),
				$checkbox = $widget.find(\'input:checkbox\'),
				color = $button.data(\'color\'),
				settings = {
					on: {
						icon: \'fa fa-check-square-o\'
					},
					off: {
						icon: \'fa fa-square-o\'
					}
				};
			// Event Handlers
			$button.on(\'click\', function () {
				$checkbox.prop(\'checked\', !$checkbox.is(\':checked\'));
				$checkbox.triggerHandler(\'change\');
				updateDisplay();
			});
			$checkbox.on(\'change\', function () {
				updateDisplay();
			});
			// Actions
			function updateDisplay() {
				var isChecked = $checkbox.is(\':checked\');
				// Set the button\'s state
				$button.data(\'state\', (isChecked) ? "on" : "off");
				// Set the button\'s icon
				$button.find(\'.state-icon\')
					.removeClass()
					.addClass(\'state-icon \' + settings[$button.data(\'state\')].icon);
				// Update the button\'s colour
				if (isChecked) {
					$button
						.removeClass(\'btn-default\')
						.addClass(\'btn-\' + color + \' active\');
				}
				else {
					$button
						.removeClass(\'btn-\' + color + \' active\')
						.addClass(\'btn-default\');
				}
			}
			// Initialisation
			function init() {
				updateDisplay();
				// Inject the icon if applicable
				if ($button.find(\'.state-icon\').length == 0) {
					$button.prepend(\'<i class="state-icon \' + settings[$button.data(\'state\')].icon + \'"></i>\');
				}
			}
			init();
		});
	});
	</script>
	';
}