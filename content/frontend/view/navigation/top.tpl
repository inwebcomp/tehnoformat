<nav class="menu">
    <ul class="menu__items">
        {%list items%}
            <li class="menu__item {%ifset ._itemsTree%} menu__item--with-submenu{%endif%}">
                <a href="{%.href%}" class="menu__link link" onclick="document.getElementsByTagName('body')[0].classList = {}">{%.title%}</a>
            </li>
        {%end%}
    </ul>
</nav>