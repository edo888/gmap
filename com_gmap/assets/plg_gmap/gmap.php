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
    
    function onAfterDispatch() {
        $app = JFactory::getApplication();
        
        if(!$app->isAdmin())
            return;

        // TODO            
    }
}