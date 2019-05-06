<div class="list wow {%config_animation_effect_list%}">
    <table class="table table-hover">
        <thead>
            {%list items%}
                {%ifset ._first%}
                    <tr>
                    	<th class="numeration_field"></th>
                        <th>{%lang Заголовок%}</th>
                        <th>{%lang Описание%}</th>
                    </tr>
                {%endif%}
            {%end%}
        </thead>
        <tbody>
            {%list items%}
                <tr class="vertical-align{%if .block == 1%} warning{%endif%}">
                	<th class="numeration_field">{%._index%}</th>
                    <th><a href="/backend/{%language_name%}/index/link/{%controllerName%}/edit/{%.ID%}">{%.title%}</a></th>
                    <th>{%.description%}</th>
                </tr>
            {%end%}
        </tbody>
    </table>
</div>