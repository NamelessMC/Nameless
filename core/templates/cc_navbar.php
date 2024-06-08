<?php
/**
 * User settings area navbar generation.
 *
 * @author Samerton
 * @license MIT
 * @version 2.2.0
 *
 * @var Navigation   $cc_nav
 * @var TemplateBase $template
 */
$template->getEngine()->addVariable('CC_NAV_LINKS', $cc_nav->returnNav());
