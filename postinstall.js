console.log('Copying to core/assets/vendor...')

const fse = require('fs-extra');

fse.copySync('node_modules', 'core/assets/vendor');
