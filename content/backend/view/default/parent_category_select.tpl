{%block .path%}
    <div id="{%oc%}">
        
        <div class="form-group{%ifset .err_tpl%} has-error{%endif%}" id="path_{%oc%}">
            <label class="control-label col-md-2" for="tpl">{%lang Категория%}:</label>
            <div class="col-md-6">
            	<ol class="breadcrumb">
                {%list .path%}
                    <li><a href="javascript:void(0)" class="cms_parent" rel="{%.ID%}">{%.title%}</a></li>
                {%end%}
                </ol>              
                {%if .pathCount < .maxLevel%}
                    {%block .nodes%}
                        {%block .select%}{%if .num > 0%}
                        		<select id="eq_category_ID_{%oc%}" name="params[category_ID]" class="form-control">
                                    <option selected value="{%category_ID%}">{%lang Выберите значение%}</option>
                                    {%list path.nodes.items%}
                                        <option value="{%.ID%}">{%.title%}</option>
                                    {%end%}
                                </select>
                            {%else%}
                                <input name="params[category_ID]" type="hidden" value="{%category_ID%}" />
                            {%endif%}
                        {%end%}
            
                    {%end%}
                {%else%}
                    <input name="params[category_ID]" type="hidden" value="{%category_ID%}" />
                {%endif%}
            </div>
        </div>
        
    </div>

	<script type="text/javascript">
    
        $('#{%oc%} .cms_parent').bind('click', function(){
			return $('#{%oc%}').Request({ controller: '{%controllerName%}', action: 'parent_category_select', data: 'object=' + $(this).attr('rel') });
		});
    
        $('#{%oc%} #eq_category_ID_{%oc%}').bind('change', function(){ return $('#{%oc%}').Request({ controller: '{%controllerName%}', action: 'parent_category_select', data: 'object=' + $(this).val() }); });
    
    </script>

{%end%}
