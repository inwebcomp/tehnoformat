<div class="block block--services">
    <div class="services container">
        {%list services%}
            <div class="service">
                {%ifset .href%}
                    <a href="{%.href%}" class="service__title">{%.title%}</a>
                {%else%}
                    <div class="service__title">{%.title%}</div>
                {%endif%}
                <div class="service__icon circle-icon">
                    <div class="circle-icon__circle">
                        <img class="circle-icon__image" width="50" height="50" src="/img/images/Service/{%.ID%}/{%.base_image%}" alt="{%.title%}">
                    </div>
                </div>
                <div class="service__text">{%.text%}</div>
                {%ifset .href%}
                    <a href="{%.href%}" class="service__link">{%lang Подробнее%}</a>
                {%endif%}
            </div>
        {%end%}
    </div>
</div>