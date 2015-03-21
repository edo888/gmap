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

$id = (int)$_REQUEST['id'];

$db = JFactory::getDBO();
$query = 'select * from #__gmap_data where id = ' . $id;
$db->setQuery($query);
$row = $db->loadObject();


$document = JFactory::getDocument();
$document->addScript(Juri::base().'components/com_gmap/assets/js/io.lib.js');
$document->addScript('http://maps.google.com/maps/api/js?sensor=false&libraries=drawing');
$document->addStyleDeclaration('#panel {width:200px;font-family:Arial, sans-serif;font-size:13px;margin:10px;}
#gmap {width:1000px;height:600px;}
#color-palette {clear:both;}
.color-button {width:14px;height:14px;font-size:0;margin:2px;float:left;cursor:pointer;}
#delete-button {margin-top:5px;}
');

editMap($row);

function editMap($row) {
    jimport('joomla.filter.output');
    JFilterOutput::objectHTMLSafe($row, ENT_QUOTES);

    JHTML::_('behavior.modal');

    if(empty($row->center))
        $row->center = '[40.169997,44.52]';
    $row->center = json_decode($row->center);
    if(empty($row->zoom))
        $row->zoom = 10;

    //echo '<pre>', print_r($row, true), '</pre>';
    ?>
        <script language="javascript" type="text/javascript">
        Joomla.submitbutton = function(pressbutton) {
            var form = document.adminForm;
            if(pressbutton == 'map.cancel') {
                submitform(pressbutton);
                return;
            }

            // validation
            if(form.name && form.name.value == "")
                alert('Map name is required');
            else {
                document.getElementById('gmap_data').value = JSON.stringify(IO.IN(gmap_data, false));
                document.getElementById('gmap_center').value = JSON.stringify([drawingManager.map.getCenter().lat(), drawingManager.map.getCenter().lng()]);
                document.getElementById('gmap_zoom').value = drawingManager.map.getZoom();
                submitform(pressbutton);
            }

        }
        </script>

        <script type="text/javascript">
        var drawingManager;
        var selectedShape;
        var colors = ['#1E90FF', '#FF1493', '#32CD32', '#FF8C00', '#4B0082'];
        var selectedColor;
        var colorButtons = {};
        var gmap_data = [];

        function clearSelection() {
            if (selectedShape) {
                selectedShape.set((selectedShape.type === google.maps.drawing.OverlayType.MARKER) ? 'draggable' : 'editable', false);
                selectedShape = null;
            }
        }

        function setSelection(shape) {
            clearSelection();
            selectedShape = shape;
            selectedShape.set((selectedShape.type === google.maps.drawing.OverlayType.MARKER) ? 'draggable' : 'editable', true);
            selectColor(selectedShape.get('fillColor') || selectedShape.get('strokeColor'));
        }

        function deleteSelectedShape() {
            if (selectedShape) {
                selectedShape.setMap(null);
            }
        }

        function selectColor(color) {
            selectedColor = color;
            for (var i = 0; i < colors.length; ++i) {
                var currColor = colors[i];
                colorButtons[currColor].style.border = currColor == color ? '2px solid #789' :'2px solid #fff';
            }

            // Retrieves the current options from the drawing manager and replaces the
            // stroke or fill color as appropriate.
            var polylineOptions = drawingManager.get('polylineOptions');
            polylineOptions.strokeColor = color;
            drawingManager.set('polylineOptions', polylineOptions);

            var rectangleOptions = drawingManager.get('rectangleOptions');
            rectangleOptions.fillColor = color;
            drawingManager.set('rectangleOptions', rectangleOptions);

            var circleOptions = drawingManager.get('circleOptions');
            circleOptions.fillColor = color;
            drawingManager.set('circleOptions', circleOptions);

            var polygonOptions = drawingManager.get('polygonOptions');
            polygonOptions.fillColor = color;
            drawingManager.set('polygonOptions', polygonOptions);
        }

        function setSelectedShapeColor(color) {
            if (selectedShape) {
                if (selectedShape.type == google.maps.drawing.OverlayType.POLYLINE) {
                    selectedShape.set('strokeColor', color);
                } else {
                    selectedShape.set('fillColor', color);
                }
            }
        }

        function makeColorButton(color) {
            var button = document.createElement('span');
            button.className = 'color-button';
            button.style.backgroundColor = color;
            google.maps.event.addDomListener(button, 'click', function() {
                selectColor(color);
                setSelectedShapeColor(color);
            });

            return button;
        }

        function buildColorPalette() {
            var colorPalette = document.getElementById('color-palette');
            for (var i = 0; i < colors.length; ++i) {
                var currColor = colors[i];
                var colorButton = makeColorButton(currColor);
                colorPalette.appendChild(colorButton);
                colorButtons[currColor] = colorButton;
            }
            selectColor(colors[0]);
        }

        function initialize() {
            var map = new google.maps.Map(document.getElementById('gmap'), {
                zoom: <?php echo $row->zoom; ?>,
                center: new google.maps.LatLng(<?php echo $row->center[0]; ?>, <?php echo $row->center[1]; ?>),
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                disableDefaultUI: true,
                zoomControl: true
            });

            var polyOptions = {
                strokeWeight: 0,
                fillOpacity: 0.45,
                editable: true
            };

            // Creates a drawing manager attached to the map that allows the user to draw
            // markers, lines, and shapes.
            drawingManager = new google.maps.drawing.DrawingManager({
                drawingMode: google.maps.drawing.OverlayType.POLYGON,
                markerOptions: {
                    draggable: true
                },
                polylineOptions: {
                    editable: true
                },
                rectangleOptions: polyOptions,
                circleOptions: polyOptions,
                polygonOptions: polyOptions,
                map: map
            });

            google.maps.event.addListener(drawingManager, 'overlaycomplete', function(e) {
                if (e.type != google.maps.drawing.OverlayType.MARKER) {
                    // Switch back to non-drawing mode after drawing a shape.
                    drawingManager.setDrawingMode(null);
                }

                // Add an event listener that selects the newly-drawn shape when the user mouses down on it.
                var newShape = e.overlay;
                newShape.type = e.type;
                google.maps.event.addListener(newShape, 'click', function() {
                    setSelection(newShape);
                });
                setSelection(newShape);

                gmap_data.push(newShape);
            });

            // Clear the current selection when the drawing mode is changed, or when the map is clicked.
            google.maps.event.addListener(drawingManager, 'drawingmode_changed', clearSelection);
            google.maps.event.addListener(map, 'click', clearSelection);
            google.maps.event.addDomListener(document.getElementById('delete-button'), 'click', deleteSelectedShape);

            buildColorPalette();

            // load shapes
            gmap_data = IO.OUT(JSON.parse(document.getElementById('gmap_data').value), map);
            for(var i = 0; i < gmap_data.length; i++) {
                setSelection(gmap_data[i]);
                (function(newShape) {google.maps.event.addListener(newShape, 'click', function() {setSelection(newShape);});})(gmap_data[i]);
            }
        }

        google.maps.event.addDomListener(window, 'load', initialize);
        </script>

        <form action="index.php" method="post" name="adminForm">

        <fieldset class="adminform">
            <legend><?php echo JText::_('Details'); ?></legend>

            <table class="admintable">
            <tr>
                <td width="200" class="key">
                    <label for="name">
                        <?php echo JText::_( 'Map Name' ); ?>
                    </label>
                </td>
                <td>
                    <?php echo '<input class="inputbox" type="text" name="name" id="name" size="60" value="', @JRequest::getVar('name', $row->name), '" />'; ?>
                </td>
            </tr>
            <?php /* <tr>
                <td class="key" valign="top">
                    <label for="path">
                        <?php echo JText::_( 'Description' ); ?>
                    </label>
                </td>
                <td>
                        <?php echo '<textmap name="description" id="description" cols="80" rows="5">', @JRequest::getVar('description', $row->description), '</textmap>'; ?>
                </td>
            </tr> */ ?>

            <tr>
                <td colspan="2" style="position:absolute;">
                    <div id="panel">
                        <div id="color-palette"></div>
                        <div>
                            <button id="delete-button">Delete Selected Shape</button>
                        </div>
                    </div>
                    <div id="gmap"></div>
                </td>
            </tr>
            </table>
        </fieldset>

        <div class="clr"></div>

        <input type="hidden" name="data" id="gmap_data" value="<?php echo @$row->data; ?>" />
        <input type="hidden" name="center" id="gmap_center" value="<?php echo @json_encode($row->center); ?>" />
        <input type="hidden" name="zoom" id="gmap_zoom" value="<?php echo @$row->zoom; ?>" />
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="view" value="map" />
        <input type="hidden" name="option" value="com_gmap" />
        <input type="hidden" name="id" value="<?php echo @$row->id; ?>" />
         <?php echo JHtml::_('form.token'); ?>
        </form>
    <?php
    }
?>