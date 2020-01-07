let modal = {
    init: function() {

        let checkboxLabel = [];

        $('.checkbox-reservation').click(function(){
            if($(this).prop("checked") == true){

                var label = $(this).next("label").text();

                checkboxLabel.push(label);

            }
            else if($(this).prop("checked") == false){

                var label = $(this).next("label").text();

                checkboxLabel.splice($.inArray(label, checkboxLabel),1);

            }
        })

        $('.ask-booking').click(function(){

            let datesLi = [];

            checkboxLabel.sort().forEach((label) => {
                datesLi.push('<li>' + label + '</li>');
            })

            $('.chosen-dates').html(datesLi);

            var price = parseInt($('.vehicle-price').text());

            var days = parseInt(checkboxLabel.length);

            var totalPrice = days * price;

            $('.total-price').html(totalPrice);

        })
    }

}

$(modal.init);