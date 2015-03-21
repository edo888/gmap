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

// Import Joomla! libraries
jimport('joomla.application.component.view');

class GmapViewMap extends JViewLegacy {
    protected $form;
    protected $item;
    protected $state;

    /**
     * Display the view
     */
    public function display($tpl = null) {
        // Initialiase variables.

        $this->addToolbar();
        parent::display($tpl);
    }

    protected function addToolbar() {
        JRequest::setVar('hidemainmenu', true);

        $isNew      = ((int)$_REQUEST['id'] == 0);
        $text = $isNew ? JText::_('New') : JText::_('Edit');

        JToolBarHelper::title(JText::_('Map').': <small><small>[ ' . $text . ' ]</small></small>', 'manage.png');

        if ($isNew)  {
            JToolBarHelper::apply('map.apply');
            JToolBarHelper::save('map.save');
            JToolBarHelper::cancel('map.cancel');
        } else {
            JToolBarHelper::apply('map.apply');
            JToolBarHelper::save('map.save');
            JToolBarHelper::cancel('map.cancel','JTOOLBAR_CLOSE');
        }
    }

}
