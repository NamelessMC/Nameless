<!-- 
Admin profile view - This file is pretty big because it's the main part of the admin feature.
It displays the punishment of a player and allow to manage them.
It can also creates punishment.
Except the loading of display data, all the call are made with ajax and the result are display in bootstrap modal.
-->
<script type="text/javascript" src="public/js/adminprofile.js"></script>
<div class="well">
	<div class="row">
		<div class="col-md-2">
			<img class="img-responsive" src="<?php echo $data['headUrl'];?>"
			alt="<?php echo $data['player'];?> head">
		</div>
		<div class="col-md-9">
			<h2>
				<div class="row">
					<div class="col-md-4"></div>
					<?php echo $data['player'];?>'s profile
				</div>
			</h2>
			<ul>
				<li>First login : <span><?php echo $data['firstlogin'];?></span></li>
				<li>Last login : <span><?php echo $data['lastlogin'];?></span></li>
				<li>Last ip : <span><?php echo $data['lastip'];?></span></li>
			</ul>
		</div>
	</div>
	<br>
		<p style="text-align: center;">
			Due to the synchronization delay, the modifications 
			operated on a player profile may take <strong style="color: red;">at most 10 seconds to be applied ingame</strong>.
		</p>

	<!-- Ban list part -->
	<div class="panel <?php if(empty($data['bans'])) {echo "panel-info";} else {echo "panel-warning";}?>">
		<div class="panel-heading clearfix">
			<?php if(empty($data['bans'])) {?>
			<h4 class="panel-title pull-left"  style="padding-top: 7.5px;"><strong>Ban list</strong> - This player was never banned !</h4>
			<a class="btn btn-sm btn-info pull-right" data-toggle="modal" data-target="#ban_modal">Ban</a>
		</div>
		<?php }else{?>
		<h4 class="panel-title pull-left"  style="padding-top: 7.5px;">
			<a href="#" onclick="deployPanel('ban_table');">
				<span class="fa fa-chevron-down"></span>
				<strong>Ban list</strong>
			</a>
		</h4>
		<a class="btn btn-sm btn-info pull-right" data-toggle="modal" data-target="#ban_modal">Ban</a>
	</div>
		<div class="hidden" id="ban_table">
			<table class="table valign">
				<thead>
					<tr class="default">
						<th>Server</th>
						<th>Reason</th>
						<th>Staff</th>
						<th>Date</th>
						<th>State</th>
						<th>Unban date</th>
						<th>Unban staff</th>
						<th>Unban reason</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($data['bans'] as $entry){
					  $ban = $entry->getData();?>
						<tr class="<?php echo $ban['state'] ? "warning" : "info-bat";?>">
						<td><?php echo $ban['server'];?></td>
						<td><?php echo $ban['reason'];?></td>
						<td><?php echo $ban['staff'];?></td>
						<td><?php echo $ban['date'];?></td>
						<td class="<?php echo $ban['state'] ? "danger-bat" : "";?>"><?php echo $ban['state'] 
								? Message::state_ACTIVE : Message::state_ENDED;?></td>
						<td><?php echo $ban['unban_date'];?></td>
						<td><?php echo $ban['unban_staff'];?></td>
						<td><?php echo $ban['unban_reason'];?></td>
							<?php if($ban['state']){?>
							<td><input type="button" class="btn btn-success" value="Unban"
							onclick="$('#ban_id').val('<?php echo $ban['id'];?>');
							$('#unban_modal_title').html('Unban <?php echo $data['player'];?> from <?php echo $ban['server'];?>');"
							data-toggle="modal" data-target="#unban_modal"></td>
							<?php }else{?>
							<td><input type="button" class="btn btn-default" value="X"
							disabled="disabled"></td>
							<?php }?>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
		<?php }?>
</div>

<!-- Mute list part -->
<div class="panel <?php if(empty($data['mutes'])) {echo "panel-info";} else {echo "panel-warning";}?>">
	<div class="panel-heading clearfix">
		<?php if(empty($data['mutes'])) {?>
		<h4 class="panel-title pull-left" style="padding-top: 7.5px;"><strong>Mute list</strong> - This player was never muted!</h4>
		<a class="btn btn-sm btn-info pull-right" data-toggle="modal" data-target="#mute_modal">Mute</a>
	</div>
		<?php }else{?>
		<h4 class="panel-title pull-left" style="padding-top: 7.5px;">
			<a href="#" onclick="deployPanel('mute_table');">
				<span class="fa fa-chevron-down"></span>
				<strong>Mute list</strong>
			</a>
		</h4>
		<a class="btn btn-sm btn-info pull-right" data-toggle="modal" data-target="#mute_modal">Mute</a>
</div>
	<div class="hidden" id="mute_table">
		<table class="table">
			<thead>
				<tr class="default">
					<th>Server</th>
					<th>Reason</th>
					<th>Staff</th>
					<th>Date</th>
					<th>State</th>
					<th>Unmute date</th>
					<th>Unmute staff</th>
					<th>Unmute reason</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($data['mutes'] as $entry){
					  $mute = $entry->getData();?>
						<tr class="<?php echo $mute['state'] ? "warning" : "info-bat";?>">
					<td><?php echo $mute['server'];?></td>
					<td><?php echo $mute['reason'];?></td>
					<td><?php echo $mute['staff'];?></td>
					<td><?php echo $mute['date'];?></td>
					<td class="<?php echo $mute['state'] ? "danger-bat" : "";?>"><?php echo $mute['state'] 
								? Message::state_ACTIVE : Message::state_ENDED;?></td>
					<td><?php echo $mute['unmute_date'];?></td>
					<td><?php echo $mute['unmute_staff'];?></td>
					<td><?php echo $mute['unmute_reason'];?></td>
					<?php if($mute['state']){?>
						<td><input type="button" class="btn btn-success" value="Unmute"
						onclick="$('#mute_id').val('<?php echo $mute['id'];?>');
						$('#unmute_modal_title').html('Unmute <?php echo $data['player'];?> from <?php echo $mute['server'];?>');"
						data-toggle="modal" data-target="#unmute_modal"></td>
					<?php }else{?>
						<td><input type="button" class="btn btn-default" value="X"
						disabled="disabled"></td>
					<?php }?>
				</tr>
					<?php } ?>
				</tbody>
		</table>
	</div>
		<?php }?>
	</div>
	
<!-- Kick list -->
<div
	class="panel <?php if(empty($data['kicks'])) {echo "panel-info";} else {echo "panel-warning";}?>">
		<?php if(empty($data['kicks'])) {?>
		<div class="panel-heading">
		<h4 class="panel-title"><strong>Kick list</strong> - This player was never kicked!</h4>
		</div>
		<?php }else{?>
		<div class="panel-heading">
		<h4 class="panel-title">
			<a href="#" onclick="deployPanel('kick_table');">
				<span class="fa fa-chevron-down"></span>
				<strong>Kick list</strong>
			</a>
		</h4>
		</div>
	<div class="hidden" id="kick_table">
		<table class="table">
			<thead>
				<tr class="default">
					<th>Server</th>
					<th>Reason</th>
					<th>Staff</th>
					<th>Date</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($data['kicks'] as $entry){
					  $kick = $entry->getData();?>
						<tr>
					<td><?php echo $kick['server'];?></td>
					<td><?php echo $kick['reason'];?></td>
					<td><?php echo $kick['staff'];?></td>
					<td><?php echo $kick['date'];?></td>
				</tr>
					<?php } ?>
				</tbody>
		</table>
	</div>
		<?php }?>
	</div>
	
	
<!-- Warning and comment list -->
<div class="panel <?php if(empty($data['comments'])) {echo "panel-info";} else {echo "panel-warning";}?>">
	<?php if(empty($data['comments'])) {?>
	<div class="panel-heading clearfix">
		<h4 class="panel-title pull-left" style="padding-top: 7.5px;"><strong>Warning and comment list</strong> - Nobody has warned this player!</h4>
	</div>
</div>
		<?php }else{?>
	<div class="panel-heading clearfix">
		<h4 class="panel-title pull-left" style="padding-top: 7.5px;">
			<a href="#" onclick="deployPanel('comment_table');">
				<span class="fa fa-chevron-down"></span>
				<strong>Warning and comment list</strong>
			</a>
		</h4>
	</div>
	<div class="hidden" id="comment_table">
		<table class="table">
			<thead>
				<tr class="default">
					<th>Type</th>
					<th>Reason</th>
					<th>Staff</th>
					<th>Date</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($data['comments'] as $entry){
				$comment = $entry->getData();?>
				<tr class="warning">
					<td><?php echo $comment['type'] == "NOTE" ? "comment" : "warning";?></td>
					<td><?php echo $comment['reason'];?></td>
					<td><?php echo $comment['staff'];?></td>
					<td><?php echo $comment['date'];?></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
	<?php }?>
	</div>
</div>


<!-- At this point, there are all the modals use by this page -->
<!-- Ban modal-->
<div class="modal fade" id="ban_modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
				</button>
				<h4 class="modal-title" id="ban_modal_title">Ban <?php echo $data['player'];?></h4>
			</div>
			<form class="form-horizontal ajax-form" role="form" method="post" action="index.php?p=ban&action=ban">
				<div class="modal-body row">
					<div class="col-md-1"></div>
					<div class="col-md-10">
						<div class="form-group">
							<label for="ban_server" class="control-label">Ban server</label>
							<select class="form-control col-md-3" id="ban-server-chooser">
								<option>Global ban</option>
								<option>Specific server</option>
							</select>
						</div>
						<div class="form-group">
							<input type="hidden" class="form-control col-md-6" id="ban-server" name="ban-server" placeholder="Ban server" value="(global)">
						</div>
						<div class="form-group">
							<label for="ban_expiration" class="control-label">Ban expiration</label>
							<select class="form-control" id="ban-expiration-chooser">
								<option>Definitive ban</option>
								<option>Temporary ban</option>
							</select>
						</div>
						<div class="form-group">
							<input type="hidden" class="form-control timepicker" id="ban-expiration" name="ban-expiration" placeholder="Ban expiration">
						</div>
						<div class="form-group">
							<label for="ban_reason" class="col-sm-3 control-label">Ban reason</label>
							<div class="col-sm-9">
								<textarea class="form-control" id="ban-reason" name="ban-reason" placeholder="Ban reason"></textarea>
							</div>
						</div>
						<input type="hidden" value="<?php echo $data['player'];?>" name="player">
					</div>
				</div>
				<div class="modal-footer">
					<p id="request-status"></p>
					<button type="submit" class="btn btn-danger">Ban</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Mute modal-->
<div class="modal fade" id="mute_modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
				</button>
				<h4 class="modal-title" id="mute_modal_title">Mute <?php echo $data['player'];?></h4>
			</div>
			<form class="form-horizontal ajax-form" role="form" method="post" action="index.php?p=mute&action=mute">
			<div class="modal-body row">
				<div class="col-md-1"></div>
				<div class="col-md-10">
					<div class="form-group">
						<label for="mute_server" class="control-label">Mute server</label>
						<select class="form-control col-md-3" id="mute-server-chooser">
							<option>Global mute</option>
							<option>Specific server</option>
						</select>
					</div>
					<div class="form-group">
						<input type="hidden" class="form-control col-md-6" id="mute-server" name="mute-server" placeholder="Mute server" value="(global)">
					</div>
					<div class="form-group">
						<label for="mute_expiration" class="control-label">Mute expiration</label>
						<select class="form-control" id="mute-expiration-chooser">
							<option>Permanent mute</option>
							<option>Temporary mute</option>
						</select>
					</div>
					<div class="form-group">
						<input type="hidden" class="form-control timepicker" id="mute-expiration" name="mute-expiration" placeholder="Mute expiration">
					</div>
					<div class="form-group">
						<label for="mute_reason" class="col-sm-3 control-label">Mute reason</label>
						<div class="col-sm-9">
							<textarea class="form-control" id="mute-reason" name="mute-reason" placeholder="Mute reason"></textarea>
						</div>
					</div>
					<input type="hidden" value="<?php echo $data['player'];?>" name="player">
				</div>
			</div>
			<div class="modal-footer">
				<p id="request-status"></p>
				<button type="submit" class="btn btn-danger">Mute</button>
			</div>
			</form>
		</div>
	</div>
</div>


<!-- Unban modal-->
<div class="modal fade" id="unban_modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
				</button>
				<h4 class="modal-title" id="unban_modal_title">Unban <?php echo $data['player'];?></h4>
			</div>
			<form class="form-horizontal ajax-form" role="form" method="post" action="index.php?p=ban&action=unban">
			<div class="modal-body">
				<div class="form-group">
					<label for="unban_reason" class="col-sm-3 control-label">Unban
						reason</label>
					<div class="col-sm-9">
						<textarea class="form-control" id="unban_reason"
							name="unban_reason" placeholder="Unban reason"></textarea>
					</div>
				</div>
				<input type="hidden" value="-1" id="ban_id" name="ban_id">
			</div>
			<div class="modal-footer">
				<p id="request-status"></p>
				<button type="submit" class="btn btn-success">Unban</button>
			</div>
			</form>
		</div>
	</div>
</div>

<!-- Unmute modal -->
<div class="modal fade" id="unmute_modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
				</button>
				<h4 class="modal-title" id="unmute_modal_title">Unmute <?php echo $data['player'];?></h4>
			</div>
			<form class="form-horizontal ajax-form" role="form" method="post" action="index.php?p=mute&action=unmute">
			<div class="modal-body">
				<div class="form-group">
					<label for="unmute_reason" class="col-sm-3 control-label">Unmute
						reason</label>
					<div class="col-sm-9">
						<textarea class="form-control" id="unmute_reason"
							name="unmute_reason" placeholder="Unmute reason"></textarea>
					</div>
				</div>
				<input type="hidden" value="-1" id="mute_id" name="mute_id">
			</div>
			<div class="modal-footer">
				<p id="request-status"></p>
				<button type="submit" class="btn btn-success">Unmute</button>
			</div>
			</form>
		</div>
	</div>
</div>
