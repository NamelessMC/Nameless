import subprocess
from subprocess import PIPE
import itertools
import shutil


EXCLUDE_DIRS = [
    '.git',
    '.github',
    '.idea',
    '.node-cache',
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
TARGET_DIR = 'release'


def create_archives(archive_name: str):
    zip_command = [
        'zip',
        '-r',  # Recursive
        '-q',  # Quiet
        f'{TARGET_DIR}/nameless-{archive_name}.zip',
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
        '-f', f'{TARGET_DIR}/nameless-{archive_name}.tar.xz',
        *[f'--exclude={name}' for name in [*EXCLUDE_DIRS, *EXCLUDE_FILES]],
        '.',
    ]

    print(f'Creating zip archive: {archive_name}')
    subprocess.check_call(zip_command,
                          shell=False)

    print(f'Creating tar.xz archive: {archive_name}')
    subprocess.check_call(tar_command,
                          env={'XZ_DEFAULTS': '-T 0'},  # Enable multithreading
                          shell=False)


if __name__ == '__main__':
    print('Deleting vendor files')
    shutil.rmtree('core/assets/vendor', ignore_errors=True)
    shutil.rmtree('node_modules', ignore_errors=True)
    shutil.rmtree('vendor', ignore_errors=True)

    # Create base archive
    create_archives('base')

    # Run npm and composer (production dependencies only)
    subprocess.check_call(['npm', 'ci', '-q', '--cache', '.node_cache'],
                          stdout=PIPE)
    subprocess.check_call(['composer', 'install', '--no-dev', '--no-interaction'],
                          stdout=PIPE)
    create_archives('deps-dist')

    # Run composer again, to install development dependencies
    subprocess.check_call(['composer', 'install', '--no-interaction'],
                          stdout=PIPE)
    create_archives('deps-dev')
