{if isset($SUCCESS)}
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <h5><i class="icon fa fa-check"></i> {$SUCCESS_TITLE}</h5>
        {$SUCCESS}
    </div>
{/if}