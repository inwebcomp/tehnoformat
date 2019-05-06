<div class="list wow {%config_animation_effect_list%}">
    <table class="table table-hover">
        <thead>
            {%list items%}
                {%ifset ._first%}
                    <tr>
                        {%ifset __fast_delete%}<th class="checkbox_field"><div class="checkbox"><input type="checkbox" class="styled parent_checkbox" /><label></label></div></th>{%endif%}
                        {%ifset .subject%}<th>{%lang Заголовок%}</th>{%endif%}
                        {%ifset .from%}<th>{%lang От кого%}</th>{%endif%}
                        {%ifset .to%}<th>{%lang Кому%}</th>{%endif%}
                        {%ifset .read%}<th>{%lang Прочитано%}</th>{%endif%}
                    </tr>
                {%endif%}
            {%end%}
        </thead>
        <tbody>
            {%list items%}
                <tr class="vertical-align{%if .block == 1%} warning{%endif%}">
                    {%ifset __fast_delete%}<th class="checkbox_field"><div class="checkbox"><input class="styled" name="elements[]" type="checkbox" value="{%.ID%}" /><label></label></div></th>{%endif%}
                    {%ifset .subject%}<th><a href="/backend/{%language_name%}/index/link/{%controllerName%}/edit/{%.ID%}">{%.subject%}</a></th>{%endif%}
                    {%ifset .from%}<th>{%.from%}</th>{%endif%}
                    {%ifset .to%}<th>{%.to%}</th>{%endif%}
                    {%ifset .read%}<th>{%if .read == "1"%}{%lang Да%}{%else%}{%lang Нет%}{%endif%}</th>{%endif%}
                </tr>
            {%end%}
        </tbody>
    </table>
</div>