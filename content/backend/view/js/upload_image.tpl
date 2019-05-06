<script type="text/javascript">
	/* File Uploader */
	
	var file_upload_url = "/scripts/upload.php";

	$('.image-input .file-image').fileinput({
        language: 'ru',
		maxFilesNum: 1, 
		maxFileSize: 1024 * 10, // 10 MB
        allowedFileExtensions : ['jpg','jpeg','png','gif','svg'],
		showPreview : true,
		showRemove: false,
		uploadUrl: file_upload_url,
		{%if base_image !== ""%}initialPreview: [
			'<img src="/image/{%modelName%}/{%ID%}/200x150/{%base_image%}" />'
		],
		overwriteInitial: true,{%endif%}
		uploadAsync: false
    });
	
	var InitUploadImage = function(){
		$('.image-input .file-image').on('filebatchuploaderror', function(event, data, previewId, index){
			
			var form = data.form, files = data.files, extra = data.extra, response = data.response, reader = data.reader;
			console.log(response); 
			
		}).on('filebatchuploadsuccess', function(event, data, previewId, index){
			
			var form = data.form, files = data.files, extra = data.extra, response = data.response, reader = data.reader;
	
			if(response.result == 1){
				$.Request({ type: "json", controller: '{%controllerName%}', action: 'save_image', data: "object={%ID%}&file="+files[0].name, complete: function(responce){
					$(".image-input .kv-upload-progress").replaceWith("");
					$('.image-input .file-image').fileinput('refresh', { overwriteInitial: true, initialPreview: [ '<img src="/image/{%modelName%}/{%ID%}/200x150/'+responce.file_name+'" />' ] });
					$(".image-input .fileinput-remove").bind('click', delete_image);
					InitUploadImage();
				} });
			}else{
				$("#admin-content").append('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>{%lang Произошла ошибка при загрузке файла%}</strong> </div>');	
			}
		});
	};
	
	InitUploadImage();
	
	var delete_image = function(){
		if(confirm("{%lang Вы действительно хотите удалить изображение?%}")){
			$.Request({ type: "json", controller: '{%controllerName%}', action: 'delete_all_images', data: "object={%ID%}", complete: function(responce){	$('.image-input .file-image').fileinput('clear'); } });
		}
	}
	$(".image-input .fileinput-remove").bind('click', delete_image);
</script>