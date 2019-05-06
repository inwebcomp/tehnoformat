<div id="{%oc%}">
	{%block .path%}
		<div class="cmf_line" id="path_{%oc%}">
			{%lang Родитель%}:
			{%list .path%}
				<a href="javascript:void(0)" class="cmf_parent" rel="{%.ID%}">{%.title%}</a> {%ifnotset ._last%}&raquo;{%endif%}
			{%end%}
		</div>

		{%if .pathCount < .maxLevel%}
            {%block .nodes%}
                {%block .select%}{%if .num > 0%}
                    <div class="cmf_line">
                        <label for="eq_parent_ID_{%oc%}">{%lang Потомки%}:</label>
        
                        <select size="1" id="eq_parent_ID_{%oc%}" name="params[parent_ID]" class="cmf_select cmf_filter">
                            <option selected value="{%parent_ID%}">{%lang Выберите значение%}</option>
                            {%list path.nodes.items%}
                                <option value="{%.ID%}">{%.title%}</option>
                            {%end%}
                        </select>
        
                    </div>
                    {%else%}
                        <input name="params[parent_ID]" type="hidden" value="{%parent_ID%}" />
                    {%endif%}
                {%end%}
    
            {%end%}
        {%else%}
            <input name="params[parent_ID]" type="hidden" value="{%parent_ID%}" />
        {%endif%}
	{%end%}
</div>

<script type="text/javascript">

	$('#{%oc%} .cmf_parent').bind('click', function() {
		return $('#{%oc%}').Request({ controller: '{%controllerName%}', action: '{%oc%}', data: 'parent_ID=' + $(this).attr('rel') });
    });

    $('#{%oc%} .cmf_filter').bind('change', function() { return $('#{%oc%}').Request({ controller: '{%controllerName%}', action: '{%oc%}', data: 'parent_ID=' + $(this).val() });
    });

</script>