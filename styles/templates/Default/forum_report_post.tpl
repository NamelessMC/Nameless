<div class="container">
  <h3>{$REPORT_POST}</h3>
  <center>{$SESSION}</center>
  <div class="panel-group" id="accordion">
    <div class="panel panel-default">
	  <div class="panel-heading">
	    <h4 class="panel-title">
		  <a data-toggle="collapse" data-parent="#accordion" href="#postContent">
		    {$VIEW_POST_CONTENT}
		  </a>
		</h4>
	  </div>
	  <div id="postContent" class="panel-collapse collapse">
	    <div class="panel-body">
	      {$CONTENT}
	    </div>
	  </div>
	</div>
  </div>
  <div class="panel panel-default">
	<div class="panel-heading">
	  {$REPORT_REASON}
	</div>
	<div class="panel-body">
	  {$FORM_CONTENT}
	</div>
  </div>
</div>