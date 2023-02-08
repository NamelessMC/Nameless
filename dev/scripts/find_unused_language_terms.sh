#!/usr/bin/env bash
if [ ! -d 'custom' ]
then
    echo "You need to run this script from the Nameless repo root (scripts/find-unused-language-terms.sh)"
    exit 1
fi

UNUSED_TERMS_FOUND=false
FILES=(
  "custom/languages/en_UK.json"
  "modules/Forum/language/en_UK.json"
  "modules/Cookie Consent/language/en_UK.json"
  "modules/Discord Integration/language/en_UK.json"
)

for FILE in "${FILES[@]}"
do
  echo "Checking $FILE for unused terms..."
  KEYS=$(jq -M -r 'keys | .[]' "$FILE")
  for KEY in $KEYS
  do
      BEFORE_SLASH="${KEY%%/*}"
      AFTER_SLASH="${KEY#*/}"

      # If running on macOS, install GNU Grep via `brew install grep`, and change `grep` to `ggrep` below
      if ! grep -r --exclude-dir=.git --exclude-dir=vendor --exclude-dir=cache --exclude-dir=node_modules \
              -e "get('$BEFORE_SLASH', '$AFTER_SLASH'" \
              -e "get(\"$BEFORE_SLASH\", '$AFTER_SLASH'" \
              -e "get('$BEFORE_SLASH', \"$AFTER_SLASH\"" \
              -e "get(\"$BEFORE_SLASH\", \"$AFTER_SLASH\"" \
              -e "'$AFTER_SLASH'," \
              -e "= '$AFTER_SLASH'" \
              -e "=> '$AFTER_SLASH']" \
              -e "\['$AFTER_SLASH'\]" \
              -e "\['$BEFORE_SLASH', '$AFTER_SLASH'\]" \
              -e "? '$AFTER_SLASH'" \
              -e ": '$AFTER_SLASH'" \
              -e "getLanguageTerm('$AFTER_SLASH')" \
              > /dev/null
      then
          UNUSED_TERMS_FOUND=true
          echo "$FILE: $KEY"
      fi
  done
done

if $UNUSED_TERMS_FOUND
then
    echo "Unused translation terms found."
    exit 1
fi

echo "No unused translation terms found!"
exit 0
