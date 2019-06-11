<div class="sidebar sidebar--menu">
    <a href="/{%if language_name !== config_default_language%}{%language_name%}{%endif%}" class="logo header__logo mob-menu__logo">
        <img src="/img/content/logo.png" height="40" alt="{%config_sitename%}" class="logo__img">
    </a>

    <div class="sidebar__close" onclick="document.getElementsByTagName('body')[0].classList.remove('show-sidebar--menu')"><i class="fas fa-times"></i></div>

    <nav class="mob-menu sidebar__content">
        <div class="mob-menu__content">
            <div class="mob-menu__main-list">
                {%list categories%}
                    <a href="{%.href%}" class="mob-menu__main-link" onclick="document.getElementsByTagName('body')[0].classList = {}">{%.title%}</a>
                {%end%}
            </div>

            <footer class="mob-menu__footer">
                <a href="{%pagepath contacts%}" class="header__contact">
                    <i class="fas fa-map-marker-alt"></i>
                    {%config_address%}
                </a>
                <a target="_blank" href="mailto:{%config_email%}" class="header__contact">
                    <i class="fas fa-envelope"></i>
                    {%config_email%}
                </a>
                <button class="mob-menu__button button" @click="showPopup('contact')">{%lang Отправить заявку%}</button>
            </footer>
        </div>
    </nav>
</div>