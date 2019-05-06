<nav class="header__categories">
    {%list categories%}
        <a href="{%.href%}" class="header__category link">{%.title%}</a>
    {%end%}
</nav>