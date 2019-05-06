<div class="categories-list">
    <a href="{%href%}" class="categories-list__header">
        <i class="icon icon--categories"></i>
        {%title%}
    </a>

    <div class="categories-list__items">
        {%list categories%}
            <a href="{%.href%}" class="categories-list__item{%if selected == .ID%} categories-list__item--active{%endif%}">{%.title%}</a>
        {%end%}
    </div>
</div>