<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr4
 *
 *  License: MIT
 *
 *  Default template
 */

$template_version = 'v2.0.0-pr4'; // Version number of template
$nl_template_version = '2.0.0-pr4'; // Nameless version template is designed for

$cache->setCache('default_template');
if($cache->isCached('bootswatch')){
	$theme = $cache->retrieve('bootswatch');
} else {
	$theme = 'bootstrap';
	$cache->store('bootswatch', 'bootstrap');
}

if($cache->isCached('nav_style')){
	$nav_style = $cache->retrieve('nav_style');
} else {
	$nav_style = 'light';
	$cache->store('nav_style', 'light');
}

if($cache->isCached('nav_bg')){
	$nav_bg = $cache->retrieve('nav_bg');
} else {
	$nav_bg = 'light';
	$cache->store('nav_style', 'light');
}

// Editor skin (moono-lisa or moono-dark)
define('TEMPLATE_EDITOR_STYLE', 'moono-lisa');

$smarty->assign('NAV_STYLE', Output::getClean($nav_style));
$smarty->assign('NAV_BG', Output::getClean($nav_bg));

if(isset($_GET['route']))
	$route = rtrim($_GET['route'], '/');
else
	$route = '/';

if(!isset($admin_styles)){
  // Paths to CSS files
  $css = array(
  	(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/custom/templates/Default/css/' . Output::getClean($theme) . '.min.css',
  	(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/css/custom.css',
  	(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/css/font-awesome.min.css',
  	(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/custom/templates/Default/css/custom.css'
  );

  $js_sources = array(
  	(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/custom/templates/Default/js/jquery.min.js',
  	(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/custom/templates/Default/js/popper.min.js',
  	(defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/custom/templates/Default/js/bootstrap.min.js'
  );

  $js = '';

  if($route == '/user/messaging'){
  	$css[] = (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/custom/templates/Default/css/bootstrap-tokenfield.min.css';
  	$css[] = (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/css/jquery-ui.min.css';
  	$js_sources[] = (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/custom/templates/Default/js/bootstrap-tokenfield.min.js';
  	$js_sources[] = (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/js/jquery-ui.min.js';
  	$js .= '
 	<script type="text/javascript">
 	$(\'#InputTo\').tokenfield({
 	  autocomplete: {
 	    source: allUsers,
 	    delay: 100
 	  },
 	  showAutocompleteOnFocus: true
 	});
 	</script>
  	';
  }

  // Page load time
  if(isset($page_loading) && $page_loading == '1'){
  	$page_load_js = '
  	<script type="text/javascript">
  	var timer = \'' . $language->get('general', 'page_loaded_in') . '\';
  	$(\'#page_load_tooltip\').attr(\'title\', timer).tooltip();
  	</script>';
  }

  // Popovers
  $js .= '
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

    <script type="text/javascript">
      $(function () {
      $(\'[data-toggle="tooltip"]\').tooltip()
      });
      $(function () {
      $(\'[rel="tooltip"]\').tooltip()
      });

      $(\'[data-toggle="popover"]\').popover({ trigger: "manual" , html: true, animation:false}).on("mouseenter", function () {
      var _this = this;
      $(this).popover("show");
      $(".popover").on("mouseleave", function () {
        $(_this).popover(\'hide\');
      });
      }).on("mouseleave", function () {
      var _this = this;
      setTimeout(function () {
        if (!$(".popover:hover").length) {
          $(_this).popover("hide");
        }
      }, 300);
      });
    </script>

    <script>
    function copyToClipboard(element) {
      var $temp = $("<input>")
      $("body").append($temp);
      $temp.val($(element).text()).select();
      document.execCommand("copy");
      $temp.remove();

      toastr.options.onclick = function () {};
      toastr.options.progressBar = true;
      toastr.options.closeButton = true;
      toastr.options.positionClass = \'toast-bottom-left\'
      toastr.success(\'Copied!\');
    }
    </script>';

  if($user->isLoggedIn()){
    $js .= '
    <script type="text/javascript">
    <!-- Alerts -->
    $(document).ready(function() {
      // Request permission for browser notifications
      if(Notification){
        if (Notification.permission !== "granted")
        Notification.requestPermission();
      }

      toastr.options.closeButton = true;
      toastr.options.positionClass = \'toast-bottom-left\';

      // Get alerts and messages, and then set them to refresh every 20 seconds
      $.getJSON(\'' . URL::build('/queries/pms') . '\', function(data) {
        var pm_dropdown = document.getElementById(\'pm_dropdown\');

        if(data.value > 0){
          $("#pms").html(\' <i class="fa fa-exclamation-circle custom-nav-exclaim"></i>\');

          if(pm_dropdown.innerHTML == \'' . $language->get('general', 'loading') . '\'){

            var new_pm_dropdown = \'\';

            for(i in data.pms){
              new_pm_dropdown += \'<a class="dropdown-item" href="' . URL::build('/user/messaging/', 'action=view&amp;message=') . '\' + data.pms[i].id + \'">\' + data.pms[i].title + \'</a>\';
            }

            pm_dropdown.innerHTML = new_pm_dropdown;
          }

        } else {
          pm_dropdown.innerHTML = \'<a class="dropdown-item">' . $language->get('user', 'no_messages') . '</a>\';
        }
      });
      $.getJSON(\'' . URL::build('/queries/alerts'). '\', function(data) {
        var alert_dropdown = document.getElementById(\'alert_dropdown\');

        if(data.value > 0){
          $("#alerts").html(\' <i class="fa fa-exclamation-circle custom-nav-exclaim"></i>\');

          if(alert_dropdown.innerHTML == \'' . $language->get('general', 'loading') . '\'){

            var new_alert_dropdown = \'\';

            for(i in data.alerts){
              new_alert_dropdown += \'<a class="dropdown-item" href="' . URL::build('/user/alerts/', 'view=') . '\' + data.alerts[i].id + \'">\' + data.alerts[i].content_short + \'</a>\';
            }

            alert_dropdown.innerHTML = new_alert_dropdown;
          }

        } else {
          alert_dropdown.innerHTML = \'<a class="dropdown-item">' . $language->get('user', 'no_alerts') . '</a>\';
        }
      });

      $.getJSON(\'' . URL::build('/queries/servers'). '\', function(data) {});

      window.setInterval(function(){
        $.getJSON(\'' . URL::build('/queries/pms') . '\', function(data) {
        if(data.value > 0 && $(\'#pms\').is(\':empty\')){
          $("#pms").html(\' <i class="fa fa-exclamation-circle custom-nav-exclaim"></i>\');
          toastr.options.onclick = function () {
            window.location.href = "' . URL::build('/user/messaging') . '";
          };

          if(data.value == 1){
            toastr.info(\'' . $language->get('user', '1_new_message') . '\');
          } else {
            var x_messages = \'' . $language->get('user', 'x_new_messages') . '\';
            toastr.info(x_messages.replace("{x}", data.value));
          }

          // Update navbar dropdown
          var pm_dropdown = document.getElementById(\'pm_dropdown\');

          $("#pms").html(\' <i class="fa fa-exclamation-circle custom-nav-exclaim"></i>\');

          var new_pm_dropdown = \'\';

          for(i in data.pms){
            new_pm_dropdown += \'<a class="dropdown-item" href="' . URL::build('/user/messaging/', 'action=view&amp;message=') . '\' + data.pms[i].id + \'">\' + data.pms[i].title + \'</a>\';
          }

          pm_dropdown.innerHTML = new_pm_dropdown;

          // Desktop notification
          if (Notification.permission !== "granted")
            Notification.requestPermission();
          else {
            if(data.value == 1){
              var notification = new Notification(\'' . SITE_NAME . '\', {
                body: \'' . $language->get('user', '1_new_message') . '\',
              });
            } else {
              var notification = new Notification(\'' . SITE_NAME . '\', {
                body: x_messages.replace("{x}", data.value),
              });
            }

            notification.onclick = function () {
              window.open("' . Output::getClean(rtrim(Util::getSelfURL(), '/')) . URL::build('/user/messaging') . '");
            };

          }
        }
        });
        $.getJSON(\'' . URL::build('/queries/alerts') . '\', function(data) {
        if(data.value > 0 && $(\'#alerts\').is(\':empty\')){
          $("#alerts").html(\' <i class="fa fa-exclamation-circle custom-nav-exclaim"></i>\');
          toastr.options.onclick = function () {
            window.location.href = "' . URL::build('/user/alerts') . '";
          };

          if(data.value == 1){
            toastr.info(\'' . $language->get('user', '1_new_alert') . '\');
          } else {
            var x_alerts = \'' . $language->get('user', 'x_new_alerts') . '\';
            toastr.info(x_alerts.replace("{x}", data.value));
          }

          // Update navbar dropdown
          var alert_dropdown = document.getElementById(\'alert_dropdown\');

          $("#alerts").html(\' <i class="fa fa-exclamation-circle custom-nav-exclaim"></i>\');

          var new_alert_dropdown = \'\';

          for(i in data.alerts){
            new_alert_dropdown += \'<a class="dropdown-item" href="' . URL::build('/user/alerts/', 'view=') . '\' + data.alerts[i].id + \'">\' + data.alerts[i].content_short + \'</a>\';
          }

          alert_dropdown.innerHTML = new_alert_dropdown;

          // Desktop notification
          if (Notification.permission !== "granted")
            Notification.requestPermission();
          else {
            if(data.value == 1){
              var notification = new Notification(\'' . SITE_NAME . '\', {
                body: \'' . $language->get('user', '1_new_alert') . '\',
              });
            } else {
              var notification = new Notification(\'' . SITE_NAME . '\', {
                body: x_alerts.replace("{x}", data.value),
              });
            }

            notification.onclick = function () {
              window.open("' . Output::getClean(rtrim(Util::getSelfURL(), '/')) . URL::build('/user/alerts') . '");
            };

          }
        }
        });
      }, 20000);
    });

      $(\'.alert-dropdown, .pm-dropdown\').hover(
          function() {
              $(this).find(\'.dropdown-menu\').stop(true, true).delay(25).fadeIn();
          },
          function() {
              $(this).find(\'.dropdown-menu\').stop(true, true).delay(25).fadeOut();
          }
      );

      $(\'.alert-dropdown-menu, .pm-dropdown-menu\').hover(
          function() {
              $(this).stop(true, true);
          },
          function() {
              $(this).stop(true, true).delay(25).fadeOut();
          }
      );
      
      // Warnings
      if($(\'div.show-punishment\').length){
        $(\'.show-punishment\').modal(\'show\');
      }

    </script>';
  } else {
      if(defined('COOKIE_NOTICE')){
          $js .= '<script type="text/javascript">
          toastr.options.timeOut = 0;
          toastr.options.extendedTimeOut = 0;
          toastr.options.closeButton = true;
          
          toastr.options.onclick = function() { $(\'.toast .toast-close-button\').focus(); }
          toastr.options.onHidden = function() { $.cookie(\'accept\', \'accepted\', { path: \'/\' }); }
          
          toastr.options.positionClass = \'toast-bottom-left\';
          
          toastr.info(\'' . $language->get('general', 'cookie_notice') . '\');
          
          </script>';
      }
  }

  // Registration page/login page checkbox
  if($route == '/login' || $route == '/register' || $route == '/complete_signup'){
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
}
