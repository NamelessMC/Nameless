import sys
from pathlib import Path

# 2 letter country code: https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2
# 2 letter language code: https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes

if __name__ == '__main__':
    if len(sys.argv) != 2:
        print(f"Usage: {sys.argv[0]} <language code>_<country code>")
        sys.exit(1)

    name = sys.argv[1]

    modules = [e.name for e in Path('modules').iterdir() if e.name != 'Core']
    paths = [Path('modules', module, 'language', name + '.json') for module in modules]
    paths.append(Path('custom', 'languages', name + '.json'))

    for path in paths:
        with path.open('bw+') as f:
            f.write(b'{}\n')

    print("Done. Don't forget to add the language to core/classes/Core/Language.php")
