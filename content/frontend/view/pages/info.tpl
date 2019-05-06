{%includeview textblock%}

{%ifset admin%}
    <div class="save-button" @click="saveTextpage({%ID%})">
        <i class="fa fa-save"></i> {{ saveButtonText }}
    </div>
{%endif%}