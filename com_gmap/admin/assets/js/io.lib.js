var IO = {

    IN: function(arr, encoded) {
        var shapes = [], shape;

        for(var i = 0; i < arr.length; i++) {
            shape = arr[i];
            tmp = {};
            tmp.type = shape.type.toUpperCase();

            switch(tmp.type) {
               case 'CIRCLE':
                  tmp.radius = shape.getRadius();
                  tmp.center = [shape.getCenter().lat(), shape.getCenter().lng()];
                  tmp.editable = true;
                  tmp.fillColor = shape.fillColor;
                  tmp.fillOpacity = shape.fillOpacity;
                  tmp.strokeColor = shape.strokeColor;
                  tmp.strokeOpacity = shape.strokeOpacity;
                  tmp.strokeWeight = shape.strokeWeight;
                  tmp.zIndex = shape.zIndex;
                  break;
               case 'MARKER':
                   tmp.position = [shape.getPosition().lat(), shape.getPosition().lng()];
                   tmp.draggable = true;
                   tmp.zIndex = shape.zIndex;
                   break;
               case 'RECTANGLE':
                   tmp.bounds = {ne: [shape.getBounds().getNorthEast().lat(), shape.getBounds().getNorthEast().lng()], sw: [shape.getBounds().getSouthWest().lat(), shape.getBounds().getSouthWest().lng()]};
                   tmp.editable = true;
                   tmp.fillColor = shape.fillColor;
                   tmp.fillOpacity = shape.fillOpacity;
                   tmp.strokeColor = shape.strokeColor;
                   tmp.strokeOpacity = shape.strokeOpacity;
                   tmp.strokeWeight = shape.strokeWeight;
                   tmp.zIndex = shape.zIndex;
                   break;
               case 'POLYLINE':
                   tmp.path = [];
                   shape.getPath().forEach(function(e,i){tmp.path.push([e.lat(), e.lng()])});
                   tmp.editable = true;
                   tmp.strokeColor = shape.strokeColor;
                   tmp.strokeOpacity = shape.strokeOpacity;
                   tmp.strokeWeight = shape.strokeWeight;
                   tmp.zIndex = shape.zIndex;
                   break;
               case 'POLYGON':
                   tmp.path = [];
                   shape.getPath().forEach(function(e,i){tmp.path.push([e.lat(), e.lng()])});
                   tmp.editable = true;
                   tmp.fillColor = shape.fillColor;
                   tmp.fillOpacity = shape.fillOpacity;
                   tmp.strokeColor = shape.strokeColor;
                   tmp.strokeOpacity = shape.strokeOpacity;
                   tmp.strokeWeight = shape.strokeWeight;
                   tmp.zIndex = shape.zIndex;
                   break;
            }

            shapes.push(tmp);
        }

        return shapes;
    },

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
                        editable: true,
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
                        map: map,
                        type: 'marker',
                        draggable: shape.draggable,
                        zIndex: shape.zIndex
                    });
                    break;
                case 'RECTANGLE':
                    tmp = new google.maps.Rectangle({
                        bounds: new google.maps.LatLngBounds(new google.maps.LatLng(shape.bounds.sw[0], shape.bounds.sw[1]), new google.maps.LatLng(shape.bounds.ne[0], shape.bounds.ne[1])),
                        map: map,
                        type: 'rectangle',
                        editable: true,
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
                        editable: true,
                        draggable: true,
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
                        editable: true,
                        draggable: true,
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
