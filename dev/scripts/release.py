import subprocess
from subprocess import PIPE
import itertools
import shutil
from pathlib import Path
import sys
import tempfile


INCLUDE_DIRS = [
    'cache',
    'core',
    'custom',
    'dev',
    'modules',
    'uploads',
    'vendor',
]

INCLUDE_FILES = [
    '403.php',
    '404.php',
    'index.php',
    'install.php',
    'LICENSE.txt',
    'rewrite_test.php',
]

def create_archives(archive_name: str, archive_source: str = '.'):
    archive_path: str = Path('release', archive_name).absolute().as_posix()

    print('Archive: Copy files to temp dir')
    archive_temp = tempfile.mkdtemp()

    for include_file in INCLUDE_FILES:
        include_path = Path(archive_source, include_file)
        if include_path.exists():
            shutil.copy(include_path, archive_temp)

    for include_dir in INCLUDE_DIRS:
        include_path = Path(archive_source, include_dir)
        if include_path.exists():
            shutil.copytree(include_path, Path(archive_temp, include_dir))

    if Path(archive_source, 'vendor').exists():
        print('Archive: Generate checksums')
        subprocess.check_call(['php', 'dev/scripts/generate_checksums.php'],
                              shell=False,
                              cwd=archive_temp)

    print('Archive: Creating .zip file')
    zip_command = [
        'zip',
        '-r',  # Recursive
        '-q',  # Quiet
        f'{archive_path}.zip',
        '.',
    ]
    subprocess.check_call(zip_command, shell=False, cwd=archive_temp)

    print('Archive: Creating .tar.xz file')

    tar_command = [
        'tar',
        '-c',  # Compress
        '-J',  # Use xzip
        '--owner=0',  # Set owner inside archive to root
        '--group=0',
        '-f', f'{archive_path}.tar.xz',
        '.',
    ]

    subprocess.check_call(tar_command, shell=False, cwd=archive_temp,
                          env={'XZ_DEFAULTS': '-T 0'})  # Enable multithreading

    shutil.rmtree(archive_temp)


if __name__ == '__main__':
    if not Path('.git').exists():
        print('.git does not exist')
        sys.exit(1)

    print('Deleting vendor files')
    shutil.rmtree('core/assets/vendor', ignore_errors=True)
    shutil.rmtree('node_modules', ignore_errors=True)
    shutil.rmtree('vendor', ignore_errors=True)

    # Create base archive
    create_archives('nameless-base')

    # Run npm and composer (production dependencies only)
    subprocess.check_call(['npm', 'ci', '-q', '--cache', '.node_cache'])
    subprocess.check_call(['composer', 'update'])
    subprocess.check_call(['composer', 'install', '--no-dev', '--no-interaction'])

    create_archives('nameless-deps-dist')

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

    create_archives('upgrade-from-' + previous_tag, archive_source=upgrade_temp.as_posix())

    # Run composer again, to install development dependencies
    subprocess.check_call(['composer', 'install', '--no-interaction'])
    create_archives('nameless-deps-dev')
