var previewNode = document.querySelector("#template");
previewNode.id = "";
var previewTemplate = previewNode.parentNode.innerHTML;
previewNode.parentNode.removeChild(previewNode);
Dropzone.autoDiscover = false;
Dropzone.options.myAwesomeDropzone = false;
new Dropzone("div#dropZoneArea", {
  url: "send-referrals",
  paramName: "files", // The name that will be used to transfer the file
  autoProcessQueue: true,
  maxFilesize: 2, // MB
  maxFiles : 5,
  thumbnailWidth: 150,
  thumbnailHeight: 150,
  previewTemplate: previewTemplate,
  previewsContainer: ".file_attatch",
  addRemoveLinks: true,
  acceptedFiles: ".png, .jpg, .jpeg, .doc, .docx, .xls, .xlsx, .pdf",
  dictFileTooBig: "File size too large. Max file size: {{maxFilesize}}MB.",
  dictInvalidFileType: "Supported file types are: .png, .jpg, .jpeg, .doc, .docx, .xls, .xlsx, .pdf.",

  clickable: "#clickable",
  init: function() {
    $('.dz-remove').hide();
    this.on("maxfilesexceeded", function(file){
        this.removeFile(file);
    });

    this.on('success', function(file,response) {
        $('.dz-remove').hide();
        var jsonData = JSON.parse(response);
        $(file.previewTemplate).append('<span style="display:none;" class="server_file">'+jsonData.filename+'</span>');
        var mydropzone = this;
        var re = /(?:\.([^.]+))?$/;
        var ext = re.exec(file.name)[1];
        ext = ext.toUpperCase();
        if(ext == 'XLS') {
            mydropzone.emit("thumbnail", file, path+'img/icons/xls.png');
        }
        if(ext == 'XLSX') {
            mydropzone.emit("thumbnail", file, path+'img/icons/xlsx.png');
        }
        if(ext == 'PDF') {
            mydropzone.emit("thumbnail", file, path+'img/icons/pdf.png');
        }
        if(ext == 'DOC'){
            mydropzone.emit("thumbnail", file, path+'img/icons/doc.png');
        }
        if(ext == 'DOCX'){
            mydropzone.emit("thumbnail", file, path+'img/icons/docx.png');
        }
    });

    this.on('removedfile', function(file) {
        var server_file = $(file.previewTemplate).children('.server_file').text();
        $.ajax({
            type: 'POST',
            url: 'send-referrals',
            data: { 'filename': server_file,'action' : 'delete' }
        });
    });

    this.on('error', function(file) {
        var mydropzone = this;
        //this.removeFile(file);
        $('.dz-remove').hide();
        mydropzone.emit("thumbnail", file, path+'img/icons/error.png');
    });

  }
});
$(document).ready(function(){
    $('#clearinputfile').hide();
    $("#file_attachment1").on('change',function(){
        $('#clearinputfile').show();
    }) ;

    $('#clearinputfile').click(function(e){ //on add input button click
        e.preventDefault();
        $('#file_attachment1').val(''); 
        $('#clearinputfile').hide();
    });  

    var max_fields = 5; //maximum input boxes allowed
    var wrapper = $("#add_more_files_input"); //Fields wrapper    
    var counter = 1; //initlal text box count    
    $('#clear_file').hide();
    $('button#add_file').click(function(e){ //on add input button click
        e.preventDefault();
        if(counter < max_fields){ //max input box allowed
          counter++; //text box increment
            if(counter == 5) {
                $('button#add_file').hide();
            }
            $(wrapper).append('<div class="form-group upload_file margin_clear Choose-a-contact" id="attachment'+counter+'"><input type="file" style="border: none;" class="upload_input" id="file_attachment'+counter+'" name="data[Contact][attachment]['+(counter-1)+']"><a href="#" class="remove_field" title="Remove">x</a></div>'); //add input box
        } 
    });    
    $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
        e.preventDefault(); 
        elmid = $(this).parent('div').attr('id');
        $(this).parent('div').remove();        
        $("label[for='file_"+elmid+"']").remove();  
        counter--;    
        $('button#add_file').show();  
    });
    $("#file_attachment1").change(function (){
        $('#clear_file').show();
    });
    $('#clear_file').click(function(e){ //on add input button click
        e.preventDefault();
        $('#file_attachment1').val('');
        $("label[for='file_attachment1']").remove();  
        $('#clear_file').hide();
    });

    $('#multiselect').multiselect({
        includeSelectAllOption: true,
        enableFiltering: true,
        includeFilterClearBtn: false,
        nSelectedText: 'members selected',
        allSelectedText: 'All members selected',
        maxHeight: 214,
    });

    $("#multiselect").change(function(){
    var selectedValue = $('#multiselect').val();
      if( selectedValue != null ) {
          $('.btn-group').css("border", "");
          $(".errorMember").html("");
      } else {
          $('.btn-group').css("border", "1px solid red");
          $(".errorMember").remove();
          $(".custom").remove();
          $( ' <div class="clearfix custom"></div><label class="errorMember" style="color:#c83a2a;">Please select a member</label>' ).insertAfter( ".btn-group" );
      }
    });
 
    $('#multiselect2').multiselect({
        includeSelectAllOption: true,
        enableFiltering: true,
        buttonContainer : '<div class="btn-group2" />',
        templates: {
                button: '<button type="button" class="multiselect dropdown-toggle multipleselect_btn2" data-toggle="dropdown"><span class="multiselect-selected-text pull-left"></span> <b class="caret sellect_arrow pull-right"></b></button>',
                ul: '<ul class="multiselect-container dropdown-menu"></ul>',
                filter: '<li class="multiselect-item filter"><div class="input-group"><input class="form-control multiselect-search" type="text"></div></li>',
                filterClearBtn: '<span class="input-group-btn"><button class="btn btn-default multiselect-clear-filter" type="button"><i class="glyphicon glyphicon-remove-circle"></i></button></span>',
                li: '<li><a tabindex="0"><label></label></a></li>',
                divider: '<li class="multiselect-item divider"></li>',
                liGroup: '<li class="multiselect-item multiselect-group"><label></label></li>'
            },
        includeFilterClearBtn: false,
        nSelectedText: 'Contact selected',
        allSelectedText: 'Contact selected',
        maxHeight: 214,
        minWidth: 405
    });

    $('#ContactForm').on('submit', function () {
        if (!$('#multiselect').val()) {
            $('.btn-group').css("border", "1px solid red");
            $(".errorMember").remove();
            $(".custom").remove();
            $( ' <div class="clearfix custom"></div><label class="errorMember" style="color:#c83a2a;">Please select a member</label>' ).insertAfter( ".btn-group" );
        }
    });
});
/**
 * ajaxChange() to fetch State /City list on country selection
 * @param url
 * @param location_id: country id
 * @param location_type: type of list to be fetched 1: state list, 2:city list
 */
function getStateList(countryId) {
    if (countryId!= '') {
        $.ajax({
            'type': 'post',
            'data': {'countryId': countryId},
            'url': BASE_URL+PAGE+'/sendReferrals',
            success: function (msg) {
                //$('#stateDiv').html(msg);
                //$('#state').focus();
            }
        });
    }
    if (countryId == '') {
        $('#stateDiv').html("<input id='state' class='form-control' name='data[Contact][state]' placeholder='State'>");
    }
}


function getUserDetail(contactId){
    $.ajax({
        type: "POST",
        url: getContactDetailsUrl+contactId,
        data: { contactId: contactId}
        })
        .done(function( msg ) {
            $('#first_name').removeClass('error').next('label.error').remove();
            $('#last_name').removeClass('error').next('label.error').remove();
            $('#email').removeClass('error').next('label.error').remove();
            $('#job_title').removeClass('error').next('label.error').remove();
            var obj = JSON.parse(msg);
            //console.log(obj.length);
            if(obj.hasOwnProperty('Contact')){
                $('#first_name').val(obj.Contact.first_name);
                $('#last_name').val(obj.Contact.last_name);
                $('#company').val(obj.Contact.company);
                //var job_title1 = $('<div/>').html(obj.Contact.job_title).text();
                $('#job_title').val(obj.Contact.job_title);
                $('#mobile').val(obj.Contact.mobile);
                $('#website').val(obj.Contact.website);
                $('#email').val(obj.Contact.email);
                $('#city').val(obj.Contact.city);
                $('#zip').val(obj.Contact.zip);
                $('#country_id').val(obj.Contact.country_id);
                $('#country').val(obj.Country.country_name);
                if ($('#country_id').val(obj.Contact.country_id) != '') {
                    var countryID = $('#country_id').val();
                    $.ajax({
                        'type': 'post',
                        'data': {'countryId': countryID},
                        'url': BASE_URL+PAGE+'/sendReferrals',
                        success: function (msg) {
                            //$('#stateDiv').html(msg);
                            $('#state_id').val(obj.Contact.state_id);
                            //$('#country2').val(obj.Contact.country_id);
                            //$('#state2').val(obj.Contact.state_id);
                            //$('#stateDiv').html("<input type='text' id='state' class='form-control' name='data[Contact][state]'");
                            $('#state').val(obj.State.state_subdivision_name);
                            //$("#country").attr('disabled', true).trigger("liszt:updated");
                            //$("#state").attr('disabled', true).trigger("liszt:updated");
                        }
                    });
                }
                if ($('#country_id').val(obj.Contact.country_id) == '') {
                    //$('#stateDiv').html("<input id='state' class='form-control' name='data[Contact][state]' placeholder='State'>");
                }
                $('#office_phone').val(obj.Contact.office_phone);
                $('#address').val(obj.Contact.address);
            } else {
                    
                //$("#country").attr('disabled', false);
                //$('#stateDiv').html("<input id='state' class='form-control' name='data[Contact][state]' placeholder='State'><input id='state_id' class='form-control' name='data[Contact][state_id]', 'type = 'hidden'");
                var jqueryValidator = $("#ContactForm").validate();
                //jqueryValidator.resetForm();
                $('#first_name').val('');
                $('#last_name').val('');
                $('#address').val('');
                $('#office_phone').val('');
                $('#company').val('');
                $('#job_title').val('');
                $('#mobile').val('');
                $('#website').val('');
                $('#email').val('');
                $('#city').val('');
                $('#zip').val('');
                $('#country').val('');
                $('#country_id').val('');
                $('#state').val('');
                $('#state_id').val('');
                //$('#country').val(obj.Contact.country_id);
                $.ajax({
                        'type': 'post',
                        'url': BASE_URL+PAGE+'/sendReferrals',
                        success: function (msg) {
                        }
                    });
                $('input[readonly="readonly"]').removeAttr("readonly");
            }
        });
}
