<?php
$nameless_terms = 'This website uses "Nameless" website software. The ' .
    '"Nameless" software creators will not be held responsible for any content ' .
    'that may be experienced whilst browsing this site, nor are they responsible ' .
    'for any loss of data which may come about, for example a hacking attempt. ' .
    'The website is run independently from the software creators, and any content' .
    ' is the responsibility of the website administration.';

function create_step($name, $icon, $child_steps = []) {

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

function create_field($type, $label, $name, $id, $value = '', $options = [], $fallback = false) {

    if ($type == 'select') {

        $options_markup = '';
        foreach ($options as $option_value => $option_label) {
            $selected = ($value == $option_value ? ' selected' : ($fallback ? ($value == $option_label ? ' selected' : '') : ''));
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

function validate_requirement($text, $condition) {

    if ($condition == true) {
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
        $_SESSION['requirements_validated'] = $condition;
    } else {
        if ($_SESSION['requirements_validated'] == 'true') {
            $_SESSION['requirements_validated'] = $condition;
        }
    }

}
