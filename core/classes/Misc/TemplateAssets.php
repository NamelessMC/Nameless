<?php

class TemplateAssets {

    public const FONT_AWESOME = 'FONT_AWESOME';
    public const BOOTSTRAP = 'BOOTSTRAP';
    public const BOOTSTRAP_COLORPICKER = 'BOOTSTRAP_COLORPICKER';
    public const BOOTSTRAP_DATEPICKER = 'BOOTSTRAP_DATEPICKER';
    public const CHART_JS = 'CHART_JS';
    public const CODEMIRROR = 'CODEMIRROR';
    public const DATATABLES = 'DATATABLES';
    public const DROPZONE = 'DROPZONE';
    public const IMAGE_PICKER = 'IMAGE_PICKER';
    public const JQUERY = 'JQUERY';
    public const JQUERY_UI = 'JQUERY_UI';
    public const JQUERY_COOKIE = 'JQUERY_COOKIE';
    public const MOMENT = 'MOMENT';
    public const PRISM_DARK = 'PRISM_DARK';
    public const PRISM_LIGHT = 'PRISM_LIGHT';
    public const TINYMCE = 'TINYMCE';
    public const TINYMCE_SPOILER = 'TINYMCE_SPOILER';
    public const TOASTR = 'TOASTR';

    private static array $_ASSET_TREE = [
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
                'plugins/image-picker/image-picker.js',
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
                    ? self::PRISM_LIGHT
                    : self::PRISM_DARK,
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

    private array $_assets = [];

    /**
     * @param string|array $assets
     */
    public function resolve($assets): void {
        if (!is_array($assets)) {
            $assets = [$assets];
        }

        foreach ($assets as $asset) {
            if (!defined(self::class . '::' . $asset)) {
                throw new InvalidArgumentException('Asset "' . $asset . '" is not defined');
            }

            if (array_key_exists($asset, $this->_assets)) {
                return;
            }

            $this->_assets[$asset] = self::$_ASSET_TREE[$asset];
        }
    }

    public function compile(): array {
        $css = [];
        $js = [];

        foreach ($this->_assets as $asset) {
            foreach ($asset['css'] as $cssFile) {
                $css[] = $this->buildPath($cssFile, 'css');
            }

            foreach ($asset['js'] as $jsFile) {
                $js[] = $this->buildPath($jsFile, 'js');
            }

            if (array_key_exists('depends', $asset)) {
                $this->resolve($asset['depends']);
            }
        }

        return [$css, $js];
    }

    private function buildPath(string $file, string $type): string {
        $href = (defined('CONFIG_PATH')
                ? CONFIG_PATH
                : '')
            . '/core/assets/' . $file;

        if ($type === 'css') {
            return '<link rel="stylesheet" href="' . $href . '">';
        }

        if ($type === 'js') {
            return '<script type="text/javascript" src="' . $href . '"></script>';
        }

        throw new RuntimeException('Unknown asset type: ' . $type);
    }
}
