<div class="list wow {%config_animation_effect_list%}">
    <table class="table table-hover">
        <thead>
            {%list items%}
                {%ifset ._first%}
                    <tr>
                        {%ifset __fast_delete%}<th class="checkbox_field"><div class="checkbox"><input type="checkbox" class="styled parent_checkbox" /><label></label></div></th>{%endif%}
                        <th>{%lang Автор%}</th>
                        <th>{%lang Текст%}</th>
                        <th>{%lang Дата%}</th>
                        <th>{%lang Тип%}</th>
                    </tr>
                {%endif%}
            {%end%}
        </thead>
        <tbody>
            {%list items%}
                <tr rel="{%.ID%}" class="vertical-align{%if .block == 1%} warning{%endif%}">
                    {%ifset __fast_delete%}<th class="checkbox_field"><div class="checkbox"><input class="styled" name="elements[]" type="checkbox" value="{%.ID%}" /><label></label></div></th>{%endif%}
                    <th><a href="/backend/{%language_name%}/index/link/{%controllerName%}/edit/{%.ID%}">{%if .person !== ""%}{%.person%}{%else%}---{%endif%}</a></th>
                    <th>{%.text%}</th>
                    <th>{%.created%}</th>
                    <th>{%.type%}</th>
                </tr>
            {%end%}
        </tbody>
    </table>
</div>