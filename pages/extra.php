<?php 
/* 
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

require('core/includes/htmlpurifier/HTMLPurifier.standalone.php'); // HTML Purifier

// Can the user view the page?
if(!$user->canViewPage($page_id)){
	echo '<script>window.location.replace(\'/\');</script>';
	die();
}

// Redirect page?
if(isset($redirect_page)){
	if(!is_null($redirect_page) && $redirect_page != ''){
		echo '<script>window.location.replace(\'' . $redirect_page . '\');</script>';
		die();
	} else die();
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="<?php echo $sitename; ?>">
    <meta name="theme-color" content="#454545" />
	<?php if(isset($custom_meta)){ echo $custom_meta; } ?>
	
	<?php
	// Generate header and navbar content
	// Page title
	$title = htmlspecialchars($page_title);
	
	require('core/includes/template/generate.php');
	?>
	
	<!-- Custom style -->
	<style>
	html {
		overflow-y: scroll;
	}
	</style>
  </head>

  <body>
	<?php
	// Index page
	// Load navbar
	$smarty->display('styles/templates/' . $template . '/navbar.tpl');
	?>

	<div class="container">
	  <?php 
	    // Initialise HTML Purifier
	    $config = HTMLPurifier_Config::createDefault();
	    $config->set('HTML.Doctype', 'XHTML 1.0 Transitional');
	    $config->set('URI.DisableExternalResources', false);
	    $config->set('HTML.Allowed', 'u,p,b,i,a,small,blockquote,span[style],span[class],p,strong,em,li,ul,ol,div[align],br,img,div[well]');
	    $config->set('CSS.AllowedProperties', array('text-align', 'float', 'color','background-color', 'background', 'font-size', 'font-family', 'text-decoration', 'font-weight', 'font-style', 'font-size', 'border-style', 'border-width', 'height', 'width'));
		$config->set('HTML.AllowedAttributes', 'target, href, src, height, width, alt, class, *.style, frameborder');
		$config->set('Attr.AllowedFrameTargets', array('_blank', '_self', '_parent', '_top'));
	    $config->set('HTML.SafeIframe', true);
	    $config->set('URI.SafeIframeRegexp', '%%');
		$config->set('URI.AllowedSchemes', array('http' => true, 'https' => true, 'ts3server' => true));
	    $purifier = new HTMLPurifier($config);

		echo $purifier->purify(htmlspecialchars_decode($page_content));
	  ?>
    </div>
	<?php
	// Footer
	require('core/includes/template/footer.php');
	$smarty->display('styles/templates/' . $template . '/footer.tpl');
	
	// Scripts
	require('core/includes/template/scripts.php'); 
	?>
  </body>
</html>
