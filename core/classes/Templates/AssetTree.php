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
     * @var string Font Awesome v6.1 (CSS)
     */
    public const FONT_AWESOME = 'FONT_AWESOME';
    /**
     * @var string Bootstrap bundle v4.5 (CSS + JS)
     */
    public const BOOTSTRAP = 'BOOTSTRAP';
    /**
     * @var string Bootstrap Colorpicker v3.4 (CSS + JS)
     */
    public const BOOTSTRAP_COLORPICKER = 'BOOTSTRAP_COLORPICKER';
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
     * @var string Dropzone v5.9 (CSS + JS)
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
     * @var string Moment v2.29 (JS)
     */
    public const MOMENT = 'MOMENT';
    /**
     * @var string PrismJS v1.27 (CSS + JS, and the dark theme)
     */
    public const PRISM_DARK = 'PRISM_DARK';
    /**
     * @var string PrismJS v1.27 (CSS + JS, and the Default light theme)
     */
    public const PRISM_LIGHT = 'PRISM_LIGHT';
    /**
     * @var string Select2 v4.0 (CSS + JS)
     */
    public const SELECT2 = 'SELECT2';
    /**
     * @var string TinyMCE v5.10 (JS, and the light/dark theme of Prism, as well as the spoiler plugin)
     */
    public const TINYMCE = 'TINYMCE';
    /**
     * @var string TinyMCE Spoiler plugin. Used individually when posts will be shown but not created (home page for example)
     */
    public const TINYMCE_SPOILER = 'TINYMCE_SPOILER';
    /**
     * @var string Fomantic-UI bundle v2.8 (CSS + JS)
     */
    public const FOMANTIC_UI = 'FOMANTIC_UI';

    /**
     * @var mixed Tree of all available assets, with their applicable CSS/JS files.
     * In the case an asset depends on other assets within the tree, they are defined as "depends".
     */
    protected const ASSET_TREE = [
        self::FONT_AWESOME => [
            'css' => [
                'vendor/@fortawesome/fontawesome-free/css/all.min.css'
            ],
        ],
        self::BOOTSTRAP => [
            'css' => [
                'vendor/bootstrap/dist/css/bootstrap.min.css',
            ],
            'js' => [
                'vendor/bootstrap/dist/js/bootstrap.bundle.min.js',
            ],
            'after' => [
                self::JQUERY_UI,
            ]
        ],
        self::BOOTSTRAP_COLORPICKER => [
            'css' => [
                'vendor/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css',
            ],
            'js' => [
                'vendor/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js',
            ],
            'depends' => [
                self::BOOTSTRAP,
            ],
        ],
        self::CHART_JS => [
            'js' => [
                'vendor/chart.js/dist/Chart.min.js',
            ],
            'depends' => [
                self::JQUERY,
            ],
        ],
        self::CODEMIRROR => [
            'css' => [
                'vendor/codemirror/lib/codemirror.css',
            ],
            'js' => [
                'vendor/codemirror/lib/codemirror.js',
                'vendor/codemirror/mode/smarty/smarty.js',
                'vendor/codemirror/mode/javascript/javascript.js',
                'vendor/codemirror/mode/css/css.js',
                'vendor/codemirror/mode/htmlmixed/htmlmixed.js',
            ],
        ],
        self::DATATABLES => [
            'css' => [
                'vendor/datatables.net-bs4/css/dataTables.bootstrap4.css',
            ],
            'js' => [
                'vendor/datatables.net/js/jquery.dataTables.min.js',
                'vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js',
            ],
            'depends' => [
                self::JQUERY,
            ],
        ],
        self::DROPZONE => [
            'css' => [
                'vendor/dropzone/dist/min/dropzone.min.css',
            ],
            'js' => [
                'vendor/dropzone/dist/min/dropzone.min.js',
            ],
        ],
        self::IMAGE_PICKER => [
            'css' => [
                'vendor/image-picker/image-picker/image-picker.css',
            ],
            'js' => [
                'vendor/image-picker/image-picker/image-picker.min.js',
            ],
            'depends' => [
                self::JQUERY,
            ],
        ],
        self::JQUERY => [
            'js' => [
                'vendor/jquery/dist/jquery.min.js',
            ],
        ],
        self::JQUERY_UI => [
            'js' => [
                'vendor/jquery-ui-dist/jquery-ui.min.js',
            ],
            'depends' => [
                self::JQUERY,
            ],
        ],
        self::JQUERY_COOKIE => [
            'js' => [
                'vendor/jquery.cookie/jquery.cookie.js',
            ],
            'depends' => [
                self::JQUERY,
            ],
        ],
        self::MOMENT => [
            'js' => [
                'vendor/moment/min/moment.min.js',
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
        self::SELECT2 => [
            'js' => [
                'vendor/select2/dist/js/select2.min.js',
            ],
            'css' => [
                'vendor/select2/dist/css/select2.min.css',
            ],
            'depends' => [
                self::JQUERY,
            ],
        ],
        self::TINYMCE => [
            'js' => [
                'vendor/tinymce/tinymce.min.js',
            ],
            'depends' => [
                // Not included here: Prism light/dark, since we cannot
                // check dark_mode const here in PHP 8.0+,
                // it is added in AssetResolver instead.
                self::TINYMCE_SPOILER,
            ],
        ],
        self::TINYMCE_SPOILER => [
            'css' => [
                'plugins/tinymce_spoiler/css/spoiler.css',
            ],
            'js' => [
                'plugins/tinymce_spoiler/js/spoiler.js',
            ],
            'depends' => [
                self::JQUERY,
            ],
        ],
        self::FOMANTIC_UI => [
            'css' => [
                'vendor/fomantic-ui/dist/semantic.min.css',
            ],
            'js' => [
                'vendor/fomantic-ui/dist/semantic.min.js',
            ],
            'after' => [
                self::JQUERY,
            ]
        ],
    ];
}
