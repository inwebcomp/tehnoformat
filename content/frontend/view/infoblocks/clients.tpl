<div id="clients" class="block block--clients">
    <div class="container">
        <div class="block__header">
            <div class="title">{%title%}</div>

            <div class="circle-icon">
                <div class="circle-icon__circle">
                    <img class="circle-icon__image" width="50" height="50" src="/img/content/icons/pin.svg" alt="Clients">
                </div>

                <div class="block__header__lines"></div>
            </div>

            <div class="subtitle">{%description%}</div>
        </div>

        <div class="clients">
            {%list clients%}
                <div class="client">
                    <img src="/img/images/Client/{%.ID%}/index/{%.base_image%}" alt="{%.title%}" width="250" height="110" class="client__image">
                </div>
            {%end%}
        </div>
    </div>
</div>