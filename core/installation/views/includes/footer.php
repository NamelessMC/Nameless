        <div class="ui very padded inverted vertical footer segment">
            <div class="ui container">
                <div class="ui middle aligned grid">
                    <div class="eight wide column">
                        <h4 class="ui inverted header">
                            Copyright &copy; NamelessMC <?php echo date('Y'); ?>
                            <div class="sub header">
                                Thanks to all <a href="https://github.com/NamelessMC/Nameless/graphs/contributors" target="_blank">
                                NamelessMC contributors</a> since 2014
                            </div>
                        </h4>
                    </div>
                    <div class="eight wide right aligned column">
                        <div class="ui inverted basic labeled scrolling dropdown icon button">
                            <i class="world icon"></i>
                            <span class="text"><?php echo $installer_language; ?></span>
                            <div class="menu">
                                <?php foreach ($languages as $language) { ?>
                                    <a onclick="setLanguage($(this).text())" class="item"><?php echo $language; ?></a>
                                <?php } ?>
                            </div>
                        </div>
                        <a href="https://github.com/NamelessMC/Nameless" target="_blank" class="ui inverted basic icon button">
                            <i class="github icon"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="core/assets/js/jquery.min.js"></script>
    <script src="custom/templates/DefaultRevamp/js/semantic.min.js"></script>
    <script>

        $('.dropdown').dropdown();

        function setLanguage(language) {
            $.ajax({
                'url' : 'install.php?language=' + language,
                'type' : 'GET',
                'success' : function(data) {
                    if (data == 'OK') {
                        window.location.reload();
                    }
                }
            });
        }
    </script>

    <?php
    if ($scripts) {
        foreach ($scripts as $script) {
            echo $script;
        }
    }
    ?>

</body>

</html>
