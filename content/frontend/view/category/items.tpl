<div id="categories" class="block block--categories">
    <div class="container">
        <div class="block__header">
            <div class="title">{%title%}</div>

            <div class="circle-icon">
                <div class="circle-icon__circle">
                    <img class="circle-icon__image" width="50" height="50" src="/img/content/icons/medal.svg" alt="Medal">
                </div>

                <div class="block__header__lines"></div>
            </div>

            <div class="subtitle">{%text%}</div>
        </div>

        <div class="categories">
            {%list categories%}
                <a href="{%.href%}" class="category">
                    <img src="/img/images/Category/{%.ID%}/index/{%.base_image%}" alt="{%.title%}" width="370" height="230" class="category__image">

                    <div class="category__content">
                        <div class="category__title">{%.title%}</div>
                        <i class="category__icon icon icon--info"></i>
                    </div>
                </a>
            {%end%}
        </div>
    </div>
</div>