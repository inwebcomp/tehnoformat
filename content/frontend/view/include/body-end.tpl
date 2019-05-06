            {%include popups%}
        </div>

        <script src="//code.jquery.com/jquery-3.3.1.min.js"></script>
        <script src="{%root%}/jslib/app.js?9" type="text/javascript"></script>

        {%if page_name_uniq == "index"%}
            {%js gallery-index%}
        {%endif%}
        {%if page_name_uniq == "gallery-category"%}
            {%js gallery%}
        {%endif%}
        {%if page_name_uniq == "page"%}
            {%ifset admin%}
                {%js editable-page%}
            {%endif%}
        {%endif%}
        {%if page_name_uniq == "category"%}
            {%ifset admin%}
                {%js editable-page%}
            {%endif%}
            {%js gallery%}
        {%endif%}
    </body>
</html>