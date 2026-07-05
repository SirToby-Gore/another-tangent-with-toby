#!/usr/bin/env python3
import os
import re
import argparse
import shutil


def reindex_episodes(directory, width):
    if not os.path.isdir(directory):
        print(f"Error: Directory '{directory}' does not exist.")
        return

    # Pattern to match your files like ep-001.php or ep-2.php
    file_pattern = re.compile(r'^ep-(\d+)\.php$')

    # Internal text pattern to target: "episode_number" => "12" or "episode_number"=>"2"
    php_num_pattern = re.compile(r'("episode_number"\s*=>\s*")\d+(")')

    # 1. Gather all target files and extract their original numbering order
    found_files = []
    for filename in os.listdir(directory):
        match = file_pattern.match(filename)
        if match:
            orig_num = int(match.group(1))
            found_files.append((orig_num, filename))

    if not found_files:
        print("No matching episode files found.")
        return

    # Sort files strictly by their original numeric order
    found_files.sort(key=lambda x: x[0])

    print(f"Found {len(found_files)} episodes to process.")

    # Create a clean processing staging sandbox inside your target layout
    staging_dir = os.path.join(directory, "_reindex_staging")
    os.makedirs(staging_dir, exist_ok=True)

    try:
        # 2. Map file sequence sequentially starting straight from 1
        for index, (orig_num, filename) in enumerate(found_files, start=1):
            src_path = os.path.join(directory, filename)

            # Format the filename padding using the custom width argument
            new_filename = f"ep-{str(index).zfill(width)}.php"
            dest_path = os.path.join(staging_dir, new_filename)

            with open(src_path, 'r', encoding='utf-8') as f:
                content = f.read()

            # Using a lambda function completely sidesteps the \1 Group Reference bug
            updated_content = php_num_pattern.sub(
                lambda m: f'{m.group(1)}{index}{m.group(2)}',
                content
            )

            with open(dest_path, 'w', encoding='utf-8') as f:
                f.write(updated_content)

            print(
                f" Staged: {filename} -> {new_filename} (Internal ID: {index})")

        # 3. Clean up legacy versions from the target production path
        for _, filename in found_files:
            os.remove(os.path.join(directory, filename))

        # 4. Migrate the sequential staging files directly into production
        for filename in os.listdir(staging_dir):
            shutil.move(os.path.join(staging_dir, filename),
                        os.path.join(directory, filename))

        print(
            "\nSuccess: All show manifests successfully compacted and sequentially ordered.")

    finally:
        # Clean up the sandbox container if anything goes wrong
        if os.path.exists(staging_dir):
            shutil.rmtree(staging_dir)


if __name__ == "__main__":
    parser = argparse.ArgumentParser(
        description="Sequentially re-index episode manifests from 1.")
    parser.add_argument(
        '--directory',
        default='.',
        help="The file path target directory containing your ep-xxx.php files (Default: current directory)"
    )
    parser.add_argument(
        '--width',
        type=int,
        default=3,
        help="The dynamic string padding width for the zero-filled file name indices (Default: 3)"
    )

    args = parser.parse_args()
    reindex_episodes(args.directory, args.width)
