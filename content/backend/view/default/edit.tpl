{%include header_free_style%}

	{%ifset mess%}
    	<div class="alert alert-{%ifnotset err%}success{%else%}danger{%endif%} alert-dismissible" role="alert">
        	<button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        	<strong>{%mess%}</strong>
        </div>
    {%endif%}
    
	{%includeview edit_base%}
    
    {%includeview modules_base%}

{%include footer_free_style%}

{%js cms_edit%}
