<form action="" method="post">
    <div class="form-group">
        <label for="inputDarkMode">{$DARK_MODE}</label>
        <select name="darkMode" class="form-control" id="inputDarkMode">
            <option value="0"{if $DARK_MODE_VALUE eq '0'} selected{/if}>{$DISABLED}</option>
            <option value="1"{if $DARK_MODE_VALUE eq '1'} selected{/if}>{$ENABLED}</option>
        </select>
    </div>
    <div class="form-group">
        <label for="inputNavbarColour">{$NAVBAR_COLOUR}</label>
        <select name="navbarColour" class="form-control" id="inputNavbarColour">
            <option value="white"{if $NAVBAR_COLOUR_VALUE eq 'white'} selected{/if}>{$DEFAULT}</option>
            <option value="red"{if $NAVBAR_COLOUR_VALUE eq 'red'} selected{/if}>{$RED}</option>
            <option value="orange"{if $NAVBAR_COLOUR_VALUE eq 'orange'} selected{/if}>{$ORANGE}</option>
            <option value="yellow"{if $NAVBAR_COLOUR_VALUE eq 'yellow'} selected{/if}>{$YELLOW}</option>
            <option value="olive"{if $NAVBAR_COLOUR_VALUE eq 'olive'} selected{/if}>{$OLIVE}</option>
            <option value="green"{if $NAVBAR_COLOUR_VALUE eq 'green'} selected{/if}>{$GREEN}</option>
            <option value="teal"{if $NAVBAR_COLOUR_VALUE eq 'teal'} selected{/if}>{$TEAL}</option>
            <option value="blue"{if $NAVBAR_COLOUR_VALUE eq 'blue'} selected{/if}>{$BLUE}</option>
            <option value="violet"{if $NAVBAR_COLOUR_VALUE eq 'violet'} selected{/if}>{$VIOLET}</option>
            <option value="purple"{if $NAVBAR_COLOUR_VALUE eq 'purple'} selected{/if}>{$PURPLE}</option>
            <option value="pink"{if $NAVBAR_COLOUR_VALUE eq 'pink'} selected{/if}>{$PINK}</option>
            <option value="brown"{if $NAVBAR_COLOUR_VALUE eq 'brown'} selected{/if}>{$BROWN}</option>
            <option value="grey"{if $NAVBAR_COLOUR_VALUE eq 'grey'} selected{/if}>{$GREY}</option>
        </select>
    </div>
    <div class="form-group">
        <input type="hidden" name="token" value="{$TOKEN}">
        <input type="submit" class="btn btn-primary" value="{$SUBMIT}">
    </div>
</form>