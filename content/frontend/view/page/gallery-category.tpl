{%include head-start%}
    {%controller navigation/meta meta%}
{%include head-end%}
{%include body-start%}
    {%include header%}
        <div id="content">
            {%controller gallery/info gallery_info cache%}

            {%controller infoblocks/contacts_form contacts_form cache%}
            {%controller infoblocks/contacts contacts cache%}
        </div>
    {%include footer%}
{%include body-end%}