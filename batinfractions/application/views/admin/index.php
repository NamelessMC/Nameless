<div class="well">
	<h2 style="text-align: center">Bungee Admin Tools - Administration panel</h2>
	<p style="text-align: center">
		<?php if($this->isSU()){?>
			<!-- Is admin with SU rights -->
			Welcome <?php echo $this->getUsername();?>! As an administrator <strong>with the superuser rights</strong>, 
			you can manage punishment of the players <strong>and handle the admin accounts</strong>. More will come in the next version. Stay tuned!
			
			<?php }else{?>
			
			<!-- Not admin with SU rights -->
			Welcome <?php echo $this->getUsername();?>! As an administrator, you can manage punishment of the players.
			More will come in the next version. Stay tuned!
		<?php }?>
	</p>
</div>

<?php if($this->isSU()){ ?>

	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">Superuser Admin Actions</h3>
		</div>
		<div class="panel-body">
			<ul>
				<li><a href="index.php?p=admin&action=manageaccounts">Account Management</a></li>
			</ul>
		</div>
	</div>

	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">Admin Actions</h3>
		</div>
		<div class="panel-body">
			<ul>
				<li>
					<p>
						Visit the bans, mutes, kicks, or comments page & then click the head of a player in the list. Then you will be greeted by an admin page for that specific player.
					</p>
				</li>
			</ul>
		</div>
	</div>
	
<?php } else { ?>

	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">Admin Actions</h3>
		</div>
		<div class="panel-body">
			<ul>
				<li>
					<p>
						Visit the bans, mutes, kicks, or comments page & then click the head of a player in the list. Then you will be greeted by an admin page for that specific player
					</p>
				</li>
			</ul>
		</div>
	</div>

<?php } ?>