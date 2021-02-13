<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Preview</title>
</head>

<body>

    <style>
        body {
            font-family: arial;
        }

        .email {
            font-size: 0.85em;
            border: 1px solid #cfcfcf;
        }

        .email-top {
            background: #404040;
            color: #FFF;
            font-weight: bold;
            padding: 1em;
        }

        .email-icon {
            float: right;
            margin-left: 1em;
            font-size: 1em;
            color: #b3b3b3;
        }

        .email-body {
            padding: 1em;
            border-bottom: 1px solid #cfcfcf;
        }

        .email-bottom {
            background: #F5F5F5;
            color: #FFF;
            font-weight: bold;
            padding: 1em 1em;
            border: 1px solid #cfcfcf;
        }

        .email-send {
            background: #4d90fe;
            border: 1px solid #3079ed;
            border-radius: 3px;
            padding: 0.4em 1.5em;
        }

        .email-info {
            color: #888;
            font-weight: bold;
        }
    </style>

    <div class="email">
        <div class="email-top">
            New Message
            <div class="email-icon">x</div>
            <div class="email-icon">↕</div>
            <div class="email-icon">_</div>
        </div>
        <div class=email-body>
            <span class="email-info">To:</span> Samerton<br />
            <br />
            <span class="email-info">From:</span> {$USER_NAME}<br />
        </div>
        <div class=email-body>
            <span class="email-info">Subject:</span> {$SUBJECT}
        </div>
        <div class="email-body">
            {$MESSAGE}
        </div>
        <div class="email-bottom">
            <span class="email-send">
                Send
            </span>
        </div>
    </div>

</body>

</html>