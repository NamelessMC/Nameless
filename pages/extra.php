<?php 
/* 
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

require('core/includes/htmlpurifier/HTMLPurifier.standalone.php'); // HTML Purifier
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Samerton">
	<?php if(isset($custom_meta)){ echo $custom_meta; } ?>

    <title><?php echo $sitename; ?> &bull; <?php echo htmlspecialchars($page_title); ?></title>
	
	<?php
	// Generate header and navbar content
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
	
	<br /><br />
	<div class="container">
	  <?php 
	    // Initialise HTML Purifier
	    $config = HTMLPurifier_Config::createDefault();
	    $config->set('HTML.Doctype', 'XHTML 1.0 Transitional');
	    $config->set('URI.DisableExternalResources', false);
	    $config->set('HTML.Allowed', 'u,p,b,i,a,small,blockquote,span[style],span[class],p,strong,em,li,ul,ol,div[align],br,img');
	    $config->set('CSS.AllowedProperties', array('text-align', 'float', 'color','background-color', 'background', 'font-size', 'font-family', 'text-decoration', 'font-weight', 'font-style', 'font-size'));
		$config->set('HTML.AllowedAttributes', 'target, href, src, height, width, alt, class, *.style');
		$config->set('Attr.AllowedFrameTargets', array('_blank', '_self', '_parent', '_top'));
	    $config->set('HTML.SafeIframe', true);
	    $config->set('URI.SafeIframeRegexp', '%^(https?:)?//(www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/)%');
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