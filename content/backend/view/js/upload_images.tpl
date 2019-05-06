<script type="text/javascript">
	/* File Uploader */
	
	var file_upload_url = "/scripts/upload.php?multi=1";
	
	$('.image-input .files-image').fileinput({
        language: 'ru',
		maxFilesNum: 10, 
		maxFileSize: 1024 * 100, // 10 MB
        allowedFileExtensions : ['jpg','jpeg','png','gif','svg'],
		showPreview : true,
		showRemove: false,
		uploadUrl: file_upload_url,
		uploadAsync: true		
    });
	

	$('.image-input .files-image').on('filebatchuploaderror', function(event, data, previewId, index){
		
		var form = data.form, files = data.files, extra = data.extra, response = data.response, reader = data.reader;
		console.log(response.result);
		
	}).on('fileuploaded', function(event, data, previewId, index){
		
		var form = data.form, files = data.files, extra = data.extra, response = data.response, reader = data.reader;

		if(response.result == 1){
			$.Request({ type: "json", controller: '{%controllerName%}', action: 'save_images', data: "object={%ID%}&file="+files[index].name, complete: function(responce){
				$(".image-input .kv-upload-progress").hide();
				
				$("#images_list").Request({ controller: '{%controllerName%}', action: 'images_list', data: "object={%ID%}", complete: function(){ $("#{%oc%} #images_list .checkbox input").set_base_image(); $("#{%oc%} #images_list .sortable .delete-image").delete_image(); $("#{%oc%} #images_list").sortable_images(); } });
			} });
		}else{
			$("#admin-content").append('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>{%lang Произошла ошибка при загрузке файла%}</strong> </div>');	
		}
		
	});
	
	$.fn.sortable_images = function(){
		var oc = $(this);
		/*oc.find(".sortable .image").each(function(i){
			$(this).width($(this).width());
			$(this).height($(this).height());
		});*/
		
		oc.find(".sortable").sortable({
			start: function(event,ui){ 
				ui.placeholder.height(ui.helper.height());
			},
			update: function(event,ui){ 
				var images = "";
				oc.find(".sortable .ui-sortable-handle").each(function(index, element){
					$(element).attr("rel", index);
					if(index > 0){ images += "&"; }
					images += "images[" + $(element).attr("ID") + "]=" + index;
				})
				$("#images_list").Request({ controller: '{%controllerName%}', action: 'save_images_positions', data: images + "&object={%ID%}", complete: function(){ $("#{%oc%} #images_list .checkbox input").set_base_image(); $("#{%oc%} #images_list .sortable .delete-image").delete_image(); $("#{%oc%}").sortable_images(); } });
			}
		});
		oc.find(".sortable").disableSelection();
	}
	
	$.fn.delete_image = function(){
		$(this).bind("click", function(){
			if(confirm("{%lang Вы действительно хотите удалить изображение?%}")){
				$("#images_list").Request({ controller: '{%controllerName%}', action: 'delete_image', data: "object={%ID%}&name="+$(this).attr("rel"), complete: function(){ $("#{%oc%} #images_list .checkbox input").set_base_image(); $("#{%oc%} #images_list .sortable .delete-image").delete_image(); $("#{%oc%}").sortable_images(); } });
				return false;
			}
		});
	}
	
	$.fn.set_base_image = function(){
		var oc = $(this);
		oc.bind("click", function(){
			if($(this).prop("checked")){
				return $("#images_list").Request({ controller: '{%controllerName%}', action: 'set_base_image', data: "object={%ID%}&name="+$(this).val(), complete: function(){ $("#{%oc%} #images_list .checkbox input").set_base_image(); $("#{%oc%} #images_list .sortable .delete-image").delete_image(); $("#{%oc%} .image-input").sortable_images(); } });
			}else{
				return false;	
			}
		});
	}
	
	$("#{%oc%} #images_list").sortable_images();
	
	$("#{%oc%} #images_list .sortable .delete-image").delete_image();
	
	$("#{%oc%} #images_list .checkbox input").set_base_image();

</script>