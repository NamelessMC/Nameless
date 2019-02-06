<div class="card">
  <div class="card-body">
    <h2>{$STATISTICS}</h2>
    {if isset($FORUM_STATISTICS)}
      <div>
        <span class="text-muted">{$TOTAL_THREADS}</span>
        <span class="float-right">{$TOTAL_THREADS_VALUE}</span>
      </div>
      <div>
        <span class="text-muted">{$TOTAL_POSTS}</span>
        <span class="float-right">{$TOTAL_POSTS_VALUE}</span>
      </div>
    {/if}
    <div>
      <span class="text-muted">{$USERS_REGISTERED}</span>
      <span class="float-right">{$USERS_REGISTERED_VALUE}</span>
    </div>
    <div>
      <span class="text-muted">{$LATEST_MEMBER}</span>
      <span class="float-right"><a style="{$LAST_MEMBER_VALUE.style}" href="{$LATEST_MEMBER_VALUE.profile}" data-poload="{$USER_INFO_URL}{$LATEST_MEMBER_VALUE.id}" data-html="true" data-placement="top">{$LATEST_MEMBER_VALUE.nickname}</a></span>
    </div>
  </div>
</div>
