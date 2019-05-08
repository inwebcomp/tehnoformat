<div class="banner">
    <div class="container">
        <div class="banner__text">
            <h1 class="banner__header">{%banner_header%}</h1>
            <p class="banner__paragraph">{%banner_paragraph%}</p>

            <div class="banner__buttons">
                <a target="_blank" href="//www.termoformat.md" class="banner__button button button--accent">
                    <div class="banner__button__info">
                        <div class="banner__button__text">{%lang Посетите наш интернет магазин%}</div>

                        <div class="banner__button__link">
                            <i class="fas fa-link"></i>
                            www.termoformat.md
                        </div>
                    </div>
                    <i class="icon icon--cart banner__button__icon"></i>
                </a>

                <button class="button banner__btn-contact" @click="showPopup('contact')">{%lang Связаться с нами%}</button>
            </div>
        </div>
        <img class="banner__image" src="/img/content/flat.png" height="477" width="532" align="Flat image"/>
    </div>

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
</div>