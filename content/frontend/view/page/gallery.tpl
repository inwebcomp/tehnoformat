{%include head-start%}
    {%controller navigation/meta meta%}
{%include head-end%}
{%include body-start%}
    {%include header%}
        <div id="content">
            {%controller gallery/items gallery_items cache%}

            {%controller infoblocks/contacts_form contacts_form cache%}
            {%controller infoblocks/contacts contacts cache%}
        </div>
    {%include footer%}
{%include body-end%}