<div class="languages">
    {%list items%}
        <a class="language{%if language_name== .name%} language--active{%endif%}" href="{%.href%}">{%.name%}</a>
    {%end%}
</div>