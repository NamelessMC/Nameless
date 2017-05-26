Helpful PHP:

Get username: <?php echo $this->getUsername();?>

Check if user is admin: <?php if($this->isSU()){?> INPUT CONTENT FOR ADMIN EYES ONLY HERE Then end admin content with: <?php }?>
OR instead of ending ALL content, you can use this: <?php }else{?> instead of this <?php }?> , input content for non-admins, and THEN put <?php }?> to end ALL content.
This is helpful to stop an error saying that, wait a minute, this isn't an admin... HALP!

Sign Out: <a href="#" onclick="logout();">Sign Out</a>

Accounts Button: <?php if($this->isSU()){?><li><a href="index.php?p=admin&action=manageaccounts">Accounts</a></li><?php }?>

Check Page Name And If It's Equal To x Then Do Something:
<?php if(get_class($this) == "home"){echo "active";}?>
Reads the ?p=PAGENAME

Get network name content from messages.php: <?php echo Message::network;?>

Put "danger-bat" if player is checked to be currently banned: <?php echo $ban['state'] ? "danger-bat" : "";?>

Put "danger-bat" if player is checked to be currently muted: <?php echo $mute['state'] ? "danger-bat" : "";?>

Check if user is regular admin: <?php if($this->isAdmin()){//Do stuff} ?>

Check if user is Superuser admin: <?php if($this->isSU()){//Do stuff} ?>
This admin has higher status & more rights than regular admin. This admin can manage regular admin accounts. :O :D