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
    AFTER_SLASH="${KEY#*/}"

    if ! grep -r --exclude-dir .git \
            -e "get('$BEFORE_SLASH', '$AFTER_SLASH')" \
            -e "get(\"$BEFORE_SLASH\", '$AFTER_SLASH')" \
            -e "get('$BEFORE_SLASH', \"$AFTER_SLASH\")" \
            -e "get(\"$BEFORE_SLASH\", \"$AFTER_SLASH\")" \
            > /dev/null
    then
        echo "$KEY"
    fi
done
