{%include head-start%}
    {%controller navigation/meta meta%}
{%include head-end%}
{%include body-start%}
    {%include header%}
        <div id="content">
            {%controller contacts/map contacts_map cache%}
            {%controller contacts/items contacts_items cache%}
            {%controller infoblocks/contacts_form contacts_form cache%}
        </div>
    {%include footer%}
{%include body-end%}