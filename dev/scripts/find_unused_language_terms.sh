#!/usr/bin/env bash
if [ ! -d 'custom' ]
then
    echo "You need to run this script from the Nameless repo root (scripts/find-unused-language-terms.sh)"
    exit 1
fi

UNUSED_TERMS_FOUND=false
KEYS=$(jq -M -r 'keys | .[]' custom/languages/en_UK.json)

for KEY in $KEYS
do
    BEFORE_SLASH="${KEY%%/*}"
    if [ "$BEFORE_SLASH" = "time" ]
    then
        continue
    fi

    AFTER_SLASH="${KEY#*/}"

    if ! grep -r --exclude-dir=.git --exclude-dir=vendor --exclude-dir=cache --exclude-dir=node_modules \
            -e "get('$BEFORE_SLASH', '$AFTER_SLASH'" \
            -e "get(\"$BEFORE_SLASH\", '$AFTER_SLASH'" \
            -e "get('$BEFORE_SLASH', \"$AFTER_SLASH\"" \
            -e "get(\"$BEFORE_SLASH\", \"$AFTER_SLASH\"" \
            -e "'$AFTER_SLASH'," \
            -e "= '$AFTER_SLASH'" \
            -e "\['$AFTER_SLASH'\]" \
            -e "\['$BEFORE_SLASH', '$AFTER_SLASH'\]" \
            > /dev/null
    then
        UNUSED_TERMS_FOUND=true
        echo "$KEY"
    fi
done

if $UNUSED_TERMS_FOUND
then
    echo "Unused translation terms found."
    exit 1
fi

echo "No unused translation terms found!"
exit 0
