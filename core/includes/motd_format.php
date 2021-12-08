<?php
/*
The MIT License (MIT)

Copyright (c) 2014 Winston Weinert

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
*/

function MC_str_split( $string )
{
    return preg_split('/(?<!^)(?!$)/u', $string);
}

function MC_parseMotdColors($motd)
{
    $inColorSequence = false;
    $openSpan = false;
    $coloredMotd = '';
    foreach (MC_str_split($motd) as $character) {
        if ($inColorSequence) {
            // find color and insert span
            // edit: ` used for explode in banner generator
            switch ($character) {
            case '0':
                $color = '`#000000';
                break;
            case '1':
                $color = '`#0000aa';
                break;
            case '2':
                $color = '`#00aa00';
                break;
            case '3':
                $color = '`#00aaaa';
                break;
            case '4':
                $color = '`#aa0000';
                break;
            case '5':
                $color = '`#aa00aa';
                break;
            case '6':
                $color = '`#ffaa00';
                break;
            case '7':
                $color = '`#aaaaaa';
                break;
            case '8':
                $color = '`#555555';
                break;
            case '9':
                $color = '`#5555ff';
                break;
            case 'a':
                $color = '`#55ff55';
                break;
            case 'b':
                $color = '`#55ffff';
                break;
            case 'c':
                $color = '`#ff5555';
                break;
            case 'd':
                $color = '`#ff55ff';
                break;
            case 'e':
                $color = '`#ffff55';
                break;
            case 'f':
            case 'r':
                $color = '`#ffffff';
                break;
            default:
                $color = false;
                break;
            }
            if ($color) {
                if ($openSpan) {
                    $coloredMotd .= '</span>';
                }
                $coloredMotd .= '<span style="color:' . $color . ';">';
                $openSpan = true;
            }
            $inColorSequence = false;
        } elseif ($character== '§') {
            $inColorSequence = true;
        } else {
            $coloredMotd .= $character;
        }
    }
    if ($openSpan) {
        $coloredMotd .= '</span>';
    }
    return $coloredMotd;
}
?>
