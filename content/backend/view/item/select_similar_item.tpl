<div id="select_similar_item">
	<form method="post" class="form-horizontal">
        {%block .path%}
            <div class="form-group">
                <label class="control-label col-md-3">{%lang Категория%}:</label>
                <div class="col-md-6">
                    <ol class="breadcrumb">
                    {%list .path%}
                        <li><a href="javascript:void(0)" class="cms_parent" rel="{%.ID%}">{%.title%}</a></li>
                    {%end%}
                    </ol>              
                </div>
            </div>
            
            {%block .nodes%}
                {%if .count > 0%}
                <div class="form-group">
                    <label class="control-label col-md-3" for="where_parent_ID_{%oc%}">{%lang Выберите подкатегорию%}:</label>
                    <div class="col-md-6">
                        <select size="1" id="where_parent_ID_{%oc%}" name="params[parent_ID]" class="form-control">
                            <option value="{%parent_ID%}">{%lang Выберите значение%}</option>
                            {%list .items%}
                                <option value="{%.ID%}">{%.title%}</option>
                            {%end%}
                        </select>
                    </div>
                </div>
                {%endif%}
            {%end%}
    </form>
    
    {%block products%}
        {%ifcount .items > 0%}
        <form rel="similar_items" action="{%controllerName%}/save_similar" class="ajax-form-request form-horizontal">
        
        	<input name="object" type="hidden" value="{%ID%}" />
            
            <div class="form-group">
                <label class="control-label col-md-3">{%lang Выберите товар%}:</label>
                <div class="col-md-6">
                    <select size="5" multiple="multiple" name="params[]" class="form-control">
                        {%list .items%}
                            <option value="{%.ID%}">{%.title%}</option>
                        {%end%}
                    </select>
                </div>
            </div>

            <div class="form-group adm-buttons animated_all"> 
                <div class="col-md-offset-3 col-md-9">
                	{%includeview button_save%}
                </div>
            </div>
        </div>
        </form>
        {%endif%}
    {%end%}
    
        {%end%}
</div>

<script type="text/javascript">
	$('#select_similar_item .cms_parent').bind('click', function() {
		return $('#select_similar_item').Request({ controller: '{%controllerName%}', action: 'select_similar_item', data: 'object={%ID%}&parent_ID=' + $(this).attr('rel') });
    });

    $('#select_similar_item #where_parent_ID_{%oc%}').bind('change', function() {
    	return $('#select_similar_item').Request({ controller: '{%controllerName%}', action: 'select_similar_item', data: 'object={%ID%}&parent_ID=' + $(this).val() });
    });
</script>