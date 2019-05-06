<script type="text/javascript">
	/* File Uploader */
	
	var file_upload_url = "/scripts/upload.php?all=1";
	
	var fileUploader = fileUploaderContainer.find('.file-upload');
	
	fileUploader.fileinput({
        language: 'ru',
		maxFilesNum: 1, 
		maxFileSize: 1024 * 50, // 50 MB
        fileType: "any",
		showPreview : false,
		showRemove: false,
		uploadUrl: file_upload_url,
		uploadAsync: false
    });
	
	fileUploader.on('filebatchuploaderror', function(event, data, previewId, index){
		
		var form = data.form, files = data.files, extra = data.extra, response = data.response, reader = data.reader;
		console.log(response.result); 
		
	}).on('filebatchuploadsuccess', function(event, data, previewId, index){
		
		var form = data.form, files = data.files, extra = data.extra, response = data.response, reader = data.reader;

		if(response.result == 1){
			$.Request({ type: "json", controller: '{%controllerName%}', action: 'save_file', data: "object={%ID%}&group={%group%}&file="+files[0].name, complete: function(responce){
				fileUploaderContainer.find(".kv-upload-progress").replaceWith("");
				
				$("#files_list_{%group%}").Request({ controller: '{%controllerName%}', action: 'files_list', data: "object={%ID%}&group={%group%}&title={%title%}", complete: function(){ $("#files_list_{%group%} .delete-file").delete_file_{%group%}(); } });
			} });
		}else{
			$("#admin-content").append('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>{%lang Произошла ошибка при загрузке файла%}</strong> </div>');	
		}
		
	});
	
	$.fn.delete_file_{%group%} = function(){
		$(this).bind("click", function(){
			if(confirm("{%lang Вы действительно хотите удалить файл?%}")){
				$("#files_list_{%group%}").Request({ controller: '{%controllerName%}', action: 'delete_file', data: "object={%ID%}&group={%group%}&name="+$(this).attr("rel"), complete: function(){ $("#files_list_{%group%} .sortable .delete-file").delete_image_{%group%}(); } });
				return false;
			}
		});
	}
	
	$("#files_list_{%group%} .delete-file").delete_file_{%group%}();
	
</script>