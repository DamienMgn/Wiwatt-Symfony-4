map = {

    params: null,

    mapResults: null,

    hoverMarker: null,

    init: function() {
        map.displayMap();
    },

    // Affichage carte page annonces

    displayMap: function() {
        if( $('#map-results').length > 0 ){
                
            map.params = app.extractUrlParams();
        
            map.mapResults = L.map('map-results').setView([map.params.latitude , map.params.longitude ], 12);
    
            L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map.mapResults);
    
            map.getAvailableMarker() ;
        }
    },   
    
    getAvailableMarker : function(){
    
        let $allCards = $('.carte') ;
    
        const $activeIcon = L.icon({
            iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-black.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            popupAnchor: [0, -40],
            shadowSize: [41, 41]
        });
    
        const $unactiveIcon = L.icon({
            iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
        });
    
        let $allMarkers = [];

        for ( let i = 0 ; i < $allCards.length ; i++ ){
    
            let $marker = L.marker( [ $($allCards[i]).attr('data-latitude'),  $($allCards[i]).attr('data-longitude')]).addTo(map.mapResults);
            
            let $popup = $marker.bindPopup($($allCards[i]).find('.image-container').html() + $($allCards[i]).find('.show-annonce').html());

            $allMarkers.push($marker);
    
            $($allCards[i]).mouseover(function() {
                map.onOver($unactiveIcon, $activeIcon, $allMarkers, $popup, i);
            })
    
            $($marker).mouseover(function() {
                map.onOver($unactiveIcon, $activeIcon, $allMarkers, $popup, i);
                $($allCards[i]).addClass('carte-active')
            })
    
            $($marker).mouseleave(function() {
                $($allCards[i]).removeClass('carte-active');
            })
        }
    },

    onOver: function($unactiveIcon, $activeIcon, $allMarkers, $popup, i) {
        if (map.hoverMarker !== null) {
            map.hoverMarker.setIcon($unactiveIcon);
            map.hoverMarker.setZIndexOffset(10);
        }
        $allMarkers[i].setIcon($activeIcon);
        $popup.openPopup();
        $allMarkers[i].setZIndexOffset(100);
    
        map.hoverMarker = $allMarkers[i];
    },

}

map.init();