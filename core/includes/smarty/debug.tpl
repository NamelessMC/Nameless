{capture name='_smarty_debug' assign=debug_output}
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <title>Smarty debugging console</title>
        <style>
            * {
                box-sizing: border-box;
            }
            body {
                margin: 0;
                background-color: #eeeeee;
                font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
                font-size: 0.8rem;
            }
            body > header {
                margin-bottom: 5rem;
                display: flex;
                background-color: rgba(255, 255, 255, 0.733);
                backdrop-filter: blur(4px);
                padding: 0.2rem 0.6rem;
                min-height: 3rem;
                position: fixed;
                width: 100%;
                align-items: center;
                top: 0;
            }
            body > header > h1 {
                margin: 0;
                font-size: 1.2rem;
            }
            body > header > h2 {
                margin: 0;
                margin-left: auto;
                font-size: 1rem;
            }
            body > main {
                margin: 0 2rem;
                margin-top: 5rem;
            }
            body > main > section {
                background-color: #fff;
                box-shadow: 0 1px 7px -2px #0002;
                margin-bottom: 1rem;
                border-radius: .4rem;
                display: flex;
                flex-direction: column;
            }
            body > footer {
                width: 100%;
                text-align: center;
                padding-top: .5rem;
            }
            body > main > section > h3 {
                margin-top: 1rem;
                margin-bottom: 1.2rem;
                margin-left: 1rem;
            }
            .opccion {
                display: flex;
                width: 100%;
                padding: .2rem;
            }
            .opccion > * {
                display: flex;
                flex-direction: column;
                width: 100%;
                margin: .2rem;
            }
            .opccion > * {
                display: flex;
                flex-direction: column;
                width: 100%;
            }
            .oscuro {
                background-color: #7c7c7c17;
            }
            .claro {
                background-color: #fafafa26;
            }
            span {
                margin: 0;
            }
            span.code {
                background-color: #20232a;
                color: #0eab1a;
                padding: 0.2rem 1rem;
                border-radius: .2rem;

            }
            span.code > pre {
                white-space: normal;
            }
        </style>
    </head>
    <body>
        <header>
            <h1>Smarty &bull; Debug</h1>
            <h2>{if isset($template_name)}{$template_name|debug_print_var nofilter} {/if}</h2>
        </header>
        <main>
            <section>
                <h3>Assigned template variables</h3>


                {foreach $assigned_vars as $vars}
                    <div class="opccion {if $vars@iteration % 2 eq 0}oscuro{else}claro{/if}">
                        <div>
                            <h5 style="margin: 0; margin-bottom: auto;">
                                ${$vars@key}
                            </h5>
                            <small>
                                {if isset($vars['nocache'])}<b>Nocache</b><br />{/if}
                                {if isset($vars['scope'])}<b>Origin:</b> {$vars['scope']|debug_print_var nofilter}{/if}
                            </small>
                        </div>
                        <div>
                            <span class="code"><pre><code>{$vars['value']|debug_print_var:10:80 nofilter}</code></pre></span>
                            {if isset($vars['attributes'])}
                                <dl>
                                    <h3>Attributes</h3>
                                    {$vars['attributes']|debug_print_var nofilter}
                                </dl>
                            {/if}
                        </div>
                    </div>
                {/foreach}
            </section>
            {if !empty($template_data)}
                <section>
                    <h3>Assigned template variables</h3>
                    {foreach $template_data as $template}
                        <div class="opccion {if $template@iteration % 2 eq 0}oscuro{else}claro{/if}">
                            <div>
                                <h5 style="margin: 0; margin-bottom: auto;">
                                    {$template.name}
                                </h5>
                                <small>
                                    (compile {$template['compile_time']|string_format:"%.5f"}) (render {$template['render_time']|string_format:"%.5f"}) (cache {$template['cache_time']|string_format:"%.5f"})
                                </small>
                            </div>
                        </div>
                    {/foreach}
                </section>
            {/if}
            {if !empty($config_vars)}
                <section>
                    <h3>Assigned config file variables</h3>
                    {foreach $config_vars as $vars}
                        <div class="opccion {if $vars@iteration % 2 eq 0}oscuro{else}claro{/if}">
                            <div>
                                <h5 style="margin: 0; margin-bottom: auto;">
                                    #{$vars@key}#
                                </h5>
                                <small>
                                    {if isset($vars['scope'])}<b>Origin:</b> {$vars['scope']|debug_print_var nofilter}{/if}
                                </small>
                            </div>
                            <div>
                                <span>{$vars['value']|debug_print_var:10:80 nofilter}</span>
                            </div>
                        </div>
                    {/foreach}
                </section>
            {/if}
        </main>
    </body>
    </html>
{/capture}
<script type="text/javascript">
    {$id = '__Smarty__'}
    {if $display_mode}{$id = "$offset$template_name"|md5}{/if}
    _smarty_console = window.open("", "console{$id}", "width=800,height=600,left={$offset},top={$offset},resizable,scrollbars=yes");
    _smarty_console.document.write("{$debug_output|escape:'javascript' nofilter}");
    _smarty_console.document.close();
</script>
