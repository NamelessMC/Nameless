{include file='header.tpl'}

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        {include file='navbar.tpl'}
        {include file='sidebar.tpl'}

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">{$ANNOUNCEMENTS}</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                                <li class="breadcrumb-item active">{$CONFIGURATION}</li>
                                <li class="breadcrumb-item active">{$PAGE}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    {include file='includes/update.tpl'}

                    <div class="card">
                        <div class="card-body">
                            <h5 style="display:inline">{$ANNOUNCEMENT_TITLE}</h5>
                            <div class="float-md-right">
                                <a href="{$BACK_LINK}" class="btn btn-warning">{$BACK}</a>
                            </div>
                            <hr>

                            {include file='includes/success.tpl'}

                            {include file='includes/errors.tpl'}

                            <form role="form" action="" method="post">
                                <div class="form-group">
                                    <label for="header">{$HEADER}</label>
                                    <input type="text" name="header" class="form-control" id="header" value="{$ANNOUNCEMENT->header}">
                                </div>
                                <div class="form-group">
                                    <label for="message">{$MESSAGE}</label>
                                    <textarea name="message" class="form-control" id="message">{$ANNOUNCEMENT->message}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="pages">{$PAGES}</label>
                                    <select name="pages[]" id="pages" class="form-control" multiple>
                                        {foreach from=$PAGES_ARRAY item=page}
                                            <option value="{$page.name}"{if isset($ANNOUNCEMENT) && in_array($page.name, $ANNOUNCEMENT->pages)} selected {/if}>{$page.name|ucfirst}</option>
                                        {/foreach}
                                    </select>
                                </div>
                                <div class="form-group backgroundColour">
                                    <label for="InputBackgroundColour">{$BACKGROUND_COLOUR}</label>
                                    <div class="input-group">
                                        <input type="text" name="background_colour" class="form-control" id="InputBackgroundColour" value="{$ANNOUNCEMENT->background_colour}">
                                        <span class="input-group-append">
                                            <span class="input-group-text colorpicker-input-addon"><i></i></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group textColour">
                                    <label for="InputTextColour">{$TEXT_COLOUR}</label>
                                    <div class="input-group">
                                        <input type="text" name="text_colour" class="form-control" id="InputTextColour" value="{$ANNOUNCEMENT->text_colour}">
                                        <span class="input-group-append">
                                            <span class="input-group-text colorpicker-input-addon"><i></i></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="icon">{$ICON} <span class="badge badge-info" data-toggle="popover" data-title="{$INFO}" data-content="{$ICON_INFO|escape}"><i class="fa fa-question"></i></label>
                                    <input type="text" name="icon" id="icon" class="form-control" placeholder="fas fa-edit icon" value="{$ANNOUNCEMENT->icon|escape}">
                                </div>
                                <div class="form-group">
                                    <label for="closable">{$CLOSABLE}</label>
                                    <input id="closable" name="closable" type="checkbox" class="js-switch" value="1" {if $ANNOUNCEMENT->closable} checked{/if} />
                                </div>
                                <strong>Groups</strong>
                                <script>
                                    var groups = [];
                                    groups.push("0");
                                </script>
                                <table class="table table-responsive table-striped">
                                    <thead>
                                        <tr>
                                            <th>{$NAME}</th>
                                            <th>{$CAN_VIEW_ANNOUNCEMENT}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td onclick="toggleAll(this);">{$GUESTS}</td>
                                            <td><input type="hidden" name="perm-view-0" value="0" /><input onclick="colourUpdate(this);"
                                                    name="perm-view-0" id="Input-view-0" value="1" type="checkbox" {if $GUEST_PERMISSIONS} checked {/if} /></td>
                                        </tr>
                                        {foreach from=$GROUPS item=item}
                                        <tr>
                                            <td onclick="toggleAll(this);">{$item.name}</td>
                                            <td><input type="hidden" name="perm-view-{$item.id}" value="0" /><input onclick="colourUpdate(this);"
                                                    name="perm-view-{$item.id}" id="Input-view-{$item.id}" value="1" type="checkbox" {if $GROUPS[$item.id]['allowed'] } checked {/if} /></td>
                                        </tr>
                                        <script>groups.push("{$item.id}");</script>
                                        {/foreach}
                                    </tbody>
                                </table>
                                <div class="form-group">
                                    <input type="hidden" name="token" value="{$TOKEN}">
                                    <input type="submit" class="btn btn-primary" value="{$SUBMIT}">
                                </div>
                            </form>

                        </div>
                    </div>

                    <!-- Spacing -->
                    <div style="height:1rem;"></div>

                </div>
            </section>
        </div>

        {include file='footer.tpl'}

    </div>
    <!-- ./wrapper -->

    {include file='scripts.tpl'}

    <script type="text/javascript">
            $(function () {
                $('.backgroundColour').colorpicker({
                    format: 'hex',
                    'color': {if $ANNOUNCEMENT->background_colour} '{$ANNOUNCEMENT->background_colour}' {else}'#007BFF'{/if}
                });
                $('.textColour').colorpicker({
                    format: 'hex',
                    'color': {if $ANNOUNCEMENT->text_colour} '{$ANNOUNCEMENT->text_colour}' {else}'#ffffff'{/if}
                });
            });
            function colourUpdate(that) {
                var x = that.parentElement;
                if (that.checked) {
                    x.className = "bg-success";
                } else {
                    x.className = "bg-danger";
                }
            }
            function toggle(group) {
                if (document.getElementById('Input-view-' + group).checked) {
                    document.getElementById('Input-view-' + group).checked = false;
                } else {
                    document.getElementById('Input-view-' + group).checked = true;
                }
                colourUpdate(document.getElementById('Input-view-' + group));
            }
            for (var g in groups) {
                colourUpdate(document.getElementById('Input-view-' + groups[g]));
            }

            // Toggle all columns in row
            function toggleAll(that) {
                var first = (($(that).parents('tr').find(':checkbox').first().is(':checked') == true) ? false : true);
                $(that).parents('tr').find(':checkbox').each(function () {
                    $(this).prop('checked', first);
                    colourUpdate(this);
                });
            }

            $(document).ready(function () {
                $('td').click(function () {
                    let checkbox = $(this).find('input:checkbox');
                    let id = checkbox.attr('id');

                    if (checkbox.is(':checked')) {
                        checkbox.prop('checked', false);

                        colourUpdate(document.getElementById(id));
                    } else {
                        checkbox.prop('checked', true);

                        colourUpdate(document.getElementById(id));
                    }
                }).children().click(function (e) {
                    e.stopPropagation();
                });
            });
        </script>

</body>

</html>