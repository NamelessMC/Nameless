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
			</ul>
		</div>
	</div>
	<br>
	<!-- Ban list part -->
	<div class="panel <?php if(empty($data['bans'])) {echo "panel-info";} else {echo "panel-warning";}?>">
		<?php if(empty($data['bans'])) {?>
		<div class="panel-heading">
			<h4 class="panel-title"><strong>Ban list</strong> - This player has never been banned!</h4>
		</div>
		<?php }else{?>
		<div class="panel-heading">
			<h4 class="panel-title">
				<a href="#" onclick="deployPanel('ban_table');">
					<span class="fa fa-chevron-down"></span>
					<strong>Ban list</strong>
				</a>
			</h4>
		</div>
		<div class="hidden" id="ban_table">
			<table class="table">
				<thead>
					<tr class ="default">
						<th>Server</th>
						<th>Reason</th>
						<th>Staff</th>
						<th>Date</th>
						<th>State</th>
						<th>Unban date</th>
						<th>Unban staff</th>
						<th>Unban reason</th>
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
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
		<?php }?>
	</div>
	<!-- Mute list part -->
	<div class="panel <?php if(empty($data['mutes'])) {echo "panel-info";} else {echo "panel-warning";}?>">
		<?php if(empty($data['mutes'])) {?>
		<div class="panel-heading">
			<h4 class="panel-title"><strong>Mute list</strong> - This player has never been muted!</h4>
		</div>
		<?php }else{?>
		<div class="panel-heading">
			<h4 class="panel-title">
				<a href="#" onclick="deployPanel('mute_table');">
					<span class="fa fa-chevron-down"></span>
					<strong>Mute list</strong>
				</a>
			</h4>
		</div>
		<div class="hidden" id="mute_table">
			<table class="table">
				<thead>
					<tr class ="default">
						<th>Server</th>
						<th>Reason</th>
						<th>Staff</th>
						<th>Date</th>
						<th>State</th>
						<th>Unmute date</th>
						<th>Unmute staff</th>
						<th>Unmute reason</th>
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
						<td class="<?php echo $mute['state'] ? "danger-bat" : "";?>">
							<?php echo $mute['state'] 
							? Message::state_ACTIVE : Message::state_ENDED;?>
						</td>
						<td><?php echo $mute['unmute_date'];?></td>
						<td><?php echo $mute['unmute_staff'];?></td>
						<td><?php echo $mute['unmute_reason'];?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
		<?php }?>
	</div>
	<!-- Kick list part -->
	<div class="panel <?php if(empty($data['kicks'])) {echo "panel-info";} else {echo "panel-warning";}?>">
		<?php if(empty($data['kicks'])) {?>
		<div class="panel-heading">
			<h4 class="panel-title"><strong>Kick list</strong> - This player has never been kicked!</h4>
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
					<tr class ="default">
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
	<!-- Warning list part - Comment aren't displayed for non-auth user -->
	<div class="panel <?php if(empty($data['comments'])) {echo "panel-info";} else {echo "panel-warning";}?>">
		<?php if(empty($data['comments'])) {?>
		<div class="panel-heading">
			<h4 class="panel-title"><strong>Warning list</strong> - Nobody has warned this player!</h4>
		</div>
		<?php }else{?>
		<div class="panel-heading">
			<h4 class="panel-title">
				<a href="#" onclick="deployPanel('comment_table');">
					<span class="fa fa-chevron-down"></span>
					<strong>Warning list</strong>
				</a>
			</h4>
		</div>
		<div class="hidden" id="comment_table">
			<table class="table">
				<thead>
					<tr class ="default">
						<th>Reason</th>
						<th>Staff</th>
						<th>Date</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($data['comments'] as $entry){
					$comment = $entry->getData();
					if($comment['type'] == "NOTE") {continue;}?>
					<tr class="warning">
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
