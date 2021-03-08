<!DOCTYPE html>
<html lang="{$LANG}" {$RTL}>
<meta charset="{$LANG_CHARSET}">

<head>

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{$TITLE}">

    <!-- Page Title -->
    <title>{$TITLE}</title>

    <meta name="author" content="{$SITE_NAME}">

    <link rel="stylesheet" href="{$BOOTSTRAP}">
    <link rel="stylesheet" href="{$CUSTOM}">
    <link rel="stylesheet" href="{$FONT_AWESOME}">
    <link rel="stylesheet" href="{$PRISM_CSS}">
    <script src="{$JQUERY}"></script>
    <script src="{$PRISM_JS}"></script>

</head>


<body>
    <br /><br />
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="jumbotron">
                    <div style="text-align:center">
                        <h2>{$FATAL_ERROR_TITLE}</h2>

                        {if $DETAILED_ERROR}

                            <h4>{$FATAL_ERROR_MESSAGE_ADMIN}</h4>

                            <kbd>{$ERROR_STRING}</kbd>
                            <br /><br/>

                            <div class="card">
                                <div class="card-body">
                                    <div class="tab">
                                        {foreach from=$FRAMES item=frame}

                                            <button class="tablinks" id="button-{$frame['number']}" onclick="openFrame({$frame['number']})">
                                                <h5>Frame #{$frame['number']}</h5>
                                                <sub>{$frame['file']}:{$frame['line']}</sub>
                                            </button>

                                        {/foreach}
                                    </div>

                                    <div class="code">
                                        {foreach from=$FRAMES item=frame}

                                            <div id="frame-{$frame['number']}" class="tabcontent">
                                                <h5>File: <strong>{$frame['file']}</strong></h5>

                                                <pre data-line="{$frame['highlight_line']}" data-start="{($frame['start_line'])}">
                                                    <code class="language-php line-numbers">{$frame['code']}</code>
                                                </pre>

                                            </div>

                                        {/foreach}
                                    </div>
                                </div>
                            </div>

                        {else}

                            <h4>{$FATAL_ERROR_MESSAGE_USER}</h4>
                            
                            <div class="btn-group" role="group" aria-label="...">
                                <button href="#" class="btn btn-primary btn-lg" onclick="javascript:history.go(-1)">
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
    width: 30%;
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
    transition: 0.3s;
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
.tabcontent {
    float: left;
    padding: 0px 12px;
    width: 70%;
}
{/literal}
</style>

<script>
hideAllFrames();

function hideAllFrames() {
    $('.tabcontent').each(function() {
        $(this).css('display', 'none');
    });
}

function removeAllActive() {
    $('.tablinks').each(function() {
        $(this).removeClass('active');
    });
}

$(document).ready(function() {
    openFrame({$FRAMES|count})
});

function openFrame(id) {

    hideAllFrames();

    removeAllActive();

    $('#frame-' + id).css('display', 'block');
    $('#button-' + id).addClass('active');
}
</script>

</html>