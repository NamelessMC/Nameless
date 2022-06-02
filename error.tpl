<!DOCTYPE html>
<html lang="{$LANG}" {$RTL}>

<head>

    <meta charset="{$LANG_CHARSET}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{$TITLE}">

    <!-- Page Title -->
    <title>{$TITLE}</title>

    <meta name="author" content="{$SITE_NAME}">

    <link rel="stylesheet" href="{$BOOTSTRAP}">
    <link rel="stylesheet" href="{$CUSTOM}">
    <link rel="stylesheet" href="{$FONT_AWESOME}">
    <link rel="stylesheet" href="{$PRISM_CSS}">
    <link rel="stylesheet" href="{$TOAST_CSS}">

</head>

<body>

    <br /><br />
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-{if $DETAILED_ERROR}10 offset-1{else}4 offset-md-4{/if}">
                <div class="jumbotron">
                    <div style="text-align:{if $DETAILED_ERROR} left {else} center {/if};">
                        {if $DETAILED_ERROR}
                        <h4>Uncaught <i>{$ERROR_TYPE}</i></h4>
                        <h2><kbd>{$ERROR_STRING}</kbd></h2>
                        <h3>(File: {$ERROR_FILE})</h3>
                        <a href="{$CURRENT_URL}">{$CURRENT_URL}</a>
                        {if $CAN_GENERATE_DEBUG}
                        <button class="float-right btn btn-info d-flex align-items-center" id="show_debug_modal" onclick="showDebugModal()">
                            <span id="debug_link_text">{$DEBUG_LINK}</span>
                            <span id="debug_link_success" style="display: none;">
                                <i class="fa fa-check"></i>
                            </span>
                            <span id="debug_link_error" style="display: none;">
                                <i class="fa fa-times-circle"></i>
                            </span>
                        </button>
                        {/if}
                        {else}
                        <h2>{$FATAL_ERROR_TITLE}</h2>
                        <h4>{$FATAL_ERROR_MESSAGE_USER}</h4>

                        <div class="btn-group" role="group" aria-label="...">
                            <button href="#" class="btn btn-primary btn-lg" onclick="history.go(-1)">
                                {$BACK}
                            </button>
                            <a href="{$HOME_URL}" class="btn btn-success btn-lg">
                                {$HOME}
                            </a>
                        </div>
                        {/if}
                    </div>
                </div>
            </div>
        </div>

        {if $DETAILED_ERROR}

        <br />

        <div class="row">
            <div class="col-md-10 offset-1">
                <div class="jumbotron">
                    <div class="card">
                        <div class="card-body">

                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#stack">Stack trace</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#sql">SQL trace</a>
                                </li>
                            </ul>

                            <br />

                            <div class="tab-content">
                                <div class="tab-pane show active" id="stack">

                                    <div class="tab">
                                        {foreach from=$FRAMES item=frame}
                                        <button class="tablinks" id="button-{$frame['number']}"
                                            onclick="openFrame({$frame['number']})">
                                            <h5>Frame #{$frame['number']}</h5>
                                            <sub>{$frame['file']}:{$frame['line']}</sub>
                                        </button>
                                        {/foreach}
                                    </div>

                                    <div class="code">
                                        {foreach from=$FRAMES item=frame}
                                        <div id="frame-{$frame['number']}" class="tabcontent">
                                            <h5>File: <strong>{$frame['file']}</strong></h5>

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

                                <div class="tab-pane active" id="sql">
                                    <div class="tab">
                                        {foreach from=$ERROR_SQL_STACK item=$stack}
                                        <button class="sql-tablinks" id="sql-button-{$stack['number']}"
                                            onclick="openSqlFrame({$stack['number']})">
                                            <h5>Query #{$stack['number']}</h5>
                                            <sub>{$stack['frame']['file']}:{$stack['frame']['line']}</sub>
                                        </button>
                                        {/foreach}
                                    </div>

                                    <div class="code">
                                        {foreach from=$ERROR_SQL_STACK item=$stack}
                                        <div id="sql-frame-{$stack['number']}" class="sql-tabcontent">
                                            <h5>SQL query: <strong>{$stack['sql_query']}</strong></h5>
                                            <h5>File: <strong>{$stack['frame']['file']}</strong></h5>

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
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {/if}

    </div>

    <!-- Debug link modal -->
    <div class="modal fade" id="debug_link_modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    {$DEBUG_LINK_INFO}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{$CANCEL}</button>
                    <button class="btn btn-primary" id="generateUrl" onclick="generateUrl()">
                            <span class="spinner-border spinner-border-sm mr-2" role="status"
                                  id="debug_link_loading" style="display: none;"></span>
                        <span>{$DEBUG_LINK}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>

<style>
    {literal}

    * {
        box-sizing: border-box
    }

    /* Style the tab */
    .tab {
        float: left;
        border: 1px solid #ccc;
        background-color: #f1f1f1;
        width: 20%;
        max-height: inherit;
    }

    /* Style the buttons that are used to open the tab content */
    .tab button {
        display: block;
        background-color: inherit;
        color: black;
        padding: 15px 16px;
        width: 100%;
        border: none;
        outline: none;
        text-align: left;
        cursor: pointer;
        transition: 0.1s;
    }

    /* Change background color of buttons on hover */
    .tab button:hover {
        background-color: #ddd;
    }

    /* Create an active/current "tab button" class */
    .tab button.active {
        background-color: #ccc;
    }

    /* Style the tab content */
    .tabcontent,
    .sql-tabcontent {
        float: left;
        padding: 0 12px;
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

    /* Move icon of the toast to right */
    .toast .move-right {
        position: absolute;
        left: 20px;
    }

    {/literal}
</style>

<script src="{$JQUERY}"></script>
<script src="{$BOOTSTRAP_JS}"></script>
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
        // Fix prism line highlights not reaching full width of scroll box
        const line_highlights = document.getElementsByClassName('line-highlight');
        for (line of line_highlights) {
            line.style.width = line.parentNode.scrollWidth + "px";
        }

        // Jank fix for prism not highlighting the tabs which are "display: hidden;"
        // from the bootstrap active class
        document.getElementById('sql').classList.remove('active');

        hideAllFrames();
        hideAllSqlFrames();

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
    let link_created = false;

    function showDebugModal() {
      $('#debug_link_modal').modal('show');
    }

    const generateUrl = () => {
        $('#generateUrl').prop('disabled', true);
        $('#show_debug_modal').prop('disabled', true);

        if (link_created) {
            return;
        }

        $('#debug_link').prop('disabled', true);
        $('#debug_link_loading').show(100);
        $.get('{$DEBUG_LINK_URL}')
            .done((url) => {
                link_created = true;

                $('#debug_link_loading').hide(100);
                $('#debug_link_modal').modal('hide');
                $('#show_debug_modal').removeClass('btn-info');
                $('#debug_link_text').hide();

                if (!url.startsWith('https://debug.namelessmc.com/')) {
                    $('#show_debug_modal').addClass('btn-danger');
                    $('#debug_link_error').show();
                    console.error(url);
                    $('body').toast({
                        showIcon: 'fa-solid fa-circle-info move-right',
                        message: 'Could not create debug link. Check console for information.',
                        class: 'warning',
                        progressUp: true,
                        displayTime: 6000,
                        pauseOnHover: true,
                        position: 'bottom left',
                    });
                } else {
                    $('#show_debug_modal').addClass('btn-success');
                    $('#debug_link_success').show();

                    if (navigator.clipboard !== undefined) {
                        $('body').toast({
                            showIcon: 'fa-solid fa-circle-info move-right',
                            message: 'Copied debug link to your clipboard.',
                            class: 'info',
                            progressUp: true,
                            displayTime: 6000,
                            pauseOnHover: true,
                            position: 'bottom left',
                        });
                        navigator.clipboard.writeText(url);
                    } else {
                        $('body').toast({
                            showIcon: 'fa-solid fa-circle-info move-right',
                            message: url,
                            class: 'info',
                            progressUp: true,
                            displayTime: 6000,
                            pauseOnHover: true,
                            position: 'bottom left',
                        });
                    }
                }
            });
    };
    {/if}
</script>

</html>
