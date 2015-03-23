var GMAP_IO = {

    OUT: function(arr, map) {
        var shapes = [], shape;

        for(var i = 0; i < arr.length; i++) {
            shape = arr[i];
            tmp = {};

            switch(shape.type) {
                case 'CIRCLE':
                    tmp = new google.maps.Circle({
                        radius: shape.radius,
                        center: new google.maps.LatLng(shape.center[0], shape.center[1]),
                        map: map,
                        type: 'circle',
                        editable: false,
                        fillColor: shape.fillColor,
                        fillOpacity: shape.fillOpacity,
                        strokeColor: shape.strokeColor,
                        strokeOpacity: shape.strokeOpacity,
                        strokeWeight: shape.strokeWeight,
                        zIndex: shape.zIndex
                    });
                    break;
                case 'MARKER':
                    tmp = new google.maps.Marker({
                        position: new google.maps.LatLng(shape.position[0], shape.position[1]),
                        title: shape.title,
                        map: map,
                        type: 'marker',
                        draggable: false,
                        zIndex: shape.zIndex
                    });
                    tmp.contentHTML = shape.contentHTML;
                    break;
                case 'RECTANGLE':
                    tmp = new google.maps.Rectangle({
                        bounds: new google.maps.LatLngBounds(new google.maps.LatLng(shape.bounds.sw[0], shape.bounds.sw[1]), new google.maps.LatLng(shape.bounds.ne[0], shape.bounds.ne[1])),
                        map: map,
                        type: 'rectangle',
                        editable: false,
                        fillColor: shape.fillColor,
                        fillOpacity: shape.fillOpacity,
                        strokeColor: shape.strokeColor,
                        strokeOpacity: shape.strokeOpacity,
                        strokeWeight: shape.strokeWeight,
                        zIndex: shape.zIndex
                    });
                    break;
                case 'POLYLINE':
                    var path = [];
                    shape.path.forEach(function(p){path.push(new google.maps.LatLng(p[0], p[1]));});
                    tmp = new google.maps.Polyline({
                        path: path,
                        map: map,
                        type: 'polyline',
                        editable: false,
                        draggable: false,
                        strokeColor: shape.strokeColor,
                        strokeOpacity: shape.strokeOpacity,
                        strokeWeight: shape.strokeWeight,
                        zIndex: shape.zIndex
                    });
                    break;
                case 'POLYGON':
                    var path = [];
                    shape.path.forEach(function(p){path.push(new google.maps.LatLng(p[0], p[1]));});
                    tmp = new google.maps.Polygon({
                        path: path,
                        map: map,
                        type: 'polygon',
                        editable: false,
                        draggable: false,
                        fillColor: shape.fillColor,
                        fillOpacity: shape.fillOpacity,
                        strokeColor: shape.strokeColor,
                        strokeOpacity: shape.strokeOpacity,
                        strokeWeight: shape.strokeWeight,
                        zIndex: shape.zIndex
                    });
                    break;
            }

            shapes.push(tmp);
        }

        return shapes;
    }

}
