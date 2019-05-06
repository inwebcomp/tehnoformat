{%ifset ID%}
    <div class="form-group{%ifset .err_base_image%} has-error{%endif%} image-input">
        <label class="control-label col-md-2" for="base_image">{%lang Изображение%}:</label>
        <div class="col-md-6">
            <input name="params[base_image]" id="base_image" class="file-image" type="file">
            {%ifset .err_base_image%}<span class="help-block">{%.err_base_image_mess%}</span>{%endif%}
        </div>
    </div>  
    {%js upload_image%}
{%else%}
	<div class="form-group{%ifset .err_base_image%} has-error{%endif%} image-input">
        <label class="control-label col-md-2" for="base_image">{%lang Изображение%}:</label>
        <div class="col-md-6">
        	<input id="base_image" class="form-control" readonly type="text" value="{%lang Изображение нельзя выбрать пока запись не сохранена%}">
        </div>
    </div>
{%endif%}

