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

jimport('joomla.application.component.controllerform');

jimport('joomla.database.table');

class GmapTableMap extends JTable {

    /**
     * Constructor
     *
     * @param object Database connector object
     */
    function __construct() {
        $db   = JFactory::getDBO();
        parent::__construct('#__gmap_data', 'id', $db);
    }
}

class GmapControllerMap extends JControllerForm {

    function __construct($default = array()) {
        parent::__construct($default);

        $task = $_REQUEST['task'];
        $this->registerTask('add' , 'editMap');
        $this->registerTask('edit', 'editMap');
        $this->registerTask('save', 'saveMap');
        $this->registerTask('apply', 'saveMap');
        $this->registerTask('copy', 'copyMap');
        $this->registerTask('remove', 'removeMap');
        $this->registerTask('cancel', 'close');

    }

    function close() {
        $link = 'index.php?option=com_gmap&view=maps';
        $this->setRedirect($link, $msg);
    }

    function editMap() {
        $db = JFactory::getDBO();
        $id = JRequest::getInt('id', 0);

        $link = 'index.php?option=com_gmap&view=map&layout=edit';
        if($id != 0)
            $link .= '&id='.$id;

        $this->setRedirect($link, $msg);
    }

    function saveMap() {
        $db = JFactory::getDBO();
        $id = JRequest::getInt('id', 0);

        if($id == 0) { // going to insert new map
            // constructing the map object
            $map = new GmapTableMap;
            $map->set('name', JRequest::getVar('name'));
            $map->set('height', JRequest::getInt('height'));
            $map->set('width', JRequest::getInt('width'));
            $map->set('data', $_REQUEST['data']);
            $map->set('center', JRequest::getVar('center'));
            $map->set('zoom', JRequest::getVar('zoom'));
            $map->set('mapTypeId', JRequest::getVar('mapTypeId'));

            //$map->set('description', JRequest::getVar('description'));

            if (!$map->store()) {
                $mainframe = JFactory::getApplication();
                $mainframe->enqueueMessage(JText::_('Cannot save map data'), 'message');
                $mainframe->enqueueMessage($map->getError(), 'error');
                JRequest::setVar('task', 'editMap');
                return $this->execute('editMap');
            }

            $this->setRedirect('index.php?option=com_gmap&view=maps', JText::_('Map successfully created'));
        } else { // going to update map
            // constructing the map object
            $map = new GmapTableMap;
            $map->set('id', $id);
            $map->set('name', null);
            $map->set('height', null);
            $map->set('width', null);
            $map->set('data', null);
            $map->set('center', null);
            $map->set('zoom', null);
            $map->set('mapTypeId', null);
            //$map->set('description', null);
            $map->load();


            $map->set('name', JRequest::getVar('name'));
            $map->set('height', JRequest::getInt('height'));
            $map->set('width', JRequest::getInt('width'));
            $map->set('data', $_REQUEST['data']);
            $map->set('center', JRequest::getVar('center'));
            $map->set('zoom', JRequest::getVar('zoom'));
            $map->set('mapTypeId', JRequest::getVar('mapTypeId'));
            //$map->set('description', JRequest::getVar('description'));

            // storing updated data
            if (!$map->store()) {
                $mainframe = JFactory::getApplication();
                $mainframe->enqueueMessage(JText::_('Cannot save map data'), 'message');
                $mainframe->enqueueMessage($map->getError(), 'error');
                JRequest::setVar('task', 'editMap');
                return $this->execute('editMap');
            }


            if($_REQUEST['task'] == 'save')
                $this->setRedirect('index.php?option=com_gmap&view=maps', JText::_('Map data successfully saved'));
            elseif($_REQUEST['task'] == 'apply')
                $this->setRedirect('index.php?option=com_gmap&view=map&layout=edit&id='.$id, JText::_('Map data successfully saved'));
            else
                $this->setRedirect('index.php?option=com_gmap', JText::_('Unknown task'));
        }
    }

    function copyMap() {
        $db = JFactory::getDBO();
        $cid  = JRequest::getVar( 'cid', array(), '', 'array' );

        for($i = 0, $n = count($cid); $i < $n; $i++) {
            $query = "insert into #__gmap_data (id, name, data, center, zoom, mapTypeId, height, width) select null, concat(name, ' - Copy'), data, center, zoom, mapTypeId, height, width from #__gmap_data where id = $cid[$i]";
            $db->setQuery($query);
            $db->query();
        }

        $this->setRedirect('index.php?option=com_gmap&view=maps', JText::_('Map successfully copied'));
    }

    function removeMap() {
        $db   = JFactory::getDBO();
        $cid  = JRequest::getVar( 'cid', array(), '', 'array' );

        for($i = 0, $n = count($cid); $i < $n; $i++) {
            $query = "delete from #__gmap_data where id = $cid[$i]";
            $db->setQuery($query);
            $db->query();
        }

        $this->setRedirect('index.php?option=com_gmap&view=maps', JText::_('Map(s) successfully deleted'));

    }

}
