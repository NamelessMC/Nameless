<script type="text/javascript" src="public/js/adminaccount.js"></script>
<div class="well">
	<h2 style="text-align: center">Manage accounts</h2>
	<br>

	<h4>Accounts list :</h4>
	<table class="table">
		<thead>
			<tr>
				<th>Username</th>
				<th colspan="2">Action</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($data['users'] as $userData){?>
			<tr>
				<td><?php echo $userData['username'];?></td>
				<td>
					<form class="ajax-form" action="index.php?p=admin&action=togglesu" method="post">
						<input type="hidden" name="user" value="<?php echo $userData['username'];?>">
						<?php if($userData['superuser']){?>
							<input type="submit" class="btn btn-sm btn-danger" value="Remove SuperUser rights">
						<?php }else{?>
							<input type="submit" class="btn btn-sm btn-success" value="Give SuperUser rights">
						<?php }?>
					</form>
				</td>
				<td>
					<form class="ajax-form" action="index.php?p=admin&action=deleteaccount" method="post">
						<input type="hidden" name="user" value="<?php echo $userData['username'];?>">
						<input type="submit" class="btn btn-sm btn-warning" value="Delete this account">
					</form>
				</td>
			</tr>
			<?php }?>
		</tbody>
	</table>
	<br>

	<h4>Create an account :</h4>
	<form class="form-horizontal ajax-form" role="form" method="post" autocomplete="off" action="index.php?p=admin&action=createaccount">
		<div class="form-group">
			<div class="col-sm-3"></div>
			<label for="username" class="col-sm-1 control-label">Username</label>
			<div class="col-sm-4">
				<input type="text" class="form-control" id="username" name="user"
					placeholder="Enter username" autocomplete="off">
			</div>
			<div class="col-sm-3"></div>
		</div>
		<div class="form-group">
			<div class="col-sm-3"></div>
			<label for="password" class="col-sm-1 control-label">Password</label>
			<div class="col-sm-4">
				<input type="password" class="form-control" id="password"
					name="password" placeholder="Password" autocomplete="off">
			</div>
			<div class="col-sm-3"></div>
		</div>
		<div class="row">
			<div class="col-sm-5"></div>
			<button type="submit" class="btn btn-bat">Create account</button>
		</div>
	</form>
</div>