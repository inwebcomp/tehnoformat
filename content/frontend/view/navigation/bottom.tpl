<nav class="menu">
    <ul>
        {%list items%}
        <li class="menu__item {%ifset ._itemsTree%} menu__item--with-submenu{%endif%}">
            <a href="{%.href%}#{%.name%}" class="menu__link" @click="scrollToBlock($event, '{%.name%}')">{%.title%}</a>
            {%ifset ._itemsTree%}<div class="menu__submenu-toggler" onclick="$(this).parent().toggleClass('menu__item--active')"></div>{%endif%}

            {%block ._itemsTree%}
            <ul class="submenu{%if .size > 100%} submenu--with-columns{%endif%}" style="width: {%.size%}%">
                {%list .items%}
                <li><a href="{%.href%}" class="submenu__link">{%.title%}</a></li>
                {%end%}
            </ul>
            {%end%}
        </li>
        {%end%}
    </ul>
</nav>