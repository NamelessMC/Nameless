<div class="container">
  <h3>{$CREATING_POST_IN}</h3>
  {$LOCKED_MESSAGE}
  <form action="" method="post">
    {$FORM_CONTENT}
	<a href="/forum/view_topic/?tid={$TOPIC_ID}" class="btn btn-danger" onclick="return confirm('{$CONFIRM}');">{$CANCEL}</a>
  </form>
</div>