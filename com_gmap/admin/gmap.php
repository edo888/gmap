<?php
/**
 * Joomla! component com_gmap
 *
 * @author 2GLux
 * @package GMap
 * @license GNU/GPL
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// Initialize the controller
$controller = JControllerLegacy::getInstance('gmap');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
