const fse = require('fs-extra');

console.log('Copying assets to core/assets/vendor...')

// Define assets to be copied here
const publicAssets = {
  '@fortawesome': [
    'fontawesome-free/css/all.min.css',
    'fontawesome-free/webfonts'
  ],
  'bootstrap': [
    'dist/css/bootstrap.min.css',
    'dist/css/bootstrap.min.css.map',
    'dist/js/bootstrap.bundle.min.js',
    'dist/js/bootstrap.bundle.min.js.map',
  ],
  'bootstrap-colorpicker': [
    'dist',
  ],
  'chart.js': [
    'dist/Chart.min.js',
    'dist/Chart.min.css',
  ],
  'codemirror': [
    'lib/codemirror.css',
    'lib/codemirror.js',
    'mode/smarty/smarty.js',
    'mode/javascript/javascript.js',
    'mode/css/css.js',
    'mode/htmlmixed/htmlmixed.js',
  ],
  'datatables.net': [
    'js/jquery.dataTables.min.js',
  ],
  'datatables.net-bs4': [
    'css/dataTables.bootstrap4.css',
    'js/dataTables.bootstrap4.min.js',
  ],
  'dropzone': [
    'dist/min/dropzone.min.css',
    'dist/min/dropzone.min.js',
  ],
  'fomantic-ui': [
    'dist/semantic.min.css',
    'dist/semantic.min.js',
    'dist/themes/default',
  ],
  'image-picker': [
    'image-picker/image-picker.css',
    'image-picker/image-picker.min.js',
  ],
  'jquery': [
    'dist/jquery.min.js',
  ],
  'jquery-ui-dist': [
    'jquery-ui.min.js',
  ],
  'jquery.cookie': [
    'jquery.cookie.js',
  ],
  'moment': [
    'min/moment.min.js',
    'min/moment.min.js.map',
  ],
  'select2': [
    'dist/js/select2.min.js',
    'dist/css/select2.min.css',
  ],
  'tinymce': [
    'icons',
    'plugins',
    'skins',
    'themes',
    'tinymce.min.js',
  ],
};

fse.emptyDirSync('core/assets/vendor');

Object.keys(publicAssets).forEach((name) => {
  publicAssets[name].forEach((asset) => {
    const fullPath = `${name}/${asset}`;
    console.log(`Copying ${fullPath}...`);
    fse.copySync(
      `node_modules/${fullPath}`,
      `core/assets/vendor/${fullPath}`
    );
  });
});
