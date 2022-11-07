<div class="ui very padded inverted vertical footer segment">
    <div class="ui container">
        <div class="ui middle aligned grid">
            <div class="eight wide column">
                <h4 class="ui inverted header">
                    Copyright &copy; NamelessMC <?php echo date('Y'); ?>
                    <div class="sub header">
                        Thanks to all <a href="https://github.com/NamelessMC/Nameless/graphs/contributors"
                                         target="_blank">
                            NamelessMC contributors</a> since 2014
                    </div>
                </h4>
            </div>
            <div class="eight wide right aligned column">
                <div class="ui inverted basic labeled scrolling dropdown icon button">
                    <i class="world icon"></i>
                    <span class="text"><?php echo Language::LANGUAGES[$installer_language]['name']; ?></span>
                    <div class="menu">
                        <?php foreach ($languages as $short_code => $name) { ?>
                            <a onclick="setLanguage($(this).data('short_code'))" data-short_code="<?php echo $short_code; ?>" class="item"><?php echo $name; ?></a>
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

<script src="core/assets/vendor/jquery/dist/jquery.min.js"></script>
<script src="core/assets/vendor/fomantic-ui/dist/semantic.min.js"></script>
<script>

    $('.dropdown').dropdown();

    function setLanguage(language) {
        $.ajax({
            'url': 'install.php?language=' + language,
            'type': 'GET',
            'success': function (data) {
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
