<?php require(__DIR__ . '/includes/header.php'); ?>

    <div class="main-content">
        <div class="ui container">
            <div class="ui centered grid">
                <div class="ten wide column">
                    <div class="ui warning message">
                        It appears Nameless has already been installed. If you
                        want to re-install Nameless, you should remove the
                        database tables and the configuration file located at:
                        <span class="ui basic label">/core/config.php</span>
                    </div>
                    <a href="index.php?route=/" class="ui yellow fluid button">Home</a>
                </div>
            </div>
        </div>
    </div>

<?php
require(__DIR__ . '/includes/footer.php');
