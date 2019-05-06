<div id="{%oc%}">
	{%block .path%}
		<div class="cmf_line" id="path_{%oc%}">
            <div class="cmf_path">
                {%list .path%}
                    <a href="javascript:void(0)" level="{%.level%}" class="cmf_parent path{%._index%}" rel="{%.ID%}"><img src="/img/content/white_{%ifnotset ._last%}bot{%else%}right{%endif%}_arrow.png" height="16" width="16" /> {%.title%}</a><br />
                {%end%}
            </div>
		</div>

            {%block .nodes%}
                {%block .select%}{%if .num > 0%}
                    <div class="cmf_line">
                        <div class="row title">
                        	<label>{%lang Потомки%}:</label>
                        </div>
                        <div class="row">
                            <select size="1" id="where_category_ID_{%oc%}" name="params[category_ID]" class="cmf_select cmf_filter">
                                <option selected value="{%category_ID%}">{%lang Выберите значение%}</option>
                                {%list path.nodes.items%}
                                    <option value="{%.ID%}">{%.title%}</option>
                                {%end%}
                            </select>
        				</div>
                    </div>
                    {%else%}
                        <input name="params[category_ID]" type="hidden" value="{%category_ID%}" />
                    {%endif%}
                {%end%}
    
            {%end%}
	{%end%}
</div>

<script type="text/javascript">

	$('#{%oc%} .cmf_parent').bind('click', function() {
		return $('#{%oc%}').Request({ controller: '{%controllerName%}', action: 'parent_category_select', data: 'ID=' + $(this).attr('rel') });
    });

    $('#{%oc%} .cmf_filter').bind('change', function() { return $('#{%oc%}').Request({ controller: '{%controllerName%}', action: 'parent_category_select', data: 'ID=' + $(this).val() });
    });

</script>