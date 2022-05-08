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
            <option value="white"{if $NAVBAR_COLOUR_VALUE eq 'white'} selected{/if}>{$NAVBAR_COLOUR_DEFAULT}</option>
            <option value="red"{if $NAVBAR_COLOUR_VALUE eq 'red'} selected{/if}>{$NAVBAR_COLOUR_RED}</option>
            <option value="orange"{if $NAVBAR_COLOUR_VALUE eq 'orange'} selected{/if}>{$NAVBAR_COLOUR_ORANGE}</option>
            <option value="yellow"{if $NAVBAR_COLOUR_VALUE eq 'yellow'} selected{/if}>{$NAVBAR_COLOUR_YELLOW}</option>
            <option value="olive"{if $NAVBAR_COLOUR_VALUE eq 'olive'} selected{/if}>{$NAVBAR_COLOUR_OLIVE}</option>
            <option value="green"{if $NAVBAR_COLOUR_VALUE eq 'green'} selected{/if}>{$NAVBAR_COLOUR_GREEN}</option>
            <option value="teal"{if $NAVBAR_COLOUR_VALUE eq 'teal'} selected{/if}>{$NAVBAR_COLOUR_TEAL}</option>
            <option value="blue"{if $NAVBAR_COLOUR_VALUE eq 'blue'} selected{/if}>{$NAVBAR_COLOUR_BLUE}</option>
            <option value="violet"{if $NAVBAR_COLOUR_VALUE eq 'violet'} selected{/if}>{$NAVBAR_COLOUR_VIOLET}</option>
            <option value="purple"{if $NAVBAR_COLOUR_VALUE eq 'purple'} selected{/if}>{$NAVBAR_COLOUR_PURPLE}</option>
            <option value="pink"{if $NAVBAR_COLOUR_VALUE eq 'pink'} selected{/if}>{$NAVBAR_COLOUR_PINK}</option>
            <option value="brown"{if $NAVBAR_COLOUR_VALUE eq 'brown'} selected{/if}>{$NAVBAR_COLOUR_BROWN}</option>
            <option value="grey"{if $NAVBAR_COLOUR_VALUE eq 'grey'} selected{/if}>{$NAVBAR_COLOUR_GREY}</option>
        </select>
    </div>
    <div class="form-group">
        <input type="hidden" name="token" value="{$TOKEN}">
        <input type="submit" class="btn btn-primary" value="{$SUBMIT}">
    </div>
</form>