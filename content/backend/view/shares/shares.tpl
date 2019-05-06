<div class="edit wow {%config_animation_effect_edit_block%} panel panel-default" id="shares">

	{%ifset mess%}
    	<div class="alert alert-{%ifnotset err%}success{%else%}danger{%endif%} alert-dismissible" role="alert">
        	<button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        	<strong>{%mess%}</strong>
        </div>
    {%endif%}

	<div class="page-header">
    	<h3 class="row">
        	<div class="col-sm-8">{%lang Акция%}</div>
        </h3>
    </div>
    
	<div class="panel-body">

		<form id="shares_form" class="form-horizontal">
            <div class="form-group">
                <label class="control-label col-md-2" for="share_ID">{%lang Акция товара%}</label>
                <div class="col-md-3">
                    <select name="share_ID" field="share_ID" class="form-control">
                    	<option value="" {%ifnotset share_ID%}selected{%endif%}>{%lang Без акции%}</option>
                    	{%list items%}
                        	<option value="{%.ID%}" {%if .ID == share_ID%}selected{%endif%}>{%.title%}</option>
                        {%end%}
                    </select>
                </div>
            </div>
            <div class="form-group adm-buttons animated_all"> 
                <div class="col-md-10">
                	{%includeview button_save%}
                </div>
            </div>
        </form> 
      	
        <script type="text/javascript">
			$("#shares_form").bind("submit", function(){
				$("#shares").Request({ controller: "{%controllerName%}", action: "shares_save", data: $('#shares_form').serialize() + "&object_ID={%ID%}" });
				return false;
			});
		</script>  
	</div>
</div>