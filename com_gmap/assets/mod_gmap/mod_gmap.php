<?php
/**
 * Joomla! module mod_gmap
 *
 * @author 2GLux
 * @package GMap
 * @license GNU/GPL
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

//include helper class
require_once JPATH_ADMINISTRATOR.'/components/com_gmap/helpers/helper.php';

$gmap = new GMapHelper;
$gmap->id = $params->get('gmap_id', 1);
$gmap->module_id = $module->id;
$gmap->type = 'module';
$gmap->width = $params->get('width', 500);
$gmap->height = $params->get('height', 300);

echo $gmap->render_html();