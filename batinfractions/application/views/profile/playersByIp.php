<div class="container jumbotron">
<h2 style="text-align: center; color: navy;">Player search by ip :</h2>
<p>The following players share the same ip (<span class="text-success"><?php echo $data['ip'];?></span>): </p>
<ul>
	<?php foreach($data['players'] as $player){
		echo "<li><a href='index.php?p=profile&player=" . $player . "'>".$player."</li>";
	}?>
</ul>
</div>