<?php
declare(strict_types=1);
/**
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.1.0
 *
 *  License: MIT
 *
 *  TODO: Description
 *
 * @var Language $language
 */

$scripts = [
    '
    <script>
        $(document).ready(function() {
            $.post("?step=ajax_initialise&initialise=upgrade", {perform: "true"}, function(response) {
                if (response.success) {
                    window.location.replace(response.redirect_url);
                } else {
                    const info = $("#info");
                    info.html(response.message);
                    if (response.errors) {
                      info.after(`<div class="ui inverted red segment">${response.errors.join("<br />")}</div>`);
                    }
                    if (response.redirect_url) {
                        const button = $("#continue-button");
                        button.attr("href", response.redirect_url);
                        button.removeClass("disabled");
                    }
                }
            });
        });
    </script>
    '
];
?>

<form action="" method="post">
    <div class="ui segments">
        <div class="ui secondary segment">
            <h4 class="ui header">
                <?php

                echo $language->get('installer', 'upgrade'); ?>
            </h4>
        </div>
        <div class="ui segment">
            <span id="info">
                <i class="blue circular notched circle loading icon"></i>
                <?php

                echo $language->get('installer', 'installer_upgrading_database'); ?>
            </span>
        </div>
        <div class="ui right aligned secondary segment">
            <a href="#" class="ui primary disabled button" id="continue-button">
                <?php

                echo $language->get('installer', 'continue'); ?>
            </a>
        </div>
    </div>
</form>
