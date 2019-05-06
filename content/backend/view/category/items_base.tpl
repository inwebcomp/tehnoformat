<div class="list wow {%config_animation_effect_list%}">
    <table class="table table-hover">
        <thead>
            {%list items%}
                {%ifset ._first%}
                    <tr>
                        {%ifset __fast_delete%}<th class="checkbox_field"><div class="checkbox"><input type="checkbox" class="styled parent_checkbox" /><label></label></div></th>{%endif%}
                        <th>{%lang Заголовок%}</th>
                        <th>{%lang Скидка%}</th>
                        <th>{%lang Позиция%}</th>
                        <th>{%lang Фильтра%}</th>
                    </tr>
                {%endif%}
            {%end%}
        </thead>
        <tbody>
            {%list items%}
                <tr rel="{%.ID%}" class="vertical-align{%if .block == 1%} warning{%endif%} with_position" rel="{%.ID%}" level="{%.padding_num%}" parent="{%.parent_ID%}">
                    {%ifset __fast_delete%}<th class="checkbox_field"><div class="checkbox"><input class="styled" name="elements[]" type="checkbox" value="{%.ID%}" /><label></label></div></th>{%endif%}
                    <th{%if .last_level == "1"%} class="without_arrow"{%endif%}{%if .last_level !== "1"%} class="cms_parent"{%endif%}><a style="margin-left:{%.padding_num%}px;" href="/backend/{%language_name%}/index/link/{%controllerName%}/edit/{%.ID%}">{%.title%}</a></th>
                    <th fast-edit="sale">{%.sale%}</th>
                    {%ifset .pos%}<th class="pos">{%ifset __fast_save%}<div class="input-group input-group-sm"><input name="params[{%.ID%}][pos]" type="text" value="{%.pos%}" class="filter-int form-control small" />{%else%}{%.pos%}{%endif%}</div></th>{%endif%}
                    <th><a href="/backend/{%language_name%}/index/link/filters/items/{%.ID%}" target="_blank">{%lang К списку%}</a></th>
                </tr>
            {%end%}
        </tbody>
    </table>
</div>