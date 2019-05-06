<div class="block block--process" id="steps">
    <div class="title">{%lang Как мы работаем%}</div>

    <div class="process container">
        {%list steps%}
            <div class="step">
                <div class="step__overflow">
                    <div class="step__icon">
                        <img src="/img/images/Step/{%.ID%}/{%.base_image%}" width="48" height="48" alt="{%.title%}">
                    </div>
                    <div class="step__title">{%.title%}</div>
                    <div class="step__text">{%.text%}</div>
                    <div class="step__index">0{%._index%}</div>
                </div>
            </div>
        {%end%}
    </div>
</div>