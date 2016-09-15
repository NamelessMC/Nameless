<?php
    // total page count calculation
    $pages = ((int) ceil($total / $rpp));

    // if it's an invalid page request
    if ($current < 1) {
        return;
    } elseif ($current > $pages) {
        return;
    }

    // if there are pages to be shown
    if ($pages > 1 || $alwaysShowPagination === true) {
?>
<ul class="<?php echo implode(' ', $classes); ?>">
<?php
        /**
         * Previous Link
         */

        // anchor classes and target
        $classes = array('copy', 'previous');
        $params = $get;
        $params[$key] = ($current - 1);
        $href = ($target) . '?' . http_build_query($params);
        $href = preg_replace(
            array('/=$/', '/=&/'),
            array('', '&'),
            $href
        );
        if ($current == 1) {
            $href = '#';
            array_push($classes, 'disabled');
        }
?>
    <li class="<?php echo implode(' ', $classes); ?>"><a href="<?php echo $href; ?>"><?php echo $previous; ?></a></li>
<?php
        /**
         * if this isn't a clean output for pagination (eg. show numerical
         * links)
         */
        if (!$clean) {

            /**
             * Calculates the number of leading page crumbs based on the minimum
             *     and maximum possible leading pages.
             */
            $max = min($pages, $crumbs);
            $limit = ((int) floor($max / 2));
            $leading = $limit;
            for ($x = 0; $x < $limit; ++$x) {
                if ($current === ($x + 1)) {
                    $leading = $x;
                    break;
                }
            }
            for ($x = $pages - $limit; $x < $pages; ++$x) {
                if ($current === ($x + 1)) {
                    $leading = $max - ($pages - $x);
                    break;
                }
            }

            // calculate trailing crumb count based on inverse of leading
            $trailing = $max - $leading - 1;

            // generate/render leading crumbs
            for ($x = 1; $x < $leading; ++$x) {

                // class/href setup
                $params = $get;
                $params[$key] = ($current + $x - $leading);
                $href = ($target) . '?' . http_build_query($params);
                $href = preg_replace(
                    array('/=$/', '/=&/'),
                    array('', '&'),
                    $href
                );
?>
    <li class="number"><a data-pagenumber="<?php echo ($current + $x - $leading); ?>" href="<?php echo $href; ?>"><?php echo ($current + $x - $leading); ?></a></li>
<?php
            }

            // print current page
?>
    <li class="number active"><a data-pagenumber="<?php echo $current; ?>" href="#"><?php echo $current; ?></a></li>
<?php
			// Prevent displaying any extra crumbs
			if($current != $max){
				// generate/render trailing crumbs
				for ($x = 0; $x < $trailing; ++$x) {

					// class/href setup
					$params = $get;
					$params[$key] = ($current + $x + 1);
					$href = ($target) . '?' . http_build_query($params);
					$href = preg_replace(
						array('/=$/', '/=&/'),
						array('', '&'),
						$href
					);
?>
    <li class="number"><a data-pagenumber="<?php echo ($current + $x + 1); ?>" href="<?php echo $href; ?>"><?php echo ($current + $x + 1); ?></a></li>
<?php
				}
			}
        }

        /**
         * Next Link
         */

        // anchor classes and target
        $classes = array('copy', 'next');
        $params = $get;
        $params[$key] = ($current + 1);
        $href = ($target) . '?' . http_build_query($params);
        $href = preg_replace(
            array('/=$/', '/=&/'),
            array('', '&'),
            $href
        );
        if ($current == $pages) {
            $href = '#';
            array_push($classes, 'disabled');
        }
?>
    <li class="<?php echo implode(' ', $classes); ?>"><a href="<?php echo $href; ?>"><?php echo $next; ?></a></li>
</ul>
<?php
    }
