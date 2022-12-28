<?php
declare(strict_types=1);
/**
 * Title:      Minecraft Avatar
 * URL:        http://github.com/jamiebicknell/Minecraft-Avatar
 * Author:     Jamie Bicknell
 * Twitter:    @jamiebicknell
 *
 * Modified by Samerton for NamelessMC
 */

require '../../vendor/autoload.php';

$cache = new Cache();

$size = isset($_GET['s']) ? max(8, min(250, $_GET['s'])) : 48;
$user = $_GET['u'] ?? '';
$view = isset($_GET['v']) ? $_GET['v'][0] : 'f';
$view = in_array($view, ['f', 'l', 'r', 'b']) ? $view : 'f';

/**
 * Get the skin of a user from the Mojang API
 *
 * @param string $user The username of the user whose skin we want to retrieve.
 * @param Cache $cache The cache object to use for storing and retrieving skin data.
 * @param string $defaultSkin The default skin to use if an error occurs or the user does not have a custom skin.
 * @param string $endpointUrl The URL of the Mojang API endpoint to use for retrieving skin data.
 * @return string The skin data in binary format.
 */
function get_skin(string $user,
                  Cache  $cache,
                  string $defaultSkin = /* Default Steve Skin: https://minecraft.net/images/steve.png*/ 'iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaXHeAAAFDUlEQVR42u2a20sUURzH97G0LKMotPuWbVpslj1olJXdjCgyisowsSjzgrB0gSKyC5UF1ZNQWEEQSBQ9dHsIe+zJ/+nXfM/sb/rN4ZwZ96LOrnPgyxzP/M7Z+X7OZc96JpEISfWrFhK0YcU8knlozeJKunE4HahEqSc2nF6zSEkCgGCyb+82enyqybtCZQWAzdfVVFgBJJNJn1BWFgC49/VpwGVlD0CaxQiA5HSYEwBM5sMAdKTqygcAG9+8coHKY/XXAZhUNgDYuBSPjJL/GkzVVhAEU5tqK5XZ7cnFtHWtq/TahdSw2l0HUisr1UKIWJQBAMehDuqiDdzndsP2EZECAG1ZXaWMwOCODdXqysLf++uXUGv9MhUHIByDOijjdiSAoH3ErANQD73C7TXXuGOsFj1d4YH4OTJAEy8y9Hd0mCaeZ5z8dfp88zw1bVyiYhCLOg1ZeAqC0ybaDttHRGME1DhDeVWV26u17lRAPr2+mj7dvULfHw2q65fhQRrLXKDfIxkau3ZMCTGIRR3URR5toU38HbaPiMwUcKfBAkoun09PzrbQ2KWD1JJaqswjdeweoR93rirzyCMBCmIQizqoizZkm2H7iOgAcHrMHbbV9KijkUYv7qOn55sdc4fo250e+vUg4329/Xk6QB/6DtOws+dHDGJRB3XRBve+XARt+4hIrAF4UAzbnrY0ve07QW8uHfB+0LzqanMM7qVb+3f69LJrD90/1axiEIs6qIs21BTIToewfcSsA+Bfb2x67OoR1aPPzu2i60fSNHRwCw221Suz0O3jO+jh6V1KyCMGse9721XdN5ePutdsewxS30cwuMjtC860T5JUKpXyKbSByUn7psi5l+juDlZYGh9324GcPKbkycaN3jUSAGxb46IAYPNZzW0AzgiQ5tVnzLUpUDCAbakMQXXrOtX1UMtHn+Q9/X5L4wgl7t37r85OSrx+TYl379SCia9KXjxRpiTjIZTBFOvrV1f8ty2eY/T7XJ81FQAwmA8ASH1ob68r5PnBsxA88/xAMh6SpqW4HRnLBrkOA9Xv5wPAZjAUgOkB+SHxgBgR0qSMh0zmZRsmwDJm1gFg2PMDIC8/nAHIMls8x8GgzOsG5WiaqREgYzDvpTwjLDy8NM15LpexDEA3LepjU8Z64my+8PtDCmUyRr+fFwA2J0eAFYA0AxgSgMmYBMZTwFQnO9RNAEaHOj2DXF5UADmvAToA2ftyxZYA5BqgmZZApDkdAK4mAKo8GzPlr8G8AehzMAyA/i1girUA0HtYB2CaIkUBEHQ/cBHSvwF0AKZFS5M0ZwMQtEaEAmhtbSUoDADH9ff3++QZ4o0I957e+zYAMt6wHkhzpjkuAcgpwNcpA7AZDLsvpwiuOkBvxygA6Bsvb0HlaeKIF2EbADZpGiGzBsA0gnwQHGOhW2snRpbpPexbAB2Z1oicAMQpTnGKU5ziFKc4xSlOcYpTnOIUpzgVmgo+XC324WfJAdDO/+ceADkCpuMFiFKbApEHkOv7BfzfXt+5gpT8V7rpfYJcDz+jAsB233r6yyBsJ0mlBCDofuBJkel4vOwBFPv8fyYAFPJ+wbSf/88UANNRVy4Awo6+Ig2gkCmgA5DHWjoA+X7AlM//owLANkX0w0359od++pvX8fdMAcj3/QJ9iJsAFPQCxHSnQt8vMJ3v2wCYpkhkAOR7vG7q4aCXoMoSgG8hFAuc/grMdAD4B/kHl9da7Ne9AAAAAElFTkSuQmCC',
                  string $endpointUrl = 'https://sessionserver.mojang.com/session/minecraft/profile/'): string {
    try {
        // Check cache
        $cache->setCacheName('avatarCache_' . $user);
        if ($cache->hasCashedData($user)) {
            return 'cached';
        }

        $output = $defaultSkin;
        if ($user !== '') {
            $json = HttpClient::get($endpointUrl . $user)->json();

            if (isset($json->properties[0]->value)) {
                $texture = base64_decode($json->properties[0]->value);

                $jsonTexture = json_decode($texture, true);

                if (isset($jsonTexture->textures->SKIN->url)) {
                    $output = HttpClient::get($jsonTexture->textures->SKIN->url)->contents();
                }
            }
        }

        // Cache image
        $cache->setCacheName('avatarCache_' . $user);
        $cache->store($user, 'cached', 3600);

        return $output;
    } catch (Exception $ignored) {
        return $defaultSkin;
    }
}

$skin = get_skin($user, $cache);

if ($skin === 'cached') {
    // Output - already cached
    $im = imagecreatefrompng('cache/' . $user . '.png');
} else {
    // Image not cached
    $im = imagecreatefromstring($skin);
    $av = imagecreatetruecolor($size, $size);

    $x = ['f' => 8, 'l' => 16, 'r' => 0, 'b' => 24];

    imagecopyresized($av, $im, 0, 0, $x[$view], 8, $size, $size, 8, 8);         // Face
    imagecolortransparent($im, imagecolorat($im, 63, 0));                       // Black Hat Issue
    imagecopyresized($av, $im, 0, 0, $x[$view] + 32, 8, $size, $size, 8, 8);    // Accessories

    // Output to screen
    imagepng($av);

    // To file
    imagepng($av, 'cache/' . $user . '.png');

    imagedestroy($av);
}

header('Content-type: image/png');
imagepng($im);
imagedestroy($im);
