<div class="list wow {%config_animation_effect_list%}">
    <table class="table table-hover">
        <thead>
            {%list items%}
                {%ifset ._first%}
                    <tr>
                        {%ifset __fast_delete%}<th class="checkbox_field"><div class="checkbox"><input type="checkbox" class="styled parent_checkbox" /><label></label></div></th>{%endif%}
                        {%ifset .title%}<th>{%lang Заголовок%}</th>{%endif%}
                        {%ifset .pos%}<th>{%lang Позиция%}</th>{%endif%}
                        {%ifset .created%}<th>{%lang Создан%}</th>{%endif%}
                        {%ifset .updated%}<th>{%lang Изменен%}</th>{%endif%}
                        {%ifset .popular%}<th class="padding_s center">{%lang Популярный%}</th>{%endif%}
                    </tr>
                {%endif%}
            {%end%}
        </thead>
        <tbody>
            {%list items%}
                <tr rel="{%.ID%}" class="vertical-align{%if .block == 1%} warning{%endif%} with_position">
                    {%ifset __fast_delete%}<th class="checkbox_field"><div class="checkbox"><input class="styled" name="elements[]" type="checkbox" value="{%.ID%}" /><label></label></div></th>{%endif%}
                    {%ifset .title%}<th><a href="/backend/{%language_name%}/index/link/{%controllerName%}/edit/{%.ID%}">{%.title%}</a></th>{%endif%}
                    {%ifset .pos%}<th class="pos">{%ifset __fast_save%}<div class="input-group input-group-sm"><input name="params[{%.ID%}][pos]" type="text" value="{%.pos%}" class="filter-int form-control small" />{%else%}{%.pos%}{%endif%}</div></th>{%endif%}
                    {%ifset .created%}<th>{%.created%}</th>{%endif%}
                    {%ifset .updated%}<th>{%.updated%}</th>{%endif%}
                        
                    <th fast-edit="popular" class="checkbox_field free center"><div class="checkbox show_only_label"><input class="styled focus-input" type="checkbox" value="1"{%if .popular == "1"%} checked{%endif%} id="{%.ID%}_checkbox_popular" /><label for="{%.ID%}_checkbox_popular"><span class="glyphicon glyphicon-ok{%if .popular == "1"%} active{%endif%}" aria-hidden="true"></span></label><input type="hidden" value="{%.popular%}" /></div></th>
                </tr>
            {%end%}
        </tbody>
    </table>
</div>