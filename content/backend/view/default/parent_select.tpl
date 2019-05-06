{%block .path%}
    <div id="{%oc%}">
        
        <div class="form-group{%ifset .err_tpl%} has-error{%endif%}" id="path_{%oc%}">
            <label class="control-label col-md-2" for="tpl">{%lang Родитель%}:</label>
            <div class="col-md-6">
            	<ol class="breadcrumb">
                {%list .path%}
                    <li><a href="javascript:void(0)" class="cms_parent" rel="{%.ID%}">{%.title%}</a></li>
                {%end%}
                </ol>              
                {%if .pathCount < .maxLevel%}
                    {%block .nodes%}
                        {%block .select%}{%if .num > 0%}
                        		<select id="eq_parent_ID_{%oc%}" name="params[parent_ID]" class="form-control">
                                    <option selected value="{%parent_ID%}">{%lang Выберите значение%}</option>
                                    {%list path.nodes.items%}
                                        <option value="{%.ID%}">{%.title%}</option>
                                    {%end%}
                                </select>
                            {%else%}
                                <input name="params[parent_ID]" type="hidden" value="{%parent_ID%}" />
                            {%endif%}
                        {%end%}
            
                    {%end%}
                {%else%}
                    <input name="params[parent_ID]" type="hidden" value="{%parent_ID%}" />
                {%endif%}
            </div>
        </div>
        
    </div>

	<script type="text/javascript">
    
        $('#{%oc%} .cms_parent').bind('click', function() {
            return $('#{%oc%}').Request({ controller: '{%controllerName%}', action: 'parent_select', data: 'parent_ID=' + $(this).attr('rel') });
        });
    
        $('#{%oc%} select').bind('change', function() { return $('#{%oc%}').Request({ controller: '{%controllerName%}', action: 'parent_select', data: 'parent_ID=' + $(this).val() });
        });
    
    </script>

{%end%}