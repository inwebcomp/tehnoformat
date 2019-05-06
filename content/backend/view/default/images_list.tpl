<div id="images_list">
	{%if actionName !== "save"%}
        {%ifset mess%}
            <div class="alert alert-{%ifnotset err%}success{%else%}danger{%endif%} alert-dismissible" role="alert">
                <button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>{%mess%}</strong>
            </div>
        {%endif%}
    {%endif%}
    {%block images%}
        <div class="form-group">
            <div class="col-md-offset-2 col-md-6 images-container sortable">
                {%list .items%}
                    <div class="col-md-6 image" rel="{%.pos%}" ID="{%.ID%}">
                    	<button type="button" class="close delete-image" aria-label="{%lang Удалить%}" rel="{%.name%}"><span aria-hidden="true">&times;</span></button>
                        <div class="checkbox">{%lang Главное%}<input class="styled" type="checkbox" value="{%.name%}"{%if base_image == .name%} checked="checked"{%endif%} /><label></label></div>
                        <a href="/image/{%images.modelName%}/{%images.ID%}/0x0/{%.name%}" class="thumbnail" target="_blank">
                            <img src="/image/{%images.modelName%}/{%images.ID%}/200x150/{%.name%}" width="200" height="150" />
                        </a>
                    </div>
                {%end%}
            </div>
        </div>
    {%end%}
</div>