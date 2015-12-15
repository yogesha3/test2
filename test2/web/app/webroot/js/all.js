/**
 * ajaxChange() to fetch State /City list on country selection
 * @param url
 * @param location_id: country id
 * @param location_type: type of list to be fetched 1: state list, 2:city list
 */

function getStateList(countryId) {
    var ajaxUrl = "<?php echo Router::url(array('controller'=>'users','action'=>'getStateCity'));?>";    
    if (countryId!= '') {
        $.ajax({
            'type': 'post',
            'data': {'countryId': countryId},
            'url': ajaxUrl,
            success: function (msg) {
                $('#stateDiv').html(msg);
            }
        });
    }
    if (countryId == '') {
        $('#stateDiv').html("<select id='state' class='form-control' name='data[BusinessOwner][state_id]'><option value=''>Select State</option></select>");
    }
}
$(function(){
    $("#country").autocomplete({
            //source: "countrylist",
            //minLength: 2,
            source: function( request, response ) {
                $.ajax({
                    url: BASE_URL+PAGE+"/getCountryList",
                    dataType: "json",
                    'type' : 'post',
                    data: {country: request.term},
                    success: function( data ) {
                        if(!data.length){
                          var result = [
                           {
                           label: 'No matches found', 
                           value: response.term
                           }
                         ];
                           response(result);
                         } else {
                            response( $.map( data, function( item ) {
                                if($('#country_id').next('.error').length>0) {
                                    $('#country_id').next().hide();
                                }
                                return {
                                    label: item.label,
                                    value: item.label,
                                    link:item.value
                                }
                            }));
                        }
                             
                        }
                });
            },
            minLength: 1,
            change: function (event, ui){
            if (!ui.item) {
                this.value = '';
                $('#country_id').val('');
                 $('#state').val('');
                 $('#state_id').val('');
            } else {
                 $('#country_id').val(ui.item.link);
                  $('#state').val('');
                  $('#state_id').val('');
            }
        } 

  });

    $("#state").autocomplete({
        
            source: function( request, response ) {
                $.ajax({
                    url: BASE_URL+PAGE+"/getStateList",
                    dataType: "json",
                    'type' : 'post',
                    data:   {
                                country : $("#country").val(),
                                state : request.term
                            },
                    success: function( data ) {
                        if(!data.length){
                            if($("#country_id").val()!=''){
                                 var result = [
                           {
                           label: 'No matches found ', 
                           value: response.term,
                           }
                         ];
                            } else {
                          var result = [
                           {
                           label: 'Select Country ', 
                           value: response.term
                           }
                         ];}
                           response(result);
                         } else {
                            response( $.map( data, function( item ) {
                                if($('#state_id').next('.error').length>0) {
                                    $('#state_id').next().hide();
                                }
                                return {
                                    label: item.label,
                                    value: item.label,
                                    link: item.value
                                }
                            }));
                        }
                             
                        }
                });
            },
            minLength: 1,
            change: function (event, ui){
            if (!ui.item) {
                this.value = '';
                $('#state_id').val('');
            } else {
                 $('#state_id').val(ui.item.link);
            }
        } 
  });
});