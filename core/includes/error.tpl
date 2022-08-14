<!DOCTYPE html>
<html lang="{$LANG}" {$RTL}>

<head>
    <meta charset="{$LANG_CHARSET}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{$TITLE}">

    <title>{$TITLE}</title>

    <meta name="author" content="{$SITE_NAME}">

    <link rel="stylesheet" href="{$SEMANTIC_CSS}">
    <link rel="stylesheet" href="{$FONT_AWESOME}">
    <link rel="stylesheet" href="{$PRISM_CSS}">
    <link rel="stylesheet" href="{$TOAST_CSS}">
</head>

<body>
    <div class="ui container one column grid">
        <div class="row {if !$DETAILED_ERROR}three column{/if}">
            {if !$DETAILED_ERROR}
                <div class="column"></div>
            {/if}
            <div class="ui message {if !$DETAILED_ERROR}column center aligned{/if}"
                 style="{if $CAN_GENERATE_DEBUG}padding-bottom: 20px;{/if} margin-top: 30px; width: 100%;">
                {if $DETAILED_ERROR}
                    <h2>Uncaught <i>{$ERROR_TYPE}</i></h2>
                    <h2><kbd>{$ERROR_STRING}</kbd></h2>
                    <h3>{$ERROR_FILE}</h3>
                    <a href="{$CURRENT_URL}">{$CURRENT_URL}</a>
                    {if $CAN_GENERATE_DEBUG}
                        <button class="ui primary right floated button" id="show_debug_modal" onclick="showDebugModal()">
                            {$DEBUG_LINK}
                        </button>
                    {/if}
                {else}
                    <h2>{$FATAL_ERROR_TITLE}</h2>
                    <p>{$FATAL_ERROR_MESSAGE_USER}</p>

                    <div class="btn-group" role="group" aria-label="...">
                        <button href="#" class="ui button primary" onclick="history.go(-1)">
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
            <div class="ui bottom attached tab segment active" id="stack" data-tab="stack">
                <div class="ui tab secondary vertical menu left floated" style="max-height: 950px; overflow-y: scroll;">
                    {foreach from=$FRAMES item=frame}
                        <button class="tablinks item" id="button-{$frame['number']}"
                                onclick="openFrame({$frame['number']})">
                            <h5>Frame #{$frame['number']}</h5>
                            <sub>{$frame['file']}:{$frame['line']}</sub>
                        </button>
                    {/foreach}
                </div>

                <div class="code">
                    {foreach from=$FRAMES item=frame}
                        <div id="frame-{$frame['number']}" class="tabcontent">
                            <h5><strong>{$frame['file']}</strong></h5>

                            {if $frame['code'] != ''}
                                <pre data-line="{$frame['highlight_line']}"
                                     data-start="{$frame['start_line']}"><code class="language-php line-numbers">{$frame['code']}</code></pre>
                            {else}
                                <pre class="text-center">Cannot read file.</pre>
                            {/if}
                        </div>
                    {/foreach}
                </div>
            </div>

            <div class="ui bottom attached tab segment active" id="sql" data-tab="sql">
                <div class="ui tab secondary vertical menu left floated" style="max-height: 950px; overflow-y: scroll;">
                    {foreach from=$ERROR_SQL_STACK item=$stack}
                        <button class="sql-tablinks item" id="sql-button-{$stack['number']}"
                            onclick="openSqlFrame({$stack['number']})">
                            <h5>Query #{$stack['number']}</h5>
                            <sub>{$stack['frame']['file']}:{$stack['frame']['line']}</sub>
                        </button>
                    {/foreach}
                </div>

                <div class="code">
                    {foreach from=$ERROR_SQL_STACK item=$stack}
                        <div id="sql-frame-{$stack['number']}" class="sql-tabcontent">
                            <h5><strong>{$stack['frame']['file']}</strong></h5>
                            <h5><strong>{$stack['sql_query']}</strong></h5>

                            {if $stack['frame']['code'] != ''}
                                <pre data-line="{$stack['frame']['highlight_line']}"
                                    data-start="{$stack['frame']['start_line']}"><code class="language-php line-numbers">{$stack['frame']['code']}</code></pre>
                            {else}
                                <pre class="text-center">Cannot read file.</pre>
                            {/if}
                        </div>
                    {/foreach}
                </div>
            </div>
        </div>

        {/if}

    </div>

    {if $CAN_GENERATE_DEBUG}
        <div class="ui modal tiny" id="debug_link_modal">
            <i class="close icon"></i>
            <div class="header">
                Generate a debug link
            </div>
            <div class="content">
                {$DEBUG_LINK_INFO}
            </div>
            <div class="actions">
                <button type="button" class="ui button" data-dismiss="modal">{$CANCEL}</button>
                <button class="ui button green" id="generate_debug_url" onclick="generateUrl()">
                    Generate
                </button>
            </div>
        </div>
    {/if}
</body>

<style>
    {literal}

    /* Style the buttons that are used to open the tab content */
    .tab button {
        text-align: left;
        cursor: pointer;
    }

    /* Style the tab content */
    .tabcontent,
    .sql-tabcontent {
        float: left;
        width: 80%;
    }

    /* TODO: might be nice to have some padding inside the code container so the code isnt right at the top/right/bottom */
    pre[class*="language-"]>code {
        box-shadow: none;
        border: none;
        background: rgb(245, 245, 245);
        font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        cursor: default;
        border-radius: 3px;
    }

    code[class*="language-"],
    pre[class*="language-"] {
        font-size: 14px;
    }

    pre[class*="language-"]::after,
    pre[class*="language-"]::before {
        display: none;
    }

    .token.entity,
    .token.operator,
    .token.url,
    .token.variable {
        background: none;
    }

    .line-numbers .line-numbers-rows {
        border-right: none;
    }

    .line-highlight {
        background: hsla(30, 80%, 10%, .08);
    }

    {/literal}
</style>

<script src="{$JQUERY}"></script>
<script src="{$SEMANTIC_JS}"></script>
<script src="{$PRISM_JS}"></script>
<script src="{$TOAST_JS}"></script>

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

        // Fix prism line highlights not reaching full width of scroll box
        const line_highlights = document.getElementsByClassName('line-highlight');
        for (line of line_highlights) {
            line.style.width = line.parentNode.scrollWidth + "px";
        }

        // Jank fix for prism not highlighting the tabs which are "display: hidden;"
        // from the bootstrap active class
        document.getElementById('sql').classList.remove('active');

        openFrame({$FRAMES|count + $SKIP_FRAMES});
        openSqlFrame({$ERROR_SQL_STACK|count});
    });

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

        const generateUrl = () => {
            const generate_debug_url = $('#generate_debug_url');
            generate_debug_url.addClass('loading');
            generate_debug_url.prop('disabled', true);
            $('#show_debug_modal').prop('disabled', true);

            $.get('{$DEBUG_LINK_URL}')
                .done((url) => {
                    $('#debug_link_modal').modal('hide');

                    if (!url.startsWith('https://debug.namelessmc.com/')) {
                        $('#show_debug_modal').addClass('red');

                        console.error(url);
                        $('body').toast({
                            showIcon: 'fa-solid fa-circle-info move-right',
                            message: 'Could not create debug link. Check console for information.',
                            class: 'error',
                            progressUp: true,
                            displayTime: 10000,
                            pauseOnHover: true,
                            position: 'bottom right',
                        });
                    } else {
                        if (navigator.clipboard !== undefined) {
                            $('body').toast({
                                showIcon: 'fa-solid fa-circle-info move-right',
                                message: 'Copied debug link to your clipboard.',
                                progressUp: true,
                                displayTime: 10000,
                                pauseOnHover: true,
                                position: 'bottom right',
                            });
                            navigator.clipboard.writeText(url);
                        } else {
                            $('body').toast({
                                showIcon: 'fa-solid fa-circle-info move-right',
                                message: '<a href="' + url + '" target="_blank">Click here</a> to view the debug link.',
                                progressUp: true,
                                displayTime: 10000,
                                pauseOnHover: true,
                                position: 'bottom right',
                            });
                        }
                    }
                });
            };
        {/if}
</script>

</html>
