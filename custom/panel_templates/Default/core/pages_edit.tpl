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
                        <h1 class="m-0 text-dark">{$CUSTOM_PAGES}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$PAGES}</li>
                            <li class="breadcrumb-item active">{$CUSTOM_PAGES}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                {if isset($NEW_UPDATE)}
                {if $NEW_UPDATE_URGENT eq true}
                <div class="alert alert-danger">
                    {else}
                    <div class="alert alert-primary alert-dismissible" id="updateAlert">
                        <button type="button" class="close" id="closeUpdate" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        {/if}
                        {$NEW_UPDATE}
                        <br />
                        <a href="{$UPDATE_LINK}" class="btn btn-primary" style="text-decoration:none">{$UPDATE}</a>
                        <hr />
                        {$CURRENT_VERSION}<br />
                        {$NEW_VERSION}
                    </div>
                    {/if}

                    <div class="card">
                        <div class="card-body">
                            <h5 style="display:inline">{$EDITING_PAGE}</h5>
                            <div class="float-md-right">
                                <button type="button" class="btn btn-warning" onclick="showCancelModal()">{$CANCEL}</button>
                            </div>
                            <hr />

                            {if isset($SUCCESS)}
                                <div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h5><i class="icon fa fa-check"></i> {$SUCCESS_TITLE}</h5>
                                    {$SUCCESS}
                                </div>
                            {/if}

                            {if isset($ERRORS) && count($ERRORS)}
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h5><i class="icon fas fa-exclamation-triangle"></i> {$ERRORS_TITLE}</h5>
                                    <ul>
                                        {foreach from=$ERRORS item=error}
                                            <li>{$error}</li>
                                        {/foreach}
                                    </ul>
                                </div>
                            {/if}

                            <form action="" method="post">
                                <div class="form-group">
                                    <label for="inputTitle">{$PAGE_TITLE}</label>
                                    <input type="text" class="form-control" name="page_title" id="inputTitle" placeholder="{$PAGE_TITLE}" value="{$PAGE_TITLE_VALUE}">
                                </div>
                                <div class="form-group">
                                    <label for="inputURL">{$PAGE_PATH}</label>
                                    <input type="text" class="form-control" name="page_url" id="inputURL" placeholder="{$PAGE_PATH}" value="{$PAGE_PATH_VALUE}">
                                </div>
                                <div class="form-group">
                                    <label for="link_location">{$PAGE_LINK_LOCATION}</label>
                                    <select class="form-control" id="link_location" name="link_location">
                                        <option value="1"{if $PAGE_LINK_LOCATION_VALUE eq 1} selected{/if}>{$PAGE_LINK_NAVBAR}</option>
                                        <option value="2"{if $PAGE_LINK_LOCATION_VALUE eq 2} selected{/if}>{$PAGE_LINK_MORE}</option>
                                        <option value="3"{if $PAGE_LINK_LOCATION_VALUE eq 3} selected{/if}>{$PAGE_LINK_FOOTER}</option>
                                        <option value="4"{if $PAGE_LINK_LOCATION_VALUE eq 4} selected{/if}>{$PAGE_LINK_NONE}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="inputContent">{$PAGE_CONTENT}</label>
                                    <textarea name="content" id="inputContent">{$PAGE_CONTENT_VALUE}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="inputRedirect">{$PAGE_REDIRECT}</label>
                                    <input id="inputRedirect" name="redirect_page" type="checkbox" class="js-switch"{if $PAGE_REDIRECT_VALUE eq 1} checked{/if} />
                                </div>
                                <div class="form-group">
                                    <label for="inputRedirectLink">{$PAGE_REDIRECT_TO}</label>
                                    <input type="text" class="form-control" id="inputRedirectLink" name="redirect_link" placeholder="{$PAGE_REDIRECT_TO}" value="{$PAGE_REDIRECT_TO_VALUE}">
                                </div>
                                <div class="form-group">
                                    <label for="inputUnsafeHTML">{$UNSAFE_HTML}</label> <span data-toggle="popover" data-content="{$UNSAFE_HTML_WARNING}" class="badge badge-warning"><i class="fas fa-exclamation-triangle"></i></span>
                                    <input id="inputUnsafeHTML" name="unsafe_html" type="checkbox" class="js-switch"{if $UNSAFE_HTML_VALUE eq 1} checked{/if} />
                                </div>
                                <div class="form-group">
                                    <label for="inputSitemap">{$INCLUDE_IN_SITEMAP}</label>
                                    <input id="inputSitemap" name="sitemap" type="checkbox" class="js-switch"{if $INCLUDE_IN_SITEMAP_VALUE eq 1} checked{/if} />
                                </div>
                                <hr />
                                <strong>{$PAGE_PERMISSIONS}</strong>
                                <script>
                                    var groups = [];
                                    groups.push("0");
                                </script>
                                <table class="table table-responsive table-striped">
                                    <thead>
                                    <tr>
                                        <th>{$GROUP}</th>
                                        <th>{$VIEW_PAGE}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td onclick="toggleAll(this);">{$GUESTS}</td>
                                        <td><input type="hidden" name="perm-view-0" value="0" /><input onclick="colourUpdate(this);" name="perm-view-0" id="Input-view-0" value="1" type="checkbox"{if $GUEST_PERMS eq 1} checked{/if} /></td>
                                    </tr>
                                    {foreach from=$GROUPS item=item}
                                        <tr>
                                            <td onclick="toggleAll(this);">{$item.name}</td>
                                            <td><input type="hidden" name="perm-view-{$item.id}" value="0" /><input onclick="colourUpdate(this);" name="perm-view-{$item.id}" id="Input-view-{$item.id}" value="1" type="checkbox"{if $item.view eq 1} checked{/if} /></td>
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

    <div class="modal fade" id="cancelModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{$ARE_YOU_SURE}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {$CONFIRM_CANCEL}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{$NO}</button>
                    <a href="{$CANCEL_LINK}" class="btn btn-primary">{$YES}</a>
                </div>
            </div>
        </div>
    </div>

    {include file='footer.tpl'}

</div>
<!-- ./wrapper -->

{include file='scripts.tpl'}

<script type="text/javascript">
    function showCancelModal(){
        $('#cancelModal').modal().show();
    }
    function colourUpdate(that) {
        var x = that.parentElement;
        if(that.checked) {
            x.className = "bg-success";
        } else {
            x.className = "bg-danger";
        }
    }
    function toggle(group) {
        if(document.getElementById('Input-view-' + group).checked) {
            document.getElementById('Input-view-' + group).checked = false;
        } else {
            document.getElementById('Input-view-' + group).checked = true;
        }
        colourUpdate(document.getElementById('Input-view-' + group));
    }
    for(var g in groups) {
        colourUpdate(document.getElementById('Input-view-' + groups[g]));
    }

    // Toggle all columns in row
    function toggleAll(that){
        var first = (($(that).parents('tr').find(':checkbox').first().is(':checked') == true) ? false : true);
        $(that).parents('tr').find(':checkbox').each(function(){
            $(this).prop('checked', first);
            colourUpdate(this);
        });
    }

    $(document).ready(function(){
        $('td').click(function() {
            let checkbox = $(this).find('input:checkbox');
            let id = checkbox.attr('id');

            if(checkbox.is(':checked')){
                checkbox.prop('checked', false);

                colourUpdate(document.getElementById(id));
            } else {
                checkbox.prop('checked', true);

                colourUpdate(document.getElementById(id));
            }
        }).children().click(function(e) {
            e.stopPropagation();
        });
    });
</script>

</body>
</html>