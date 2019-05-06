<div class="list wow {%config_animation_effect_list%}">
    <table class="table table-hover">
        <thead>
            {%list items%}
                {%ifset ._first%}
                    <tr>
                        {%ifset __fast_delete%}<th class="checkbox_field"><div class="checkbox"><input type="checkbox" class="styled parent_checkbox" /><label></label></div></th>{%endif%}
                        <th>{%lang Логин%}</th>
                        <th>{%lang Email%}</th>
                        <th>{%lang Зарегистрирован%}</th>
                    </tr>
                {%endif%}
            {%end%}
        </thead>
        <tbody>
            {%list items%}
                <tr class="vertical-align{%if .block == 1%} warning{%endif%}">
                    {%ifset __fast_delete%}<th class="checkbox_field"><div class="checkbox"><input class="styled" name="elements[]" type="checkbox" value="{%.ID%}" /><label></label></div></th>{%endif%}
                    <th><a href="/backend/{%language_name%}/index/link/{%controllerName%}/edit/{%.ID%}">{%.login%}</a></th>
                    <th>{%.email%}</th>
                    <th>{%.created%}</th>
                </tr>
            {%end%}
        </tbody>
    </table>
</div>
