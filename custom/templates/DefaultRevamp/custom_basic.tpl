{include file='header.tpl'}
{$CONTENT}
{foreach from=$TEMPLATE_JS item=script}
    {$script}
{/foreach}
</body>
</html>