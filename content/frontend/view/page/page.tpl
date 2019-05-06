{%include head-start%}
    {%controller navigation/meta meta%}
{%include head-end%}
{%include body-start%}
    {%include header%}
        <div id="content">
            <div class="container">
                {%controller pages/info page%}
            </div>

            {%controller infoblocks/contacts_form contacts_form cache%}
            {%controller infoblocks/contacts contacts cache%}
        </div>
    {%include footer%}
{%include body-end%}
<style>
</style>
