var previewNode = document.querySelector("#template");
previewNode.id = "";
var previewTemplate = previewNode.parentNode.innerHTML;
previewNode.parentNode.removeChild(previewNode);
Dropzone.autoDiscover = false;
Dropzone.options.myAwesomeDropzone = false;
new Dropzone("div#dropZoneArea", {
  url: "composeMessage",
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
            url: 'composeMessage',
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
$( "#sendto" ).change(function() {
	val = $( this ).val();
	$("#recipient_list_field").hide();
	$("#recipient_list_field").removeClass('listDisplay');
	$('.btn-group').css("border", "");
    $(".errorMember").html("");
	if(val==0){
		$("#recipient_list_field").show();
		$("#recipient_list_field").addClass('listDisplay');
	}	
});
$(document).ready(function () {
	$('#recipient_list').multiselect({
	    includeSelectAllOption: true,
	    enableFiltering: true,
	    includeFilterClearBtn: false,
	    nSelectedText: 'members selected',
	    allSelectedText: 'All members selected',
	    maxHeight: 214,
	})

	$("#recipient_list").change(function(){
    var selectedValue = $('#recipient_list').val();
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
	
	$('#composeMessageForm').on('submit', function () {
		if($('.listDisplay').length > 0){
			if (!$('#recipient_list').val()) {
				$('.btn-group').css("border", "1px solid red");
				$(".errorMember").remove();
				$(".custom").remove();

				$( ' <div class="clearfix custom"></div><label class="errorMember" style="color:#c83a2a;">Please select a member</label>' ).insertAfter( ".btn-group" );
			}
		}
      
    });
});