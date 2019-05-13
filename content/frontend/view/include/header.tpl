<header class="header">
    <div class="header__info">
        <div class="container">
            {%controller navigation/top navigation_top cache%}

            <div class="header__contacts">
                <a href="{%pagepath contacts%}" class="header__contact">
                    <i class="fas fa-map-marker-alt"></i>
                    {%config_address%}
                </a>
                <a target="_blank" href="mailto:{%config_email%}" class="header__contact">
                    <i class="fas fa-envelope"></i>
                    {%config_email%}
                </a>
                <a href="tel:{%phone_clean%}" class="header__contact header__contact--phone">
                    <i class="fas fa-phone"></i>
                    {%config_phone%}
                </a>
            </a>
            </div>

            <button class="header__button button" @click="showPopup('contact')">{%lang Отправить заявку%}</button>

            <a href="{%if other_language == default_language%}/{%else%}/{%other_language%}{%endif%}" class="language link">{%other_language%}</a>
        </div>
    </div>
    <div class="header__float">
        <div class="container">
            <a href="/{%if language_name !== config_default_language%}{%language_name%}{%endif%}" class="logo header__logo">
                <img src="/img/content/logo.png" width="240" height="60" alt="{%config_sitename%}" class="logo__img">
            </a>

            {%controller category/top category_top cache%}

            <button type="button" class="menu-toggler header__menu-toggler" onclick="document.getElementsByTagName('body')[0].classList.add('show-sidebar--menu')">
                <div class="icon icon--menu"></div>
            </button>

            <a href="tel:{%phone_clean%}" class="header__phone">
                <i class="icon icon--phone"></i>
                <div class="header__phone__text">{%config_phone%}</div>
            </a>

            <nav class="mob-menu">
                <div class="mob-menu__content">
                    <div class="mob-menu__main-list">
                        <a href="#" class="mob-menu__main-link">Вентиляция</a>
                        <a href="#" class="mob-menu__main-link">Вентиляция</a>
                        <a href="#" class="mob-menu__main-link">Вентиляция</a>
                        <a href="#" class="mob-menu__main-link">Вентиляция</a>
                        <a href="#" class="mob-menu__main-link">Вентиляция</a>
                        <a href="#" class="mob-menu__main-link">Вентиляция</a>
                    </div>

                    <div class="mob-menu__second-list">
                        <a href="#" class="mob-menu__link">Вентиляция</a>
                        <a href="#" class="mob-menu__link">Вентиляция</a>
                        <a href="#" class="mob-menu__link">Вентиляция</a>
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
    </div>
</header>