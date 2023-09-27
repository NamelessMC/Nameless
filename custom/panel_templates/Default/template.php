<?php
/*
 *  Made by Coldfire
 *  https://coldfiredzn.com
 *
 *  For NamelessMC
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.1.2
 *
 *  License: MIT
 *
 *  Default template
 */

// Always have the following if statement around your class
if (!class_exists('Default_Panel_Template')) {
    class Default_Panel_Template extends TemplateBase {

        private Language $_language;

        // Constructor - set template name, version, Nameless version and author here
        public function __construct(Smarty $smarty, Language $language) {
            $this->_language = $language;

            parent::__construct(
                'Default',  // Template name
                '2.1.2',  // Template version
                '2.1.2',  // Nameless version template is made for
                '<a href="https://coldfiredzn.com" target="_blank">Coldfire</a>'  // Author, you can use HTML here
            );

            $this->assets()->include([
                AssetTree::FONT_AWESOME,
                AssetTree::JQUERY,
                AssetTree::JQUERY_COOKIE,
                AssetTree::BOOTSTRAP,
                AssetTree::SELECT2,
            ]);

            $this->addCSSFiles([
                (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/custom/panel_templates/Default/assets/css/sb-admin-2.min.css' => [],
                'https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i' => [],
                (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/custom/panel_templates/Default/assets/css/custom.css?v=211' => [],
            ]);

            $this->addJSFiles([
                (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/custom/panel_templates/Default/assets/js/sb-admin-2.js' => [],
            ]);

            $this->addJSScript('
                // Dark and light theme switch
                var currentPanelTheme = $.cookie("nmc_panel_theme");

                if (currentPanelTheme == null) {
                    $.cookie("nmc_panel_theme", "light", { path: "/" });
                } else {
                    if (currentPanelTheme == "dark") {
                        $("html").addClass("dark");
                        if ($("#dark_mode").length) {
                            $("#dark_mode").prop("checked", true);
                        }
                    }
                }

                // Prevents light flicker on dark mode
                $("body").addClass("visible");

                if ($("#dark_mode").length) {
                    var changeCheckbox = document.querySelector("#dark_mode");
                    changeCheckbox.onchange = function() {
                        if (currentPanelTheme == "dark") {
                            $.cookie("nmc_panel_theme", "light", { path: "/" });
                        };
                        if (currentPanelTheme == "light") {
                            $.cookie("nmc_panel_theme", "dark", { path: "/" });
                        };
                        location.reload();
                        return false;
                    };
                }

                // Sidebar Fixes
                if ($(".sidebar").length) {
                    $(".nav-icon").addClass("fa-fw");
                    $(".nav-icon").removeClass("nav-icon");

                    let sidebarState = sessionStorage.getItem("sidebar");
                    $(".sidebar").toggleClass(sidebarState);

                    $("#sidebarToggle, #sidebarToggleTop").on("click", function(e) {
                          $("body").toggleClass("sidebar-toggled");
                          $(".sidebar").toggleClass("toggled");
                          if ($(".sidebar").hasClass("toggled")) {
                            sessionStorage.setItem("sidebar", "toggled");
                            $(".sidebar .collapse").collapse("hide");
                          } else {
                            sessionStorage.setItem("sidebar", "");
                          };
                    });

                    if ($(window).width() < 768) {
                        $(".sidebar").addClass("toggled")
                    }
                }

                // Some popover stuff
                $(document).ready(function(){
                    $(\'[data-toggle="tooltip"]\').tooltip();
                });
                $(document).ready(function(){
                    $(\'[data-toggle="popover"]\').popover({trigger:\'manual\',html:true}).on("mouseenter", function() {
                      var _this = this;
                      $(this).popover("show");
                      $(".popover").on("mouseleave", function() {
                        $(_this).popover(\'hide\');
                      });
                    }).on("mouseleave", function() {
                      var _this = this;
                      setTimeout(function() {
                        if (!$(".popover:hover").length) {
                          $(_this).popover("hide")
                        }
                      }, 250);
                    });
                });

                // Fix settings dropdown
                if ($(".settings-dropdown").length) {
                    $(".settings-dropdown .dropdown-menu .dropdown-item select").click(function(e) {
                        e.stopPropagation();
                    });
                }

            ');

            $smarty->assign('NAMELESS_LOGO', (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/img/namelessmc_logo.png');
        }

        public function onPageLoad() {
            $page_load = microtime(true) - PAGE_START_TIME;
            define('PAGE_LOAD_TIME', $this->_language->get('general', 'page_loaded_in', ['time' => round($page_load, 3)]));

            if (defined('PANEL_PAGE')) {
                switch (PANEL_PAGE) {
                    case 'dashboard':
                        $this->assets()->include([
                            AssetTree::MOMENT,
                            AssetTree::CHART_JS,
                        ]);

                        break;

                    case 'api':
                        $this->assets()->include([
                            AssetTree::DATATABLES
                        ]);

                        $this->addJSScript('
                            $(document).ready(function() {
                                var apiEndpointsTable = $(\'.dataTables-endpoints\').DataTable({
                                    pageLength: 50,
                                    language: {
                                        "lengthMenu": "' . $this->_language->get('table', 'display_records_per_page') . '",
                                        "zeroRecords": "' . $this->_language->get('table', 'nothing_found') . '",
                                        "info": "' . $this->_language->get('table', 'page_x_of_y') . '",
                                        "infoEmpty": "' . $this->_language->get('table', 'no_records') . '",
                                        "infoFiltered": "' . $this->_language->get('table', 'filtered') . '",
                                        "search": "' . $this->_language->get('general', 'search') . '",
                                        "paginate": {
                                            "next": "' . $this->_language->get('general', 'next') . '",
                                            "previous": "' . $this->_language->get('general', 'previous') . '"
                                        }
                                    }
                                });
                            });
                        ');

                        break;

                    case 'avatars':
                        $this->assets()->include([
                            AssetTree::DROPZONE,
                            AssetTree::IMAGE_PICKER,
                        ]);

                        $this->addJSScript('
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

                    case 'debugging_and_maintenance':
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

                        $this->assets()->include([
                            AssetTree::TINYMCE,
                        ]);

                        $this->addJSScript(Input::createTinyEditor($this->_language, 'InputMaintenanceMessage', null, false, true));
                        break;

                    case 'privacy_and_terms':
                    case 'cookie_settings':
                        $this->assets()->include([
                            AssetTree::TINYMCE,
                        ]);

                        if (PANEL_PAGE === 'cookie_settings') {
                            $this->addJSScript(Input::createTinyEditor($this->_language, 'InputCookies', null, false, true));
                        } else {
                            $this->addJSScript(Input::createTinyEditor($this->_language, 'InputPrivacy', null, false, true));
                            $this->addJSScript(Input::createTinyEditor($this->_language, 'InputTerms', null, false, true));
                        }
                        break;

                    case 'registration':
                        $this->assets()->include([
                            AssetTree::TINYMCE,
                        ]);

                        $this->addJSScript(Input::createTinyEditor($this->_language, 'InputRegistrationDisabledMessage', null, false, true));

                        $this->addJSScript('
                        /*
                         *  Submit form on clicking enable/disable registration
                         */
                        var changeCheckbox = document.querySelector(\'.js-check-change\');

                        changeCheckbox.onchange = function() {
                          $(\'#enableRegistration\').submit();
                        };
                        ');

                        break;

                    case 'announcements':
                    case 'groups':
                        $this->assets()->include([
                            AssetTree::BOOTSTRAP_COLORPICKER,
                            AssetTree::JQUERY_UI,
                        ]);

                        break;

                    case 'template':
                        if (isset($_GET['file'])) {
                            $this->assets()->include([
                                AssetTree::CODEMIRROR,
                            ]);
                        }

                        break;

                    case 'seo':
                        $this->assets()->include([
                            AssetTree::DATATABLES,
                        ]);

                        $this->addJSScript('
                        $(document).ready(function() {
                            $(\'.dataTables-pages\').dataTable({
                                responsive: true,
                                language: {
                                    "lengthMenu": "' . $this->_language->get('table', 'display_records_per_page') . '",
                                    "zeroRecords": "' . $this->_language->get('table', 'nothing_found') . '",
                                    "info": "' . $this->_language->get('table', 'page_x_of_y') . '",
                                    "infoEmpty": "' . $this->_language->get('table', 'no_records') . '",
                                    "infoFiltered": "' . $this->_language->get('table', 'filtered') . '",
                                    "search": "' . $this->_language->get('general', 'search') . '",
                                    "paginate": {
                                        "next": "' . $this->_language->get('general', 'next') . '",
                                        "previous": "' . $this->_language->get('general', 'previous') . '"
                                    }
                                }
                            });
                        });
                        ');
                        break;

                    case 'users':
                        if (!defined('EDITING_USER')) {
                            $this->assets()->include([
                                AssetTree::DATATABLES,
                            ]);

                            $this->addJSScript('
                            $(document).ready(function() {
                                var usersTable = $(\'.dataTables-users\').DataTable({
                                    columnDefs: [
                                        { targets: [0], sClass: "hide" },
                                        { "width": "50%", target: 1 },
                                        { "width": "25%", target: 2 },
                                        { "width": "25%", target: 3 }
                                    ],
                                    responsive: true,
                                    processing: true,
                                    serverSide: true,
                                    ajax: "' . URL::build('/queries/admin_users') . '",
                                    columns: [
                                        { data: "id", hidden: true },
                                        { data: "username" },
                                        { data: "groupName", "orderable": false },
                                        { data: "joined" }
                                    ],
                                    language: {
                                        "lengthMenu": "' . $this->_language->get('table', 'display_records_per_page') . '",
                                        "zeroRecords": "' . $this->_language->get('table', 'nothing_found') . '",
                                        "info": "' . $this->_language->get('table', 'page_x_of_y') . '",
                                        "infoEmpty": "' . $this->_language->get('table', 'no_records') . '",
                                        "infoFiltered": "' . $this->_language->get('table', 'filtered') . '",
                                        "search": "' . $this->_language->get('general', 'search') . '",
                                        "paginate": {
                                            "next": "' . $this->_language->get('general', 'next') . '",
                                            "previous": "' . $this->_language->get('general', 'previous') . '"
                                        }
                                    }
                                });

                                $(\'.dataTables-users tbody\').on(\'click\', \'tr\', function(){
                                    window.location.href = "' . URL::build('/panel/user/') . '" + usersTable.row(this).data().id;
                                });
                            });
                            ');

                        }

                        break;

                    case 'minecraft':
                        if (!defined('MINECRAFT_PAGE')) {

                            $this->addJSScript('
                            if ($(\'.js-check-change\').length) {
                                var changeCheckbox = document.querySelector(\'.js-check-change\');

                                changeCheckbox.onchange = function () {
                                    $(\'#enableMinecraft\').submit();
                                };
                            }
                            ');

                            $this->addJSScript('
                            if ($(\'.js-check-change-verification\').length) {
                                var changeCheckbox = document.querySelector(\'.js-check-change-verification\');

                                changeCheckbox.onchange = function () {
                                    $(\'#enablePremium\').submit();
                                };
                            }
                            ');

                        } else if (MINECRAFT_PAGE == 'authme') {

                            $this->addJSScript('
                            if ($(\'.js-check-change\').length) {
                                var changeCheckbox = document.querySelector(\'.js-check-change\');

                                changeCheckbox.onchange = function () {
                                    $(\'#enableAuthMe\').submit();
                                };
                            }
                            ');

                        } else if (MINECRAFT_PAGE == 'servers') {
                            $this->assets()->include([
                                AssetTree::JQUERY_UI,
                            ]);
                        } else if (MINECRAFT_PAGE == 'query_errors') {
                            $this->addCSSStyle('
                            .error_log {
                                width: 100%;
                                height: 50px;
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

                        } else if (MINECRAFT_PAGE == 'server_banners') {
                            if (isset($_GET['edit'])) {
                                $this->assets()->include([
                                    AssetTree::IMAGE_PICKER,
                                ]);

                                $this->addCSSStyle('
                                .thumbnails li img{
                                    width: 200px;
                                }
                                ');

                                $this->addJSScript('$(".image-picker").imagepicker();');
                            }
                        }

                        break;

                    case 'security':
                        if (isset($_GET['view'])) {
                            $this->assets()->include([
                                AssetTree::DATATABLES,
                            ]);

                            $this->addJSScript('
                            $(document).ready(function() {
                                $(\'.dataTable\').dataTable({
                                    responsive: true,
                                    language: {
                                        "lengthMenu": "' . $this->_language->get('table', 'display_records_per_page') . '",
                                        "zeroRecords": "' . $this->_language->get('table', 'nothing_found') . '",
                                        "info": "' . $this->_language->get('table', 'page_x_of_y') . '",
                                        "infoEmpty": "' . $this->_language->get('table', 'no_records') . '",
                                        "infoFiltered": "' . $this->_language->get('table', 'filtered') . '",
                                        "search": "' . $this->_language->get('general', 'search') . '",
                                        "paginate": {
                                            "next": "' . $this->_language->get('general', 'next') . '",
                                            "previous": "' . $this->_language->get('general', 'previous') . '"
                                        }
                                    },
                                    order: [[ ' . SORT . ', \'desc\']]
                                });
                            });
                            ');
                        }
                        break;

                    case 'images':
                        $this->assets()->include([
                            AssetTree::DROPZONE,
                            AssetTree::IMAGE_PICKER,
                        ]);

                        $this->addJSScript('
                        // Dropzone options
                        Dropzone.options.upload_background_dropzone = {
                            maxFilesize: 2,
                            dictDefaultMessage: "' . $this->_language->get('admin', 'drag_files_here') . '",
                            dictInvalidFileType: "' . $this->_language->get('admin', 'invalid_file_type') . '",
                            dictFileTooBig: "' . $this->_language->get('admin', 'file_too_big') . '",
                            error: function(file, response) {
                                console.log("ERROR");
                                console.log(file);
                                console.log(response);
                            },
                            success: function(file, response){
                                console.log("ACCEPTED");
                                console.log(file);
                                console.log(response);
                            }
                        };

                        Dropzone.options.upload_banner_dropzone = {
                            maxFilesize: 2,
                            dictDefaultMessage: "' . $this->_language->get('admin', 'drag_files_here') . '",
                            dictInvalidFileType: "' . $this->_language->get('admin', 'invalid_file_type') . '",
                            dictFileTooBig: "' . $this->_language->get('admin', 'file_too_big') . '",
                            error: function(file, response) {
                                console.log("ERROR");
                                console.log(file);
                                console.log(response);
                            },
                            success: function(file, response){
                                console.log("ACCEPTED");
                                console.log(file);
                                console.log(response);
                            }
                        };

                        $(".image-picker").imagepicker();
                        ');
                        break;

                    case 'forums':
                        $this->assets()->include([
                            AssetTree::TINYMCE,
                            AssetTree::JQUERY_UI
                        ]);

                        if (isset($_GET['forum'])) {
                            $this->addJSScript(Input::createTinyEditor($this->_language, 'InputPlaceholder', null, false, true));
                        }
                        break;

                    case 'member_lists':
                        $this->addJSScript('
                            if ($(\'.js-check-change\').length) {
                                var changeCheckbox = document.querySelector(\'.js-check-change\');

                                changeCheckbox.onchange = function () {
                                    $(\'#toggleHideBannedUsers\').submit();
                                };
                            }
                            ');
                }
            }
        }
    }
}

$template = new Default_Panel_Template($smarty, $language);
