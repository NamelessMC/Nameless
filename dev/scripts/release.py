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
    '.htaccess',
    '403.php',
    '404.php',
    'index.php',
    'install.php',
    'LICENSE.txt',
    'rewrite_test.php',
]

def generate_checksums(cwd):
    subprocess.check_call(['php', 'dev/scripts/generate_checksums.php'],
                          shell=False,
                          cwd=cwd)


def create_archive_dir(archive_source: str = '.') -> str:
    # Copy all files that should be included to a temporary directory
    archive_temp = tempfile.mkdtemp(prefix='nameless')

    for include_file in INCLUDE_FILES:
        include_path = Path(archive_source, include_file)
        if include_path.exists():
            shutil.copy(include_path, archive_temp)

    for include_dir in INCLUDE_DIRS:
        include_path = Path(archive_source, include_dir)
        if include_path.exists():
            shutil.copytree(include_path, Path(archive_temp, include_dir))

    return archive_temp


def create_archives(archive_name, cwd):
    archive_path: str = Path('release', archive_name).absolute().as_posix()

    print('Archive: Creating .zip file')
    zip_command = [
        'zip',
        '-r',  # Recursive
        '-q',  # Quiet
        f'{archive_path}.zip',
        '.',
    ]
    subprocess.check_call(zip_command, shell=False, cwd=cwd)

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

    subprocess.check_call(tar_command, shell=False, cwd=cwd,
                          env={'XZ_DEFAULTS': '-T 0'})  # Enable multithreading


def regenerate_vendor_files():
    print('Re-generating vendor files')
    shutil.rmtree('core/assets/vendor', ignore_errors=True)
    subprocess.check_call(['composer', 'update'])
    subprocess.check_call(['composer', 'install', '--no-dev', '--no-interaction'])


def create_deps_dist_archive():
    print('Creating nameless-deps-dist archive')
    # Copy the required files to a new temporary directory
    deps_dist_temp = create_archive_dir()
    # Generate checksums
    generate_checksums(deps_dist_temp)
    # Create .zip and .tar.xz archives
    create_archives('nameless-deps-dist', deps_dist_temp)
    # Temporary directory can now be deleted
    shutil.rmtree(deps_dist_temp)


def always_in_update_package(relative_path: str) -> bool:
    return relative_path == 'checksums.json' or relative_path.startswith('vendor/') or relative_path.startswith('core/assets/vendor')


def create_upgrade_archive():
    print('Creating update archive')

    # Copy the required files to a new temporary directory
    upgrade_temp = create_archive_dir()
    # Generate checksums
    generate_checksums(upgrade_temp)

    # Find previous tag
    previous_tag_command = ['git', 'describe', '--abbrev=0', '--tags']
    previous_tag = subprocess.check_output(previous_tag_command, shell=False)[:-1].decode()

    print('Creating files for upgrade from', previous_tag)

    # Find all files changed between previous tag and HEAD (current state)
    changed_command = ['git', 'diff', previous_tag, 'HEAD', '--name-only', '--diff-filter=d']
    changed_files_output = subprocess.check_output(changed_command, shell=False)[:-1]
    changed_files = set(changed_files_output.decode().split('\n'))

    # Delete any files that have not been changed
    for path in Path(upgrade_temp).rglob("*"):
        relative_path = path.as_posix()[len(upgrade_temp)+1:]
        if (
            not always_in_update_package(relative_path) and
            relative_path not in changed_files and
            path.is_file()
        ):
            path.unlink()

    # Delete empty directoryes
    subprocess.check_call(['find', '.', '-type', 'd', '-empty', '-delete'], cwd=upgrade_temp)

    # Create .zip and .tar.xz archives
    create_archives('upgrade-from-' + previous_tag, upgrade_temp)


def create_deps_dev_archive():
    print('Creating nameless-deps-dev archive')
    # Copy the required files to a new temporary directory
    deps_dev_temp = create_archive_dir()
    # Generate checksums
    generate_checksums(deps_dev_temp)
    # Create .zip and .tar.xz archives
    create_archives('nameless-deps-dev', deps_dev_temp)
    # Temporary directory can now be deleted
    shutil.rmtree(deps_dev_temp)


if __name__ == '__main__':
    if not Path('.git').exists():
        print('.git does not exist')
        sys.exit(1)

    # Re-generate vendor files (without development dependencies)
    regenerate_vendor_files()

    # Create nameless-deps-dist archive with production dependencies only
    create_deps_dist_archive()

    # Create update archive (files changed since last tag)
    create_upgrade_archive()

    # Run composer again, to install development dependencies
    subprocess.check_call(['composer', 'install', '--no-interaction'])
    # Create nameless-deps-dev archive with development dependencies
    create_deps_dev_archive()
