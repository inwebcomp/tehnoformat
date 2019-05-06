<div id="files_list_{%group%}">
	{%ifset mess%}
    	<div class="alert alert-{%ifnotset err%}success{%else%}danger{%endif%} alert-dismissible" role="alert">
        	<button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        	<strong>{%mess%}</strong>
        </div>
    {%endif%}
    <div class="form-group">
        <div class="col-md-offset-2 col-md-6 file-container sortable">
            {%list items%}
                <div class="col-md-6 file" rel="{%.pos%}" ID="{%.ID%}">
                    <button type="button" class="close delete-file" aria-label="{%lang Удалить%}" rel="{%.name%}"><span aria-hidden="true">&times;</span></button>
                    {%if .type == "image"%}
                    	<a href="{%root%}/files/{%group%}/{%modelName%}/{%ID%}/{%.name%}" class="thumbnail" target="_blank"><img src="{%root%}/files/{%group%}/{%modelName%}/{%ID%}/{%.name%}" /></a>
                    {%else%}
                    	<a href="{%root%}/files/{%group%}/{%modelName%}/{%ID%}/{%.name%}" class="thumbnail" target="_blank">{%.name%}</a>
                    {%endif%}
                </div>
            {%end%}
        </div>
	</div>
</div>