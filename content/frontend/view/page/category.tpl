{%include head-start%}
    {%controller category/meta%}
{%include head-end%}
{%include body-start%}
    {%include header%}
        <div id="content" class="page--category">
            <div class="layout container">
                <div class="column" id="sidebar">
                    {%controller category/list category_list cache%}
                </div>
                <div class="column">
                    {%controller category/info category%}
                </div>
            </div>

            {%controller infoblocks/contacts_form contacts_form cache%}
            {%controller infoblocks/contacts contacts cache%}
        </div>
    {%include footer%}
{%include body-end%}
