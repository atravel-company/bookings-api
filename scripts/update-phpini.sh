#!/bin/bash
#
# This script updates the upload_max_filesize value in the PHP CLI configuration.
# It checks if the value is set to a predefined constant and, if not, changes it to that value.

PHP_INI="/etc/php/7.4/cli/php.ini"
UPLOAD_MAX_FILESIZE="30M"  # Define the constant here

# Verify that the php.ini file exists.
if [ ! -f "$PHP_INI" ]; then
    echo "Error: php.ini not found at $PHP_INI"
    exit 1
fi

# Check if upload_max_filesize is set to the defined constant.
if grep -qE "^[[:space:]]*upload_max_filesize[[:space:]]*=[[:space:]]*${UPLOAD_MAX_FILESIZE}" "$PHP_INI"; then
    echo "upload_max_filesize is already set to ${UPLOAD_MAX_FILESIZE}. No changes made."
else
    echo "upload_max_filesize is not set to ${UPLOAD_MAX_FILESIZE}. Updating it to ${UPLOAD_MAX_FILESIZE}..."

    # Backup the current php.ini file.
    cp "$PHP_INI" "${PHP_INI}.bak"

    # Update the setting using sed.
    sed -i -E "s/^[[:space:]]*(upload_max_filesize[[:space:]]*=[[:space:]]*).*/\1${UPLOAD_MAX_FILESIZE}/" "$PHP_INI"
    
    echo "Update complete: upload_max_filesize is now set to ${UPLOAD_MAX_FILESIZE}."
fi
