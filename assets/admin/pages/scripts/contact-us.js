var ContactUs = function () {

    return {
        //main function to initiate the module
        init: function () {
			var map;
			$(document).ready(function(){
			  map = new GMaps({
				div: '#map',
				lat: 50.865106,
				lng: 4.678781
			  });
			   var marker = map.addMarker({
		            lat: 50.865106,
					lng: 4.678781,
		            title: 'Libaro, Inc.',
		            infoWindow: {
		                content: "<b>Bert Clybouw - KU Leuven.</b> Celestijnenlaan 200A <br/> BE-3001 Leuven"
		            }
		        });

			   marker.infoWindow.open(map, marker);
			});
        }
    };

}();