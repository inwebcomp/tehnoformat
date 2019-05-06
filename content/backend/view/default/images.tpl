{%ifset ID%}
    <div class="form-group{%ifset .err_base_image%} has-error{%endif%} image-input">
        <label class="control-label col-md-2" for="base_image">{%lang Изображения%}:</label>
        <div class="col-md-6">
            <input name="params[base_image]" id="base_image" class="files-image" type="file" multiple="multiple">
            {%ifset .err_base_image%}<span class="help-block">{%.err_base_image_mess%}</span>{%endif%}
        </div>
    </div>  
    
    {%includeview images_list%}    
    
    {%js upload_images%}
{%else%}
	<div class="form-group{%ifset .err_base_image%} has-error{%endif%} image-input">
        <label class="control-label col-md-2" for="base_image">{%lang Изображения%}:</label>
        <div class="col-md-6">
        	<input id="base_image" class="form-control" readonly type="text" value="{%lang Изображения нельзя выбрать пока запись не сохранена%}">
        </div>
    </div>
{%endif%}

