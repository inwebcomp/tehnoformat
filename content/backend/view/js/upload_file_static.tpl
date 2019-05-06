<script type="text/javascript">
	/* File Uploader */
	
	var fileUploader = fileUploaderContainer.find('.file-upload-static');
	var file_types = (file_types) ? file_types : "any";
	
	fileUploader.fileinput({
        language: 'ru',
		maxFilesNum: 1, 
		maxFileSize: 1024 * 50, // 50 MB
        allowedFileExtensions: file_types,
		showPreview : false,
		showRemove: true,
		showUpload: false,
		uploadAsync: true
    });
	
</script>