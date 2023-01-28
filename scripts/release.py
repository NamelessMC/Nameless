import subprocess
from subprocess import PIPE
import itertools
import shutil
from pathlib import Path
import sys


EXCLUDE_DIRS = [
    '.git',
    '.github',
    '.idea',
    '.node_cache',
    '.vscode',
    'node_modules',
    'release',
]
EXCLUDE_FILES = [
    '.gitignore',
    '.phpcs.xml',
    'CHANGELOG.md',
    'composer.json',
    'composer.lock',
    'CONTRIBUTING.md',
    'docker-compose.yaml',
    'Dockerfile.phpdoc',
    'nginx.example',
    'package.json',
    'package-lock.json',
    'phpstan.neon',
    'postinstall.js',
    'README.md',
    'release.sh',
    'yarn-postinstall.js',
    'yarn.lock',
    'SECURITY.md',
    'semantic.json',
    'web.config.example',
]

def create_archives(archive_path: str, cwd: str = '.'):
    zip_command = [
        'zip',
        '-r',  # Recursive
        '-q',  # Quiet
        f'{archive_path}.zip',
        '.',
        *itertools.chain.from_iterable((('-x', f'{name}/*') for name in EXCLUDE_DIRS)),
        *itertools.chain.from_iterable((('-x', f'{name}') for name in EXCLUDE_FILES)),
    ]

    tar_command = [
        'tar',
        '-c',  # Compress
        '-J',  # Use xzip
        '--owner=0',  # Set owner inside archive to root
        '--group=0',
        '-f', f'{archive_path}.tar.xz',
        *[f'--exclude={name}' for name in [*EXCLUDE_DIRS, *EXCLUDE_FILES]],
        '.',
    ]

    subprocess.check_call(zip_command,
                          shell=False,
                          cwd=cwd)

    print(f'Creating tar.xz archive: {archive_path}')
    subprocess.check_call(tar_command,
                          env={'XZ_DEFAULTS': '-T 0'},  # Enable multithreading
                          shell=False,
                          cwd=cwd)


if __name__ == '__main__':
    if not Path('.git').exists():
        print('.git does not exist')
        sys.exit(1)

    print('Deleting vendor files')
    shutil.rmtree('core/assets/vendor', ignore_errors=True)
    shutil.rmtree('node_modules', ignore_errors=True)
    shutil.rmtree('vendor', ignore_errors=True)

    # Create base archive
    create_archives('release/nameless-base')

    # Run npm and composer (production dependencies only)
    subprocess.check_call(['npm', 'ci', '-q', '--cache', '.node_cache'],
                          stdout=PIPE)
    subprocess.check_call(['composer', 'install', '--no-dev', '--no-interaction'],
                          stdout=PIPE)
    create_archives('release/nameless-deps-dist')

    # Create archive with files changed since last update

    upgrade_temp = Path('release', 'upgrade_temp')

    # Find previous tag
    previous_tag_command = ['git', 'describe', '--abbrev=0', '--tags']
    previous_tag = subprocess.check_output(previous_tag_command, shell=False)[:-1].decode()

    print('Creating files for upgrade from', previous_tag)

    # Find all files changed between previous tag and HEAD (current state)
    changed_command = ['git', 'diff', previous_tag, 'HEAD', '--name-only', '--diff-filter=d']
    changed_files = subprocess.check_output(changed_command, shell=False)[:-1].decode()

    for changed_file in changed_files.split('\n'):
        changed_file_target = Path(upgrade_temp, changed_file)
        changed_file_target.parent.mkdir(parents=True, exist_ok=True)
        shutil.copy2(changed_file, changed_file_target)

    # Vendor files are always included
    for vendor_dir in ['vendor', 'core/assets/vendor']:
        shutil.copytree(vendor_dir, Path(upgrade_temp, vendor_dir))

    create_archives('../upgrade-from-' + previous_tag, cwd=upgrade_temp.as_posix())

    # Run composer again, to install development dependencies
    subprocess.check_call(['composer', 'install', '--no-interaction'],
                          stdout=PIPE)
    create_archives('release/nameless-deps-dev')
