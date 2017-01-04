<form class="form-inline">
<h1 style="text-align: center;">
<span>Kick list</span> - Sorted by
<select class="form-control selectSortBy">
<option value="player" <?php if($this->getSortingColumn() == "player")
	echo"selected='selected'"; ?>>Player</option>
	<option value="server" <?php if($this->getSortingColumn() == "server")
		echo"selected='selected'"; ?>>Server</option>
	<option value="reason" <?php if($this->getSortingColumn() == "reason")
		echo"selected='selected'"; ?>>Reason</option>
	<option value="staff" <?php if($this->getSortingColumn() == "staff")
		echo"selected='selected'"; ?>>User Who Kicked</option>
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
				<th>Server</th>
				<th>Reason</th>
				<th>Staff</th>
				<th>Date</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			if (empty($data)) {echo "<tr><td colspan='100'>There are no kicks.</td></tr>";}
			else{
			foreach ($data as $entry){
			$kick = $entry->getData();
			?>
			<tr>
				<td><?php echo $kick['headImg'] . $kick['player'];?></td>
				<td><?php echo $kick['server'];?></td>
				<td><?php echo $kick['reason'];?></td>
				<td><?php echo $kick['staff'];?></td>
				<td><?php echo $kick['date'];?></td>
			</tr>
			<?php }}
			?>
		</tbody>
	</table>
</div>