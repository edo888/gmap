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

class GMapHelper {

    public function render_html() { // TODO
        $db = JFactory::getDBO();
        $query = 'select * from #__gmap_data where id = ' . $this->id;
        $db->setQuery($query);
        $row = $db->loadObject();

        if(empty($row->id))
            return 'Cannot load map data!';

        $html = '<div id="gmap_' . $this->id . '" style="height:' . $row->height . 'px;width:' . $row->width . 'px;"></div>';

        $row->center = json_decode($row->center);

        ob_start();
        ?>
        <script type="text/javascript">
        function gmap_<?php echo $this->id; ?>_initialize() {
            var map = new google.maps.Map(document.getElementById('gmap_<?php echo $this->id; ?>'), {
                zoom: <?php echo $row->zoom; ?>,
                center: new google.maps.LatLng(<?php echo $row->center[0]; ?>, <?php echo $row->center[1]; ?>),
                mapTypeId: google.maps.MapTypeId.<?php echo $row->mapTypeId; ?>,
                disableDefaultUI: false,
                streetViewControl: false,
                scaleControl: true,
                rotateControl: true,
                panControl: true,
                overviewMapControl: true,
                mapTypeControl: true,
                zoomControl: true
            });

            // load shapes
            var gmap_data = GMAP_IO.OUT(<?php echo $row->data; ?>, map);
            for(var i = 0; i < gmap_data.length; i++) {
                if(gmap_data[i].type == 'marker') {
                    if(gmap_data[i].contentHTML != '') {
                        gmap_data[i].infoWindow = new google.maps.InfoWindow({content: gmap_data[i].contentHTML});
                        (function(map,marker) {google.maps.event.addListener(marker, 'click', function() {marker.infoWindow.open(map, marker);})})(map, gmap_data[i]);
                    }
                }
            }
        }

        google.maps.event.addDomListener(window, 'load', gmap_<?php echo $this->id; ?>_initialize);
        </script>
        <?php
        $html = ob_get_clean() . $html;

        return $html;
    }
}
