<?php
/**
 * Made by UNKNOWN
 * https://github.com/NamelessMC/Nameless/
 * NamelessMC version UNKNOWN
 *
 * License: MIT
 *
 * TODO: Add description
 */

$nameless_terms = 'This website uses "Nameless" website software. The ' .
    '"Nameless" software creators will not be held responsible for any content ' .
    'that may be experienced whilst browsing this site, nor are they responsible ' .
    'for any loss of data which may come about, for example a hacking attempt. ' .
    'The website is run independently from the software creators, and any content' .
    ' is the responsibility of the website administration.';

/**
 * @param string $name
 * @param string $icon
 * @param array $child_steps

 */
function create_step(string $name, string $icon, array $child_steps = []) {

    global $step;

    $active = '';
    if (!isset($step)) {
        if (in_array('welcome', $child_steps)) {
            $active = 'active ';
        }
    } else {
        if (in_array($step, $child_steps)) {
            $active = 'active ';
        }
    }

    echo "
        <div class=\"$active step\">
            <i class=\"$icon\"></i>
            <div class=\"content\">
                <div class=\"title\">$name</div>
            </div>
        </div>
    ";

}

/**
 * @param string $type
 * @param string $label
 * @param string $name
 * @param string $id
 * @param string $value
 * @param array $options
 * @param bool $fallback
 *
 * @return void
 */
function create_field(string $type, string $label, string $name, string $id, string $value = '', array $options = [], bool $fallback = false): void {

    if ($type === 'select') {

        $options_markup = '';
        foreach ($options as $option_value => $option_label) {
            $selected = ($value === $option_value ? ' selected' : ($fallback ? ($value === $option_label ? ' selected' : '') : ''));
            $option_value = ($fallback ? $option_label : $option_value);
            $options_markup .= "<option value=\"$option_value\"$selected>$option_label</option>" . PHP_EOL;
        }

        echo "
            <div class=\"field\">
                <label for=\"$id\">$label</label>
                <select class=\"ui dropdown\" name=\"$name\" id=\"$id\">
                    $options_markup
                </select>
            </div>
        ";

    } else {

        echo "
            <div class=\"field\">
                <label for=\"$id\">$label</label>
                <input type=\"$type\" name=\"$name\" id=\"$id\" placeholder=\"$label\" value=\"$value\" autocomplete=\"off\">
            </div>
        ";

    }

}

/**
 * @param string $text
 * @param bool $condition
 *
 * @return void
 */
function validate_requirement(string $text, bool $condition): void {
    if ($condition === true) {
        echo "
            <div class=\"ui small positive message\">
                <i class=\"check icon\"></i>
                $text
            </div>
        ";
    } else {
        echo "
            <div class=\"ui small negative message\">
                <i class=\"times icon\"></i>
                $text
            </div>
        ";
    }

    if (!isset($_SESSION['requirements_validated'])) {
        $_SESSION['requirements_validated'] = true;
    } else {
        if ($_SESSION['requirements_validated'] === true) {
            $_SESSION['requirements_validated'] = $condition;
        }
    }
}
