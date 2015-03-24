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

$document = JFactory::getDocument();
$document->addScript(Juri::base().'components/com_gmap/assets/js/io.lib.js');
$document->addScript('https://maps.google.com/maps/api/js?sensor=false');

$gmap = new GMapHelper;
$gmap->id = $params->get('gmap_id', 1);
echo $gmap->render_html();
