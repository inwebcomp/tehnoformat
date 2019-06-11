<body>
    <div class="sidebar__overlay" onclick="document.getElementsByTagName('body')[0].classList.remove('show-sidebar--menu')"></div>

    <div id="app" class="page--{%page_name_uniq%}">

        {%controller navigation/mobile navigation_mobile cache%}