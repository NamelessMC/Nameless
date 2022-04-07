<?php

/**
 * Template asset tree.
 * In different class to keep the TemplateAssets class clean.
 *
 * @package NamelessMC\Templates
 * @see AssetResolver
 * @author Aberdeener
 * @version 2.0.0-pr13
 * @license MIT
 */
class AssetTree {

    /**
     * @var string Font Awesome 6.1 (CSS)
     */
    public const FONT_AWESOME = 'FONT_AWESOME';
    /**
     * @var string Bootstrap v4.5 (CSS + JS)
     */
    public const BOOTSTRAP = 'BOOTSTRAP';
    /**
     * @var string Bootstrap Colorpicker v3.0 (CSS + JS)
     */
    public const BOOTSTRAP_COLORPICKER = 'BOOTSTRAP_COLORPICKER';
    /**
     * @var string Bootstrap Datepicker v1.7 (CSS + JS)
     */
    public const BOOTSTRAP_DATEPICKER = 'BOOTSTRAP_DATEPICKER';
    /**
     * @var string Chart.js v2.7 (JS)
     */
    public const CHART_JS = 'CHART_JS';
    /**
     * @var string Codemirror (CSS + JS, as well as modes for Smarty, CSS, HTML & JS)
     */
    public const CODEMIRROR = 'CODEMIRROR';
    /**
     * @var string DataTables JQuery v1.10 (CSS + JS)
     */
    public const DATATABLES = 'DATATABLES';
    /**
     * @var string Dropzone (CSS + JS)
     */
    public const DROPZONE = 'DROPZONE';
    /**
     * @var string Image-Picker v0.2 (CSS + JS)
     */
    public const IMAGE_PICKER = 'IMAGE_PICKER';
    /**
     * @var string JQuery v3.5 (JS)
     */
    public const JQUERY = 'JQUERY';
    /**
     * @var string JQuery-UI v2.12 (JS)
     */
    public const JQUERY_UI = 'JQUERY_UI';
    /**
     * @var string JQuery-Cookie v1.4 (JS)
     */
    public const JQUERY_COOKIE = 'JQUERY_COOKIE';
    /**
     * @var string MCAssoc-Client (JS
     */
    public const MCASSOC_CLIENT = 'MCASSOC_CLIENT';
    /**
     * @var string Moment (JS)
     */
    public const MOMENT = 'MOMENT';
    /**
     * @var string PrismJS v1.27 (CSS + JS, and the dark theme)
     */
    public const PRISM_DARK = 'PRISM_DARK';
    /**
     * @var string PrismJS v1.27 (CSS + JS, and the Coy light theme)
     */
    public const PRISM_LIGHT = 'PRISM_LIGHT';
    /**
     * @var string TinyMCE v5.10 (JS, and the light/dark theme, as well as the spoiler plugin)
     */
    public const TINYMCE = 'TINYMCE';
    /**
     * @var string TinyMCE Spoiler plugin. Used individually when posts will be shown but not created (home page for example)
     */
    public const TINYMCE_SPOILER = 'TINYMCE_SPOILER';
    /**
     * @var string Toastr (CSS + JS)
     */
    public const TOASTR = 'TOASTR';

    /**
     * @var mixed Tree of all available assets, with their applicable CSS/JS files.
     * In the case an asset depends on other assets within the tree, they are defined as "depends".
     */
    protected const ASSET_TREE = [
        self::FONT_AWESOME => [
            'css' => [
                'css/font-awesome.min.css',
            ],
        ],
        self::BOOTSTRAP => [
            'css' => [
                'css/bootstrap.min.css',
            ],
            'js' => [
                'js/bootstrap.min.js',
            ],
        ],
        self::BOOTSTRAP_COLORPICKER => [
            'css' => [
                'plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css',
            ],
            'js' => [
                'plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js',
            ],
        ],
        self::BOOTSTRAP_DATEPICKER => [
            'css' => [
                'plugins/bootstrap-datepicker/css/bootstrap-datepicker3.standalone.min.css',
            ],
            'js' => [
                'plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js',
            ],
        ],
        self::CHART_JS => [
            'js' => [
                'plugins/Charts/Chart.min.js',
            ],
        ],
        self::CODEMIRROR => [
            'css' => [
                'plugins/codemirror/lib/codemirror.css',
            ],
            'js' => [
                'plugins/codemirror/lib/codemirror.js',
                'plugins/codemirror/mode/smarty/smarty.js',
                'plugins/codemirror/mode/javascript/javascript.js',
                'plugins/codemirror/mode/css/css.js',
                'plugins/codemirror/mode/htmlmixed/htmlmixed.js',
            ],
        ],
        self::DATATABLES => [
            'css' => [
                'plugins/DataTables/dataTables.min.css',
            ],
            'js' => [
                'plugins/DataTables/jquery.dataTables.min.js',
            ],
        ],
        self::DROPZONE => [
            'css' => [
                'plugins/dropzone/dropzone.min.css',
            ],
            'js' => [
                'plugins/dropzone/dropzone.min.js',
            ],
        ],
        self::IMAGE_PICKER => [
            'css' => [
                'plugins/image-picker/image-picker.css',
            ],
            'js' => [
                'plugins/image-picker/image-picker.min.js',
            ],
        ],
        self::JQUERY => [
            'js' => [
                'js/jquery.min.js',
            ],
        ],
        self::JQUERY_UI => [
            'js' => [
                'js/jquery-ui.min.js',
            ],
        ],
        self::JQUERY_COOKIE => [
            'js' => [
                'js/jquery.cookie.js',
            ],
        ],
        self::MCASSOC_CLIENT => [
            'js' => [
                'js/mcassoc-client.js',
            ],
        ],
        self::MOMENT => [
            'js' => [
                'plugins/moment/moment.min.js',
            ],
        ],
        self::PRISM_DARK => [
            'js' => [
                'plugins/prism/prism.js',
            ],
            'css' => [
                'plugins/prism/prism_dark.css',
            ],
        ],
        self::PRISM_LIGHT => [
            'js' => [
                'plugins/prism/prism.js',
            ],
            'css' => [
                'plugins/prism/prism_light_default.css',
            ],
        ],
        self::TINYMCE => [
            'js' => [
                'plugins/tinymce/tinymce.min.js',
            ],
            'depends' => [
                DARK_MODE
                    ? self::PRISM_DARK
                    : self::PRISM_LIGHT,
                self::TINYMCE_SPOILER,
            ],
        ],
        self::TINYMCE_SPOILER => [
            'css' => [
                'plugins/tinymce/plugins/spoiler/css/spoiler.css',
            ],
            'js' => [
                'plugins/tinymce/plugins/spoiler/js/spoiler.js',
            ],
        ],
        self::TOASTR => [
            'css' => [
                'plugins/toastr/toastr.min.css',
            ],
            'js' => [
                'plugins/toastr/toastr.min.js',
            ],
        ],
    ];
}
