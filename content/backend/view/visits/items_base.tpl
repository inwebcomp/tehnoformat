<div class="list wow {%config_animation_effect_list%}">
    <table class="table table-hover">
        <thead>
            {%list items%}
                {%ifset ._first%}
                    <tr>
                        {%ifset __fast_delete%}<th class="checkbox_field"><div class="checkbox"><input type="checkbox" class="styled parent_checkbox" /><label></label></div></th>{%endif%}
                        {%ifset .ip%}<th>{%lang IP адрес%}</th>{%endif%}
                        {%ifset .browser%}<th>{%lang Браузер%}</th>{%endif%}
                        {%ifset .updated%}<th>{%lang Время%}</th>{%endif%}
                    </tr>
                {%endif%}
            {%end%}
        </thead>
        <tbody>
            {%list items%}
                <tr class="vertical-align{%if .block == 1%} warning{%endif%}">
                    {%ifset __fast_delete%}<th class="checkbox_field"><div class="checkbox"><input class="styled" name="elements[]" type="checkbox" value="{%.ID%}" /><label></label></div></th>{%endif%}
                    {%ifset .ip%}<th><a href="/backend/{%language_name%}/index/link/{%controllerName%}/edit/{%.ID%}">{%.ip%}</a></th>{%endif%}
                    {%ifset .browser%}<th>{%.browser%}</a></th>{%endif%}
                    {%ifset .updated%}<th>{%.updated%}</a></th>{%endif%}
                </tr>
            {%end%}
        </tbody>
    </table>
</div>