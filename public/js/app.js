let app = {
    
    latitude : 0 ,

    longitude : 0 ,

    currentResult : null ,

    leasingIndexMap : null ,

    n : 1 ,

    map : null ,

    hoverMarker : 0,

    init: function(){
        console.log('WiWatt start') ;

        $('.toast').addClass('d-none');

        if( $('.toast-body div').length > 0){

             $('.toast').removeClass('d-none').addClass('show');
        }

        $('[data-toggle="popover-phone"]').popover() ;
        $('[data-toggle="popover-email"]').popover() ;
       

        $('#close-toast').click(function(){

             $('.toast').removeClass('show').addClass('d-none');

        }) ;

        $('.toast').draggable() ;



        
       
         
        $("#edit_user_avatar").change(function(){
            $('[name="edit_user"]').submit() ;
        });

        $('.avatar_picture_page_my-information').click(function(){
            
            $('#edit_user_avatar').click() ;

        }) ;


        $('#avatar-click').click(function(){
            
            $('#edit_user_avatar').click() ;

        }) ;

        $('.add_picture').click(function(){

            $('#vehicle_picture').click() ;
        }) ;

        $('#vehicle_picture').change(function(e){

           $file =  $('<p>').text($(e.target).val()).addClass('text-secondary text-center font-italic mt-3 text-small ml-3').insertAfter('.add_picture') ;

            $('<i>').addClass('fa fa-check text-success text-small').prependTo($file) ;

           

        }) ;
        
        $('#availableDate').datepicker({
            language: 'fr',
            minDate: new Date() 
          
        });

        $('#search-city').blur(function(){

            if ($('#search-city').val() < 1 ) {

                if ($('#autocomplete-home')) {

                    $('#autocomplete-home').remove() ;
                }

            }
        }) ;


        $('#search-city').focus(function(e){

            if ($('#search-city').val() < 1 ) {

                if ($('#autocomplete-home')) {

                    $('#autocomplete-home').remove() ;
                }

            }

            $(e.target).css('background' , 'white').val('').removeClass('border border-danger text-danger') ;

        }) ;

        

        $('#btn-search').click(function(e){

            if ( $('#home-longitude').val() == "" || $('#home-latitude').val()== ""  ){

                e.preventDefault() ;

                $('#search-city').css('background' , 'rgb(255, 235, 235)').addClass('border border-danger text-danger').val('Saisissez une ville')
                
                
            }


        }) ;


        $('#availableDate-home').datepicker({
            language: 'fr',
            minDate: new Date() 
          
        });

        $('#form-ajout').hide() ;


        $('#btn-form-ajout').click( function(){

             $('#form-ajout').show() ;
            })

        $('#more-search').hide() ;

        $('#btn-more-search').click( function(){

            $('#more-search').toggle() ;
           })
       

        if( $('#map').length > 0 ){

            app.map = L.map('map',{ scrollWheelZoom : false }).setView([47.081012,2.398782 ], 6);
            L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
                    }).addTo(app.map);

            app.getAllMarker() ;

          }
          

        $('.vehicle-choice').click(function(e){
            
            $('.vehicle-choice').removeClass('selected') ; 

            $('#select-vehicle-id').val( $(e.currentTarget).attr('data-vehicle-id') ) ;            
            $(e.currentTarget).addClass('selected') ;            
            
        })



        $('#btn-createPost').click(function(e){

            if ( $("input[name='vehicle']").val() == "" ) {

                e.preventDefault() ;
                $('<p>').text('Aucun véhicule n \'a été sélectionné').addClass('alert alert-danger text-center my-3').insertBefore('#availableDate') ;

            }


        }) ;

         
          if( $('#map-resultats').length > 0 ){
              
            app.currentResult = app.extractUrlParams() ;

            app.leasingIndexMap = L.map('map-resultats').setView([app.currentResult.latitude ,app.currentResult.longitude ], 12);

          L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(app.leasingIndexMap);

            app.getAvailableMarker() ;

          }


        $('#vehicle_adress').keydown(app.foundAdress) ;  
        
        

        $('#search-city').keydown(app.foundCity) ; 

        $('#vehicle_adress').blur(function(){

            if ( $('#vehicle_adress').val().length < 1 ){
                
                $('#autocomplete').empty() ;
            }

        }) ;

        $('.stars').click(function(e){

            $('.stars').css('color' , '#aaa')
            
            $('#comment_score').val($(e.currentTarget).attr('data-rate'))  ;

            $(e.currentTarget).css('color' ,'orange') ;

            $(e.currentTarget).nextAll('a').css('color' ,'orange') ;

        });

        $('#btn-add-review').click(function(e){

            if ( $('#comment_score').val() == 0 ) {

                e.preventDefault() ;

                $('.rating').popover('show');

                setTimeout(function(){  $('.rating').popover('hide'); }, 3000);
               

            }

            
        });



    },

   foundAdress : function(){            


        $.ajax( {
            url : 'https://api-adresse.data.gouv.fr/search/?q='+ $('#vehicle_adress').val() , 
            dataType : 'json', 
            
           
            }
        ).done(function(response){


            $('#autocomplete').empty() ;

            $('#zipcode').val(response.features[0].properties.citycode) ;
            $('#city').val(response.features[0].properties.city) ;
            $('#longitude').val(response.features[0].geometry.coordinates[0]) ;
            $('#latitude').val(response.features[0].geometry.coordinates[1]) ; 
                    
            response.features.forEach(function(result){

                   
                    $adress = $('<div>').text('  ' + result.properties.label).addClass('choice-adress py-1 px-2').appendTo('#autocomplete').click(function(){
                   
                    $('#vehicle_adress').val(result.properties.label) ;                                      
                    $('#zipcode').val(result.properties.citycode) ;
                    $('#city').val(result.properties.city) ;
                    $('#longitude').val(result.geometry.coordinates[0]) ;
                    $('#latitude').val(result.geometry.coordinates[1]) ;                   
                    $('#autocomplete').empty() ;  
                }) ;
                $('<i>').addClass('fa fa-map-marker color-green').attr('aria-hidden' , "true").prependTo($adress)
                
           
            }) ;

        });
    },

    getAllMarker : function(){

        $.ajax( { 
            url : 'marker/vehicule/all' , 
            dataType : 'json'
            

            }
        ).done(function(response){

            let $activeIcon = L.icon({
                iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-black.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                popupAnchor: [0, -30],
                shadowSize: [41, 41]
            });
        
            let $unActiveIcon = L.icon({
                popupAnchor: [0, -30],

                iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
            });

            response.forEach(function(marker){
                
                let $marker = L.marker( [marker.latitude, marker.longitude ]).addTo(app.map);
                
                let cardImg = '<img src="/pictures/' + marker.picture + '" alt="vehicule image" class="image-locations rounded"></img>';
                let cardPrice = '<span class="price">' + marker.dayCost +'€</span>';
                let cardCity = '<span class="city">' + marker.city + ' ' + marker.zipCode + '</span>';
                let cardButton = '<a href="/location/' + marker.id +  '/voir-annonce" class="btn btn-outline-info">Voir l\'annonce</a>';

                let $popup = $marker.bindPopup(cardImg + cardPrice + cardCity + cardButton);

                $($marker).mouseover(function() {
                    $popup.openPopup();
                    $marker.setZIndexOffset(100);
                    $marker.setIcon($activeIcon);
                })

                $($marker).mouseleave(function() {
                    $marker.setZIndexOffset(10);
                    $marker.setIcon($unActiveIcon);
                })
            })

        }) ;
          
      },

    foundCity : function(){            


    $.ajax( {
        url : 'https://api-adresse.data.gouv.fr/search/?q='+ $('#search-city').val() , 
        dataType : 'json', 
        
        
        }
    ).done(function(response){
        
        $('#autocomplete-home').remove() ; 
        $('<div>').attr('id' , 'autocomplete-home').insertAfter(('#search-city')) ;
         
                $('#home-longitude').val( response.features[0].geometry.coordinates[0]) ;
                $('#home-latitude').val(response.features[0].geometry.coordinates[1]) ; 

                
        response.features.forEach(function(result){

                 

                
                $adress = $('<div>').text('  ' + result.properties.city).addClass('choice-city py-2 px-2 rounded').appendTo('#autocomplete-home').click(function(){
                
                $('#search-city').val(result.properties.city) ;
                $('#autocomplete-home').empty() ;
                $('#home-longitude').val(result.geometry.coordinates[0]) ;
                $('#home-latitude').val(result.geometry.coordinates[1]) ; 
               
                      
                
            }) ;
            $('<i>').addClass('fa fa-map-marker color-green').attr('aria-hidden' , "true").prependTo($adress)
        
            }) ;

        });
    },

    extractUrlParams : function(){	
        var t = location.search.substring(1).split('&');
        var f = [];
        for (var i=0; i<t.length; i++){
            var x = t[ i ].split('=');
            f[x[0]]=x[1];
        }
        return f;
    },

onOver: function($unActiveIcon, $activeIcon, $allMarkers, $popup, i) {
    if (app.hoverMarker !== 0) {
        app.hoverMarker.setIcon($unActiveIcon);
        app.hoverMarker.setZIndexOffset(10);
    }
    $allMarkers[i].setIcon($activeIcon);
    $popup.openPopup();
    $allMarkers[i].setZIndexOffset(100);

    app.hoverMarker = $allMarkers[i];
},

    

getAvailableMarker : function(){

    let $allCards = $('.carte') ;

    let $allMarkers = [];

    let $activeIcon = L.icon({
        iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-black.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        popupAnchor: [0, -30],
        shadowSize: [41, 41]
    });

    let $unActiveIcon = L.icon({
        iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
    });

    for ( let i = 0 ; i < $allCards.length ; i++ ){

        let $marker = L.marker( [ $($allCards[i]).attr('data-latitude'),  $($allCards[i]).attr('data-longitude')]).addTo(app.leasingIndexMap);
        
        let $popup = $marker.bindPopup($($allCards[i]).find('.image-container').html() + $($allCards[i]).find('.show-annonce').html());
        $allMarkers.push($marker);

        $($allCards[i]).mouseover(function() {
            app.onOver($unActiveIcon, $activeIcon, $allMarkers, $popup, i);
        })

        $($marker).mouseover(function() {
            app.onOver($unActiveIcon, $activeIcon, $allMarkers, $popup, i);
            $($allCards[i]).addClass('carte-active')
        })

        $($marker).mouseleave(function() {
            $($allCards[i]).removeClass('carte-active');
        })
    }




}


}


$(app.init) ;