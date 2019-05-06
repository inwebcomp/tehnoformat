{%ifset ID%}
    <div class="form-group file-input-{%group%}">
        <label class="control-label col-md-2" for="{%group%}_file">{%title%}:</label>
        <div class="col-md-6">
            <input name="params[file]" id="{%group%}_file" class="file-upload" type="file">
        </div>
    </div>
    <script type="text/javascript">
		var fileUploaderContainer = $(".file-input-{%group%}");
	</script>
    {%includeview files_list%}
    {%js upload_file%}
{%else%}
	<div class="form-group">
        <label class="control-label col-md-2" for="{%group%}_file">{%title%}:</label>
        <div class="col-md-6">
        	<input id="{%group%}_file" class="form-control" readonly type="text" value="{%lang Файл нельзя выбрать пока запись не сохранена%}">
        </div>
    </div>
{%endif%}

