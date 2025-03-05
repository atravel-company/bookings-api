#!/bin/bash

PHP_INI="/etc/php/7.4/cli/php.ini"

# Attempt to source the .env file.  Handle errors gracefully.
if [ -f "/app/.env" ]; then
  source "/app/.env"
  if [ -z "${UPLOAD_MAX_FILESIZE+x}" ]; then
    echo "Warning: UPLOAD_MAX_FILESIZE not found in .env file. Using default."
    UPLOAD_MAX_FILESIZE="30M"
  else
    UPLOAD_MAX_FILESIZE="${UPLOAD_MAX_FILESIZE}"
  fi
else
  echo "Error: .env file not found. Using default value."
  UPLOAD_MAX_FILESIZE="30M"
fi


# Verify that the php.ini file exists.
if [ ! -f "$PHP_INI" ]; then
  echo "Error: php.ini not found at $PHP_INI"
  exit 1
fi

# Check if upload_max_filesize is set to the environment variable value.
if grep -qE "^[[:space:]]*upload_max_filesize[[:space:]]*=[[:space:]]*${UPLOAD_MAX_FILESIZE}" "$PHP_INI"; then
  echo "upload_max_filesize is already set to ${UPLOAD_MAX_FILESIZE}. No changes made."
else
  echo "upload_max_filesize is not set to ${UPLOAD_MAX_FILESIZE}. Updating it to ${UPLOAD_MAX_FILESIZE}..."

  # Backup the current php.ini file.
  cp "$PHP_INI" "${PHP_INI}.bak"

  # Update the setting using sed.  More robust sed command.
  sed -i -E "s/^\s*upload_max_filesize\s*=\s*.*/upload_max_filesize = ${UPLOAD_MAX_FILESIZE}/" "$PHP_INI"

  echo "Update complete: upload_max_filesize is now set to ${UPLOAD_MAX_FILESIZE}."
fi
