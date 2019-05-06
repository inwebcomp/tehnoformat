<div class="list wow {%config_animation_effect_list%} shadow'>
	<table class="cmf_table cmf_tree" border="0" cellpadding="0" cellspacing="0">
		<tr class="cmf_nodblclick">
			{%ifset __fast_delete%}<td class="list_title checkbox"><input type="checkbox" class="parent_checkbox" /></td>{%endif%}
			<td class="list_title">{%lang Заголовок%}</td>
		</tr>
        
		{%list items%}
			<tr rel="{%.ID%}" level="{%.level%}" class="cmf_parent lvl_{%.level%} {%if .block == 1%}blocked{%endif%}{%if .last_level == 1%} last_level{%endif%}">
				{%ifset __fast_delete%}<td class="list_element checkbox"><input class="list_element checkbox" name="elements[]" type="checkbox" value="{%.ID%}" /></td>{%endif%}
					
				<td class="list_element">{%.title%}</td>
			</tr>
		{%end%}
	</table>
</div>