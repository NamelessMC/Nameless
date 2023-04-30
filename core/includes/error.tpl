<!DOCTYPE html>
<html lang="{$LANG}" {$RTL}>

<head>
    <meta charset="{$LANG_CHARSET}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{$TITLE}">

    <title>{$TITLE}</title>

    <meta name="author" content="{$SITE_NAME}">

    <link rel="stylesheet" href="{$FOMANTIC_CSS}">
    <link rel="stylesheet" href="{$FONT_AWESOME}">
    <link rel="stylesheet" href="{$PRISM_CSS}">
</head>

<body>
    {if $DETAILED_ERROR}
        <div class="ui inverted borderless menu" style="border-radius: 0;">
            <span class="item">
                <img style="width: 30px; height: 30px;" src="core/assets/img/namelessmc_logo.png" alt="Logo">&nbsp;
            </span>
            <div class="right menu">
                <a class="item" target="_blank" href="https://discord.gg/nameless">
                    <i class="life ring icon"></i>{$NAMELESSMC_SUPPORT}
                </a>
                <a class="item" target="_blank" href="https://docs.namelessmc.com">
                    <i class="book icon"></i>{$NAMELESSMC_DOCS}
                </a>
            </div>
        </div>
    {/if}

    <div class="ui container one column grid">
        <div class="row {if !$DETAILED_ERROR}three column{/if}">
            {if !$DETAILED_ERROR}
                <div class="column"></div>
            {/if}
            <div class="ui message {if !$DETAILED_ERROR}column center aligned{/if}"
                 style="{if $CAN_GENERATE_DEBUG}padding-bottom: 20px;{/if} margin-top: 30px; width: 100%; overflow-wrap: break-word;">
                {if $DETAILED_ERROR}
                    <div class="ui large header">Uncaught <i>{$ERROR_TYPE}</i></div>
                    <p></p>
                    <div class="ui large header"><kbd>{$ERROR_STRING}</kbd></div>
                    <p></p>
                    <div class="ui medium header">{$ERROR_FILE}</div>
                    <p></p>
                    <a href="{$CURRENT_URL}">{$CURRENT_URL}</a>
                    {if $CAN_GENERATE_DEBUG}
                        <button style="margin-top: -7px;" class="ui primary right floated button" id="show_debug_modal" onclick="showDebugModal()">
                            {$DEBUG_LINK}
                        </button>
                    {/if}
                {else}
                    <h2>{$FATAL_ERROR_TITLE}</h2>
                    <p>{$FATAL_ERROR_MESSAGE_USER}</p>

                    <div class="btn-group" role="group" aria-label="...">
                        <button class="ui button primary" onclick="history.go(-1)">
                            {$BACK}
                        </button>
                        <a href="{$HOME_URL}" class="ui button success">
                            {$HOME}
                        </a>
                    </div>
                {/if}
            </div>
            {if !$DETAILED_ERROR}
                <div class="column"></div>
            {/if}
        </div>

        {if $DETAILED_ERROR}

        <div class="row">
            <div class="ui two item menu" style="cursor: pointer;">
                <div class="item" data-tab="stack">Stack trace</div>
                <div class="item" data-tab="sql">SQL trace</div>
            </div>
        </div>

        <div class="row">
            <div class="ui bottom attached tab segment active" id="stack" data-tab="stack" style="border-radius: 3px;">
                <div class="ui tab secondary vertical menu left floated tablinks-container" id="tablinks-container">
                    {foreach from=$FRAMES item=frame}
                        <button class="tablinks item" id="button-{$frame['number']}" onclick="openFrame({$frame['number']})">
                            <h5>{$FRAME} #{$frame['number']}</h5>
                            <sub>{$frame['file']}:{$frame['line']}</sub>
                        </button>
                    {/foreach}
                </div>

                {foreach from=$FRAMES item=frame}
                    <div id="frame-{$frame['number']}" class="tabcontent">
                        <h5 style="overflow: scroll !important;">{$frame['file']}</h5>
                        <div class="ui divider"></div>

                        {if $frame['code'] != ''}
                            <pre data-line="{$frame['highlight_line']}"
                                 data-start="{$frame['start_line']}"><code class="language-php line-numbers">{$frame['code']}</code></pre>
                        {else}
                            <pre class="text-center">{$CANNOT_READ_FILE}</pre>
                        {/if}
                    </div>
                {/foreach}
            </div>

            <div class="ui bottom attached tab segment active" id="sql" data-tab="sql" style="border: 1px solid #d4d4d5; border-radius: 3px;">
                <div class="ui tab secondary vertical menu left floated tablinks-container" id="sql-tablinks-container">
                    {foreach from=$ERROR_SQL_STACK item=$stack}
                        <button class="sql-tablinks item" id="sql-button-{$stack['number']}" onclick="openSqlFrame({$stack['number']})">
                            <h5>{$SQL_QUERY} #{$stack['number']}</h5>
                            <sub>{$stack['frame']['file']}:{$stack['frame']['line']}</sub>
                        </button>
                    {/foreach}
                </div>

                {foreach from=$ERROR_SQL_STACK item=$stack}
                    <div id="sql-frame-{$stack['number']}" class="sql-tabcontent">
                        <h5 style="overflow: scroll !important;">{$stack['frame']['file']}</h5>
                        <div class="ui divider"></div>
                        {$stack['sql_query']}

                        {if $stack['frame']['code'] != ''}
                            <pre data-line="{$stack['frame']['highlight_line']}"
                                data-start="{$stack['frame']['start_line']}"><code class="language-php line-numbers">{$stack['frame']['code']}</code></pre>
                        {else}
                            <pre class="text-center">{$CANNOT_READ_FILE}</pre>
                        {/if}
                    </div>
                {/foreach}
            </div>
        </div>

        {/if}

    </div>

    {if $CAN_GENERATE_DEBUG}
        <div class="ui modal tiny" id="debug_link_modal">
            <i class="close icon"></i>
            <div class="header">
                {$GENERATE_DEBUG_LINK}
            </div>
            <div class="content">
                {$DEBUG_LINK_INFO}
            </div>
            <div class="actions">
                <div class="actions">
                    <div class="ui button cancel">
                        <i class="remove icon"></i>
                        {$CANCEL}
                    </div>
                    <div class="ui button green" id="generate_debug_url" onclick="generateUrl()">
                        <i class="checkmark icon"></i>
                        {$GENERATE}
                    </div>
                </div>
            </div>
        </div>
    {/if}
</body>

<style>
    {literal}

    /* Style the buttons that are used to open the tab content */
    .tablinks, .sql-tablinks {
        text-align: left;
        cursor: pointer;
    }

    .tablinks:hover, .sql-tablinks:hover {
        background-color: rgb(247, 247, 247) !important;
    }

    /* Style the tab content */
    .tabcontent,
    .sql-tabcontent {
        float: left;
        width: 80%;
    }

    .tablinks-container {
        min-height: 920px !important;
        max-height: 920px;
        overflow-y: scroll;
    }

    @media (max-width: 1198px) {
        .tabcontent,
        .sql-tabcontent {
            width: 100%;
        }

        .tablinks, .sql-tablinks {
            width: 100% !important;
        }

        .tablinks-container {
            min-height: 0;
        }
    }

    /* Force SQL query to not overflow */
    .sql-tabcontent > pre {
        overflow-y: scroll;
    }

    code {
        cursor: default;
    }
    {/literal}
</style>

<script src="{$JQUERY}"></script>
<script src="{$FOMANTIC_JS}"></script>
<script src="{$PRISM_JS}"></script>

<script>
    function hideAllFrames() {
        $('.tabcontent').each(function() {
            $(this).css('display', 'none');
        });
    }

    function hideAllSqlFrames() {
        $('.sql-tabcontent').each(function() {
            $(this).css('display', 'none');
        });
    }

    function removeAllActive() {
        $('.tablinks').each(function() {
            $(this).removeClass('active');
        });
    }

    function removeAllActiveSqlFrames() {
        $('.sql-tablinks').each(function() {
            $(this).removeClass('active');
        });
    }

    $(document).ready(function() {
        $('.menu .item').tab();

        // Fix prism not highlighting the tabs which are "display: hidden;"
        // from the fomantic active class
        document.getElementById('sql').classList.remove('active');

        // handle expanding width of tablinks on mobile
        checkWidth();
        window.addEventListener('resize', checkWidth);

        openFrame({$FRAMES|count + $SKIP_FRAMES});
        openSqlFrame({$ERROR_SQL_STACK|count});
    });

    function checkWidth() {
        if (window.matchMedia("(max-width: 1198px)").matches) {
            document.getElementById('tablinks-container').classList.add('fluid');
            document.getElementById('sql-tablinks-container').classList.add('fluid');
        } else {
            document.getElementById('tablinks-container').classList.remove('fluid');
            document.getElementById('sql-tablinks-container').classList.remove('fluid');
        }
    }

    function openFrame(id) {
        hideAllFrames();
        removeAllActive();

        $('#frame-' + id).css('display', 'block');
        $('#button-' + id).addClass('active');
    }

    function openSqlFrame(id) {
        hideAllSqlFrames();
        removeAllActiveSqlFrames();

        $('#sql-frame-' + id).css('display', 'block');
        $('#sql-button-' + id).addClass('active');
    }

    {if $CAN_GENERATE_DEBUG}
        function showDebugModal() {
            $('#debug_link_modal').modal('show');
        }

        function generateUrl() {
            const generate_debug_url = $('#generate_debug_url');
            generate_debug_url.addClass('loading');
            generate_debug_url.prop('disabled', true);
            $('#show_debug_modal').prop('disabled', true);

            $.get('{$DEBUG_LINK_URL}')
                .done((url) => {
                    $('#debug_link_modal').modal('hide');

                    if (!url.startsWith('https://debug.namelessmc.com/')) {
                        $('#show_debug_modal').addClass('red');

                        console.error('Debug link generation failure: ' + url);

                        toast('{$DEBUG_CANNOT_GENERATE}', 'error');
                    } else {
                        if (window.isSecureContext) {
                            navigator.clipboard.writeText(url);

                            toast('{$DEBUG_COPIED}', '', 5000);
                        } else {
                            toast('{$DEBUG_TOAST_CLICK}'.replaceAll({literal}'{url}'{/literal}, url));
                        }
                    }
                });
            }

            function toast(message, type = '', time = 0) {
                $('body').toast({
                    showIcon: 'fa-solid fa-circle-info move-right',
                    message: message,
                    class: type,
                    displayTime: time,
                    showProgress: time !== 0 ? 'bottom' : false,
                    closeIcon: time === 0,
                    position: 'bottom right',
                });
            }
        {/if}
</script>

</html>
