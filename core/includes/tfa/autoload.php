<?php
// Autoloader
require('Providers/Rng/IRNGProvider.php');
require('Providers/Rng/MCryptRNGProvider.php');
require('Providers/Rng/CSRNGProvider.php');
require('Providers/Qr/IQRCodeProvider.php');
require('Providers/Qr/BaseHTTPQRCodeProvider.php');
require('Providers/Qr/GoogleQRCodeProvider.php');
require('TwoFactorAuth.php');