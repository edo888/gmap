<?php
/**
* @version   $Id$
* @package   GMap
* @copyright Copyright (C) 2014 - 2015 2GLux. All rights reserved.
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

/**
 * GMap plugin
 *
 */
class plgSystemGMap extends JPlugin {

    function __construct( &$subject ) {
        parent::__construct( $subject );
    }

    function onAfterRender() {
        if(JFactory::getApplication()->isAdmin())
            return;

        $body = JResponse::getBody();
        if(preg_match('/(\[gmap id="([0-9]+)"\])/s', $body)) {
            $scripts = '';
            $scripts .= '<script src="'.Juri::base().'components/com_gmap/assets/js/io.lib.js" type="text/javascript"></script>'."\n";
            $scripts .= '<script src="https://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>'."\n";

            $body = str_replace('</head>', $scripts . '</head>', $body);
        }

        $body = preg_replace_callback('/(\[gmap id="([0-9]+)"\])/s', array($this, 'render_gmap'), $body);
        JResponse::setBody($body);
    }

    function render_gmap($m) {
        $gmap_id = (int) $m[2];

        require_once JPATH_ADMINISTRATOR . '/components/com_gmap/helpers/helper.php';

        $map = new GMapHelper;
        $map->id = $gmap_id;

        return $map->render_html();
    }
}