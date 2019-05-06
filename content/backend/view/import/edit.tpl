{%include header_free_style%}

	{%ifset mess%}
    	<div class="alert alert-{%ifnotset err%}success{%else%}danger{%endif%} alert-dismissible" role="alert">
        	<button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        	{%ifset import_error%}
            	{%list errors%}
                	<strong style="white-space:nowrap;">{%.value%}</strong><br />
                {%end%}
            {%else%}
            	<strong>{%mess%}</strong>
            {%endif%}
        </div>
        <script type="text/javascript">
            $('.alert').appear();	
        </script>
    {%endif%}
    
	{%includeview edit_base%}
    
    {%includeview modules_base%}

{%include footer_free_style%}

{%js cms_edit%}
