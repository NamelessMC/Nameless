<form class="form-inline">
<h1 style="text-align: center;">
<span>Comment list</span> - Sorted by
<select class="form-control selectSortBy">
	<option value="player" <?php if($this->getSortingColumn() == "player")
		echo"selected='selected'"; ?>>Player</option>
	<option value="server" <?php if($this->getSortingColumn() == "server")
		echo"selected='selected'"; ?>>Server</option>
	<option value="reason" <?php if($this->getSortingColumn() == "reason")
		echo"selected='selected'"; ?>>Reason</option>
	<option value="staff" <?php if($this->getSortingColumn() == "staff")
		echo"selected='selected'"; ?>>User Who Commented</option>
	<option value="date" <?php if($this->getSortingColumn() == "date")
		echo"selected='selected'"; ?>>Date</option>
</select>
</h1>
</form>

<div class="panel panel-default">
	<table class="table table-themed">
		<thead>
			<tr>
				<th>Player</th>
				<th>Reason</th>
				<th>Staff</th>
				<th>Date</th>
				<th>Type</th>
			</tr>
		</thead>
		<tbody>
		<?php 
		if (empty($data)) {echo "<tr><td colspan='100'>There are no comments.</td></tr>";}
		else{
		foreach ($data as $entry){
		$data = $entry->getData();
		?>
			<tr class="<?php echo $data['type'] == "WARNING" ? "warning" : "info";?>">
				<td><?php echo $data['headImg'] . $data['player'];?></td>
				<td><?php echo $data['reason'];?></td>
				<td><?php echo $data['staff'];?></td>
				<td><?php echo $data['date'];?></td>
				<td><?php echo $data['type'] == "WARNING" ? Message::commentTypeWarning : Message::commentTypeNote;?></td>
			</tr>
		<?php }}
		?>
		</tbody>
	</table>
</div>