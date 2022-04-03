#!/usr/bin/env bash
if [ ! -d 'custom' ]
then
    echo "You need to run this script from the Nameless repo root (scripts/find-unused-language-terms.sh)"
    exit 1
fi

KEYS=$(jq -M -r 'keys | .[]' custom/languages/en_UK.json)
for KEY in $KEYS
do
    BEFORE_SLASH="${KEY%%/*}"

    if [ "$BEFORE_SLASH" == "time" ]
    then
        continue;
    fi

    AFTER_SLASH="${KEY#*/}"

    if ! ggrep -r --exclude-dir=.git --exclude-dir=vendor \
            -e "get('$BEFORE_SLASH', '$AFTER_SLASH'" \
            -e "get(\"$BEFORE_SLASH\", '$AFTER_SLASH'" \
            -e "get('$BEFORE_SLASH', \"$AFTER_SLASH\"" \
            -e "get(\"$BEFORE_SLASH\", \"$AFTER_SLASH\"" \
            -e "'$AFTER_SLASH'," \
            -e "\['$AFTER_SLASH'\]" \
            > /dev/null
    then
        echo "$KEY"
    fi
done
