<div class="block block--steps" id="steps">
    <div class="container">
        <div class="title">{%title%}</div>

        <div class="steps">
            {%list steps%}
                <div class="step">
                    <div class="step__title">{%.title%}</div>
                    <div class="step__text">{%.text%}</div>
                    <div class="step__icon">
                        <img src="/img/images/Step/{%.ID%}/{%.base_image%}" alt="{%.title%}" width="40" height="40">
                    </div>
                </div>
            {%end%}
        </div>
    </div>
</div>