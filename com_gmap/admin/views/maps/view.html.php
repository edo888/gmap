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

class GmapViewMaps extends JViewLegacy {

    protected $items;
    protected $pagination;
    protected $state;

    /**
     * Display the view
     *
     * @return  void
     */
    public function display($tpl = null) {

        $this->items        = $this->get('Items');
        $this->pagination   = $this->get('Pagination');
        $this->state        = $this->get('State');

        $this->addToolbar();
        //$this->sidebar = JHtmlSidebar::render();
        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @since   1.6
     */
    protected function addToolbar() {
        $mainframe = JFactory::getApplication();

        JToolBarHelper::addNew('map.add');
        JToolBarHelper::editList('map.edit');
        JToolBarHelper::custom('map.copy', 'copy', 'copy', 'Copy', true);
        JToolBarHelper::deleteList('', 'map.remove');
        JToolBarHelper::preferences('com_gmap', '550', '570', 'JOptions');
        //TODO: JToolBarHelper::help('screen.gmap.maps', true);
        JToolBarHelper::divider();
    }

    /**
     * Returns an array of fields the table can be sorted by
     *
     * @return  array  Array containing the field name to sort by as the key and display text as value
     *
     * @since   3.0
     */
    protected function getSortFields() {
        return array(
            'm.name' => JText::_('Name'),
            'm.id' => JText::_('id')
        );
    }

}
