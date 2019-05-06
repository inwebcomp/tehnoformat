<div class="block block--contacts" id="contacts">
    <div class="container">
        <div class="index-contacts">
            <div class="index-contact index-contact--phone">
                <div class="index-contact__icon circle-icon circle-icon--center">
                    <div class="circle-icon__circle">
                        <img class="circle-icon__image" width="40" height="40" src="/img/content/icons/phone.svg" alt="Phone">
                    </div>
                </div>
                <div class="index-contact__info">
                    <div class="index-contact__label">{%lang Телефон%}</div>
                    <div class="index-contact__value">{%config_phone%}</div>
                </div>
            </div>
            <div class="index-contact">
                <div class="index-contact__icon circle-icon circle-icon--center">
                    <div class="circle-icon__circle">
                        <img class="circle-icon__image" width="40" height="40" src="/img/content/icons/message.svg" alt="Email">
                    </div>
                </div>
                <div class="index-contact__info">
                    <div class="index-contact__label">{%lang Эл. почта%}</div>
                    <div class="index-contact__value">{%config_email%}</div>
                </div>
            </div>
            <div class="index-contact">
                <div class="index-contact__icon circle-icon circle-icon--center">
                    <div class="circle-icon__circle">
                        <img class="circle-icon__image" width="40" height="40" src="/img/content/icons/pin.svg" alt="Address">
                    </div>
                </div>
                <div class="index-contact__info">
                    <div class="index-contact__label">{%lang Адрес%}</div>
                    <div class="index-contact__value">{%config_address%}</div>
                </div>
            </div>

            <div class="index-contact__all">
                <a href="{%pagepath contacts%}" class="button button--ghost">{%lang Все контакты%}</a>
            </div>
        </div>
    </div>
</div>