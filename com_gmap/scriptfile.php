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

class com_gmapInstallerScript {

    /**
     * method to install the component
     *
     * @return void
     */
    function install($parent) {
        // installing module
        $module_installer = new JInstaller;
        if(@$module_installer->install(dirname(__FILE__).DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'mod_gmap'))
            echo '<p>'.JText::_('COM_GMAP_MODULE_INSTALL_SUCCESS').'</p>';
        else
           echo '<p>'.JText::_('COM_GMAP_MODULE_INSTALL_FAILED').'</p>';
        
        // installing plugin
        $plugin_installer = new JInstaller;
        if($plugin_installer->install(dirname(__FILE__).DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.'plg_gmap'))
            echo '<p>'.JText::_('COM_GMAP_PLUGIN_INSTALL_SUCCESS').'</p>';
        else
            echo '<p>'.JText::_('COM_GMAP_PLUGIN_INSTALL_FAILED').'</p>';
        
        // enabling plugin
        $db = JFactory::getDBO();
        $db->setQuery('UPDATE #__extensions SET enabled = 1 WHERE element = "gmap" AND folder = "system"');
        $db->query();
    }

    /**
     * method to uninstall the component
     *
     * @return void
     */
    function uninstall($parent) {
        $db = JFactory::getDBO();
        
        $sql = 'SELECT `extension_id` AS id, `name`, `element`, `folder` FROM #__extensions WHERE `type` = "module" AND ( (`element` = "mod_gmap") ) ';
        $db->setQuery($sql);
        $gmap_module = $db->loadObject();
        $module_uninstaller = new JInstaller;
        if($module_uninstaller->uninstall('module', $gmap_module->id))
             echo '<p>'.JText::_('COM_GMAP_MODULE_UNINSTALL_SUCCESS').'</p>';
        else
            echo '<p>'.JText::_('COM_GMAP_MODULE_UNINSTALL_FAILED').'</p>';
        
        $db->setQuery("select extension_id from #__extensions where name = 'System - GMap' and type = 'plugin' and element = 'gmap'");
        $cis_plugin = $db->loadObject();
        $plugin_uninstaller = new JInstaller;
        if($plugin_uninstaller->uninstall('plugin', $cis_plugin->extension_id))
            echo '<p>'.JText::_('COM_GMAP_PLUGIN_UNINSTALL_SUCCESS').'</p>';
        else
            echo '<p>'.JText::_('COM_GMAP_PLUGIN_UNINSTALL_FAILED').'</p>';
    }

    /**
     * method to update the component
     *
     * @return void
     */
    function update($parent) {
        $this->install($parent);
    }

    /**
     * method to run before an install/update/uninstall method
     *
     * @return void
     */
    function preflight($type, $parent) {
        // nothing to do
    }

    /**
     * method to run after an install/update/uninstall method
     *
     * @return void
     */
    function postflight($type, $parent) {
        // nothing to do
    }
}