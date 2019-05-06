{%include head-start%}
    {%controller navigation/meta meta%}
{%include head-end%}
{%include body-start%}
    {%include header%}
        <div id="content">
            {%controller infoblocks/banner banner cache%}
            {%controller category/items categories cache%}
            {%controller infoblocks/info info cache%}
            {%controller infoblocks/services services cache%}
            {%controller infoblocks/order order cache%}
            {%controller infoblocks/advantages advantages cache%}
            {%controller infoblocks/steps steps cache%}
            {%controller infoblocks/clients clients cache%}
            {%controller gallery/index gallery cache%}
            {%controller infoblocks/contacts_form contacts_form cache%}
            {%controller infoblocks/contacts contacts cache%}
        </div>
    {%include footer%}
{%include body-end%}