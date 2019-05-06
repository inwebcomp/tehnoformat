{%include header_free_style%}

<script type="text/javascript" src="https://www.google.com/jsapi"></script>

<div class="dashboard">
    {%ifset _param_count_visits%}{%controller statistics/online online%}{%endif%}
    {%ifset _param_count_visits%}{%controller statistics/new_visitors new_visitors%}{%endif%}
    {%ifset _param_users%}{%controller statistics/new_users new_users%}{%endif%}
    {%ifset _param_followers%}{%controller statistics/new_followers new_followers%}{%endif%}
    
	{%ifset _param_count_visits%}{%controller statistics/visitors visitors%}{%endif%}
</div>

{%include footer_free_style%}