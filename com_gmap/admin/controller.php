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

jimport('joomla.application.component.controller');

class gmapController extends JControllerLegacy {
 
    protected $default_view = 'maps';
    
    function __construct($default = array()) {
        parent::__construct($default);
        
    }   
    
    public function display($cachable = false, $urlparams = false) {
        parent::display();

        return $this;
        
    }
}
