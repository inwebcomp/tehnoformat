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

            <a href="{%alternative_path%}" class="language link">{%other_language%}</a>
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
        </div>
    </div>
</header>

<a target="_blank" href="//www.termoformat.md" class="floating-button banner__button button button--accent">
    <i class="icon icon--cart banner__button__icon"></i>
    <div class="banner__button__info">
        <div class="banner__button__text">{%lang Посетите наш интернет магазин%}</div>

        <div class="banner__button__link">
            <i class="fas fa-link"></i>
            www.termoformat.md
        </div>
    </div>
</a>