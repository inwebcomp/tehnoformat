<div class="block block--order" id="order">
    <div class="order container">
        <div class="order__text">
            <div class="title">{%title%}</div>

            <div class="order__button-row">
                <i class="icon icon--order order__icon"></i>
                <div class="subtitle">{%description%}</div>
                <button class="button button--accent" @click="showPopup('order')">{%lang Заказать расчёт сметы%}</button>
            </div>
        </div>
        <img src="/img/content/man.png" alt="{%lang Расчёт сметы%}" class="order__image">
    </div>
</div>