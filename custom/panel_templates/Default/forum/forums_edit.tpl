{include file='header.tpl'}

<body id="page-top">

<!-- Wrapper -->
<div id="wrapper">

    <!-- Sidebar -->
    {include file='sidebar.tpl'}

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main content -->
        <div id="content">

            <!-- Topbar -->
            {include file='navbar.tpl'}

            <!-- Begin Page Content -->
            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">{$FORUMS}</h1>
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                        <li class="breadcrumb-item active">{$FORUM}</li>
                        <li class="breadcrumb-item active">{$FORUMS}</li>
                    </ol>
                </div>

                <!-- Update Notification -->
                {include file='includes/update.tpl'}

                <div class="card shadow mb-4">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-9">
                                <h5 style="margin-top: 7px; margin-bottom: 7px;">{$FORUM_TITLE_VALUE}</h5>
                            </div>
                            <div class="col-md-3">
                                <span class="float-md-right"><button class="btn btn-warning"
                                                                     onclick="showCancelModal()">{$CANCEL}</button></span>
                            </div>
                        </div>
                        <hr />

                        <!-- Success and Error Alerts -->
                        {include file='includes/alerts.tpl'}

                        <form role="form" action="" method="post">
                            <div class="form-group">
                                <label for="InputTitle">{$FORUM_TITLE}</label>
                                <input type="text" name="title" class="form-control" id="InputTitle"
                                       placeholder="{$FORUM_TITLE}" value="{$FORUM_TITLE_VALUE}">
                            </div>

                            <div class="form-group">
                                <label for="InputDescription">{$FORUM_DESCRIPTION}</label>
                                <textarea name="description" id="InputDescription" placeholder="{$FORUM_DESCRIPTION}"
                                          class="form-control" rows="3">{$FORUM_DESCRIPTION_VALUE}</textarea>
                            </div>

                            <div class="form-group">
                                <div class="form-group">
                                    <label for="InputType">{$FORUM_TYPE}</label>
                                    <select class="form-control" id="InputType" name="forum_type">
                                        <option value="forum" {if $FORUM_TYPE_VALUE=='forum' } selected{/if}>{$FORUM_TYPE_FORUM}</option>
                                        <option value="category" {if $FORUM_TYPE_VALUE=='category' } selected{/if}>{$FORUM_TYPE_CATEGORY}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="InputIcon">{$FORUM_ICON}</label>
                                <input type="text" name="icon" class="form-control" id="InputIcon"
                                       placeholder="{$FORUM_ICON}" value="{$FORUM_ICON_VALUE}">
                            </div>

                            <div class="form-group">
                                <label for="InputParentForum">{$PARENT_FORUM}</label>
                                <select class="form-control" id="InputParentForum" name="parent_forum">
                                    <option value="0" {if $PARENT_FORUM_VALUE eq 0} selected{/if}>{$NO_PARENT}</option>
                                    {foreach from=$PARENT_FORUM_LIST item=item}
                                        <option value="{$item.id}" {if $item.id eq $PARENT_FORUM_VALUE} selected{/if}>{$item.title}</option>
                                    {/foreach}
                                </select>
                            </div>

                            <div class="form-group">
                                <input type="hidden" name="display" value="0" />
                                <label for="InputDisplay">{$DISPLAY_TOPICS_AS_NEWS}</label>
                                <input name="display" id="InputDisplay" value="1" class="js-switch"
                                       type="checkbox" {if $DISPLAY_TOPICS_AS_NEWS_VALUE} checked{/if} />
                            </div>

                            <div class="form-group">
                                <label for="InputForumRedirect">{$REDIRECT_FORUM}</label>
                                <input type="hidden" name="redirect" value="0">
                                <input name="redirect" id="InputForumRedirect" type="checkbox" class="js-switch"
                                       value="1" {if $REDIRECT_FORUM_VALUE} checked{/if} />
                            </div>

                            <div class="form-group">
                                <label for="InputForumRedirectURL">{$REDIRECT_URL}</label>
                                <input placeholder="{$REDIRECT_URL}" name="redirect_url" id="InputForumRedirectURL"
                                       type="text" class="form-control" value="{$REDIRECT_URL_VALUE}" />
                            </div>

                            <div class="form-group">
                                <label for="InputHooks">{$INCLUDE_IN_HOOK} <span class="badge badge-info"
                                                                                 data-toggle="popover"
                                                                                 data-title="{$INFO}"
                                                                                 data-content="{$HOOK_SELECT_INFO}"><i
                                                class="fa fa-question"></i></label>
                                <select name="hooks[]" id="InputHooks" class="form-control" multiple>
                                    {foreach from=$HOOKS_ARRAY item=hook}
                                        <option value="{$hook.id}" {if in_array($hook.id, $FORUM_HOOKS)} selected {/if}>{$hook.name|ucfirst}</option>
                                    {/foreach}
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="InputDefaultLabels">{$DEFAULT_LABELS} <span class="badge badge-info"
                                                                                        data-toggle="popover"
                                                                                        data-title="{$INFO}"
                                                                                        data-content="{$DEFAULT_LABELS_INFO}"><i
                                                class="fa fa-question"></i></label>
                                <select name="default_labels[]" id="InputDefaultLabels" class="form-control" multiple>
                                    {foreach from=$AVAILABLE_DEFAULT_LABELS item=label}
                                        <option value="{$label.id}" {if $label.is_enabled} selected {/if}>{$label.name}</option>
                                    {/foreach}
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="InputPlaceholder">{$TOPIC_PLACEHOLDER}</label>
                                <textarea id="InputPlaceholder"
                                          name="topic_placeholder">{$TOPIC_PLACEHOLDER_VALUE}</textarea>
                            </div>

                            <strong style="margin-bottom: 15px; display: block">{$FORUM_PERMISSIONS}</strong>
                            <script>
                              var groups = [];
                              groups.push("0");
                            </script>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>{$GROUP}</th>
                                        <th>{$CAN_VIEW_FORUM}</th>
                                        <th>{$CAN_CREATE_TOPIC}</th>
                                        <th>{$CAN_EDIT_TOPIC}</th>
                                        <th>{$CAN_POST_REPLY}</th>
                                        <th>{$CAN_VIEW_OTHER_TOPICS}</th>
                                        <th>{$CAN_MODERATE_FORUM}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>{$GUEST}</td>
                                        <td><input type="hidden" name="perm-view-0" value="0" /><input
                                                    onclick="colourUpdate(this);" name="perm-view-0" id="Input-view-0"
                                                    value="1"
                                                    type="checkbox" {if isset($GUEST_PERMISSIONS->view) && $GUEST_PERMISSIONS->view eq 1} checked{/if} />
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><input type="hidden" name="perm-view_others-0" value="0" /><input
                                                    onclick="colourUpdate(this);" name="perm-view_others-0"
                                                    id="Input-view_others-0" value="1"
                                                    type="checkbox" {if isset($GUEST_PERMISSIONS->view_other_topics) && $GUEST_PERMISSIONS->view_other_topics eq 1} checked{/if} />
                                        </td>
                                        <td></td>
                                    </tr>
                                    {foreach from=$GROUP_PERMISSIONS item=group}
                                        <tr>
                                            <td onclick="toggleAll(this);">{$group->name|escape}</td>
                                            <td><input type="hidden" name="perm-view-{$group->id|escape}" value="0" />
                                                <input onclick="colourUpdate(this);"
                                                       name="perm-view-{$group->id|escape}"
                                                       id="Input-view-{$group->id|escape}" value="1"
                                                       type="checkbox" {if isset($group->view) && $group->view eq 1} checked{/if} />
                                            </td>
                                            <td><input type="hidden" name="perm-topic-{$group->id|escape}"
                                                       value="0" /><input onclick="colourUpdate(this);"
                                                                          name="perm-topic-{$group->id|escape}"
                                                                          id="Input-topic-{$group->id|escape}" value="1"
                                                                          type="checkbox" {if isset($group->create_topic) && $group->create_topic eq 1} checked{/if} />
                                            </td>
                                            <td><input type="hidden" name="perm-edit_topic-{$group->id|escape}"
                                                       value="0" /><input onclick="colourUpdate(this);"
                                                                          name="perm-edit_topic-{$group->id|escape}"
                                                                          id="Input-edit_topic-{$group->id|escape}"
                                                                          value="1"
                                                                          type="checkbox" {if isset($group->edit_topic) && $group->edit_topic eq 1} checked{/if} />
                                            </td>
                                            <td><input type="hidden" name="perm-post-{$group->id|escape}"
                                                       value="0" /><input onclick="colourUpdate(this);"
                                                                          name="perm-post-{$group->id|escape}"
                                                                          id="Input-post-{$group->id|escape}" value="1"
                                                                          type="checkbox" {if isset($group->create_post) && $group->create_post eq 1} checked{/if} />
                                            </td>
                                            <td><input type="hidden" name="perm-view_others-{$group->id|escape}"
                                                       value="0" /><input onclick="colourUpdate(this);"
                                                                          name="perm-view_others-{$group->id|escape}"
                                                                          id="Input-view_others-{$group->id|escape}"
                                                                          value="1"
                                                                          type="checkbox" {if isset($group->view_other_topics) && $group->view_other_topics eq 1} checked{/if} />
                                            </td>
                                            <td><input type="hidden" name="perm-moderate-{$group->id|escape}"
                                                       value="0" /><input onclick="colourUpdate(this);"
                                                                          name="perm-moderate-{$group->id|escape}"
                                                                          id="Input-moderate-{$group->id|escape}"
                                                                          value="1"
                                                                          type="checkbox" {if isset($group->moderate) && $group->moderate eq 1} checked{/if} />
                                            </td>
                                        </tr>
                                        <script>
                                          groups.push("{$group->id|escape}");
                                        </script>
                                    {/foreach}
                                    </tbody>
                                </table>
                            </div>

                            <div class="form-group">
                                <input type="hidden" name="token" value="{$TOKEN}">
                                <input type="hidden" name="action" value="update">
                                <input type="submit" class="btn btn-primary" value="{$SUBMIT}">
                            </div>
                        </form>

                    </div>
                </div>

                <!-- Spacing -->
                <div style="height:1rem;"></div>

                <!-- End Page Content -->
            </div>

            <!-- End Main Content -->
        </div>

        {include file='footer.tpl'}

        <!-- End Content Wrapper -->
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

    <!-- End Wrapper -->
</div>

{include file='scripts.tpl'}

<script type="text/javascript">
  function showCancelModal() {
    $('#cancelModal').modal().show();
  }

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
    if (document.getElementById('Input-topic-' + group).checked) {
      document.getElementById('Input-topic-' + group).checked = false;
    } else {
      document.getElementById('Input-topic-' + group).checked = true;
    }
    if (document.getElementById('Input-edit_topic-' + group).checked) {
      document.getElementById('Input-edit_topic-' + group).checked = false;
    } else {
      document.getElementById('Input-edit_topic-' + group).checked = true;
    }
    if (document.getElementById('Input-post-' + group).checked) {
      document.getElementById('Input-post-' + group).checked = false;
    } else {
      document.getElementById('Input-post-' + group).checked = true;
    }
    if (document.getElementById('Input-view_others-' + group).checked) {
      document.getElementById('Input-view_others-' + group).checked = false;
    } else {
      document.getElementById('Input-view_others-' + group).checked = true;
    }
    if (document.getElementById('Input-moderate-' + group).checked) {
      document.getElementById('Input-moderate-' + group).checked = false;
    } else {
      document.getElementById('Input-moderate-' + group).checked = true;
    }

    colourUpdate(document.getElementById('Input-view-' + group));
    colourUpdate(document.getElementById('Input-topic-' + group));
    colourUpdate(document.getElementById('Input-edit_topic-' + group));
    colourUpdate(document.getElementById('Input-post-' + group));
    colourUpdate(document.getElementById('Input-view_others-' + group));
    colourUpdate(document.getElementById('Input-moderate-' + group));
  }

  for (var g in groups) {
    colourUpdate(document.getElementById('Input-view-' + groups[g]));
    colourUpdate(document.getElementById('Input-view_others-' + groups[g]));
    if (groups[g] != "0") {
      colourUpdate(document.getElementById('Input-topic-' + groups[g]));
      colourUpdate(document.getElementById('Input-edit_topic-' + groups[g]));
      colourUpdate(document.getElementById('Input-post-' + groups[g]));
      colourUpdate(document.getElementById('Input-moderate-' + groups[g]));
    }
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