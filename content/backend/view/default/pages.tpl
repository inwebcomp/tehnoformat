{%block select%}                   
    <nav class="cms_pagination">
        <ul class="pagination">
            {%list .pages%}
                <li{%ifset .selected%} class="active"{%endif%}>
                    <a href="javascript:void(0)" rel="{%.link%}">{%if .name == "back"%}<span aria-hidden="true">&laquo;</span>{%else%}{%if .name == "next"%}<span aria-hidden="true">&raquo;</span>{%else%}{%.name%}{%endif%}{%endif%}</a>
                </li>
            {%end%}
        </ul>
    </nav>
{%end%}