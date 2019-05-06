<div class="block block--advantages" id="advantages">
    <div class="advantages container">
        <div class="title">{%title%}</div>

        {%list advantages%}
            <div class="advantage service">
                <div class="service__title">{%.title%}</div>
                <div class="service__icon circle-icon circle-icon--left">
                    <div class="circle-icon__circle">
                        <img class="circle-icon__image" width="50" height="50" src="/img/images/Advantage/{%.ID%}/{%.base_image%}" alt="{%.title%}">
                    </div>
                </div>
                <div class="service__text">{%.text%}</div>
            </div>
        {%end%}
    </div>
</div>