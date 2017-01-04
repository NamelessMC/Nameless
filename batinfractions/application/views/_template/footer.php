<?php if(isset($paginationView)){?>
<center><p><?php echo $paginationView;?></p></center>
<?php }?>
</div>
<div class="col-md-1"></div>
</div>
<!-- Please don't remove the link to the BAT page - it's the only link in the whole webinterface -->
<center>
	Powered by <a href="http://www.spigotmc.org/resources/bungee-admin-tools.444/">BungeeAdminTools</a>
	<?php if($this->isAdmin()) {include("admin/footerAddon.php");} ?>
</center>
</body>
</html>
