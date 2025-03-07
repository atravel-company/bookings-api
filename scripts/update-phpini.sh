#!/bin/bash

# --- Configuration ---
PHP_INI_PATHS=(
  "/etc/php/7.4/fpm/php.ini"
  "/etc/php/7.4/cli/php.ini"
)
ENV_FILE="/var/www/atsportugal/prod/atravelbookings/.env"
DEFAULT_FILESIZE="30M"

# --- Functions ---

# Update a php.ini setting.
update_php_setting() {
  local setting_name="$1"
  local setting_value="$2"
  local php_ini_file="$3"

  if grep -qE "^[[:space:]]*${setting_name}[[:space:]]*=[[:space:]]*${setting_value}" "$php_ini_file"; then
    echo "  - ${setting_name} is already set to ${setting_value} in $php_ini_file."
  else
    echo "  - Updating ${setting_name} to ${setting_value} in $php_ini_file..."
    # Backup the current php.ini file.
    cp "$php_ini_file" "${php_ini_file}.bak"
    # Update the setting using sed.  More robust sed command.
    sed -i -E "s/^\s*${setting_name}\s*=\s*.*/${setting_name} = ${setting_value}/" "$php_ini_file"
    echo "    -> Update complete."
  fi
}

# --- Main Script ---

echo "---------------------------------------------------------------------"
echo "Starting php.ini update process..."
echo "---------------------------------------------------------------------"
echo ""

# Source the .env file if it exists.
if [ -f "$ENV_FILE" ]; then
  echo "Sourcing environment variables from $ENV_FILE..."
  source "$ENV_FILE"
  
  if [ -z "${UPLOAD_MAX_FILESIZE+x}" ]; then
    echo "  Warning: UPLOAD_MAX_FILESIZE not found in .env. Using default: $DEFAULT_FILESIZE"
    UPLOAD_MAX_FILESIZE="$DEFAULT_FILESIZE"
  else
    echo "  - UPLOAD_MAX_FILESIZE found in .env: $UPLOAD_MAX_FILESIZE"
    UPLOAD_MAX_FILESIZE="${UPLOAD_MAX_FILESIZE}"
  fi
  
  if [ -z "${POST_MAX_SIZE+x}" ]; then
    echo "  Warning: POST_MAX_SIZE not found in .env. Setting it to UPLOAD_MAX_FILESIZE."
    POST_MAX_SIZE="$UPLOAD_MAX_FILESIZE"
  else
    echo "  - POST_MAX_SIZE found in .env: $POST_MAX_SIZE"
    POST_MAX_SIZE="${POST_MAX_SIZE}"
  fi
  
  if [ -z "${APP_ENV+x}" ]; then
    echo "  Warning: APP_ENV not found in .env. Assuming default (not prod)."
    APP_ENV="dev"
  else
    echo "  - APP_ENV found in .env: $APP_ENV"
    APP_ENV="${APP_ENV}"
  fi

else
  echo "Error: .env file not found at $ENV_FILE. Using default values."
  UPLOAD_MAX_FILESIZE="$DEFAULT_FILESIZE"
  POST_MAX_SIZE="$DEFAULT_FILESIZE"
  APP_ENV="dev"
fi

echo ""

# Process each php.ini file.
for PHP_INI in "${PHP_INI_PATHS[@]}"; do
  if [ ! -f "$PHP_INI" ]; then
    echo "  Warning: php.ini not found at $PHP_INI. Skipping."
    continue
  fi

  echo "Processing php.ini file: $PHP_INI"
  update_php_setting "upload_max_filesize" "$UPLOAD_MAX_FILESIZE" "$PHP_INI"
  update_php_setting "post_max_size" "$POST_MAX_SIZE" "$PHP_INI"
  echo ""
done

echo "---------------------------------------------------------------------"
echo "Finished processing all php.ini files."
echo "---------------------------------------------------------------------"

# Production restart hint.
if [ "$APP_ENV" = "prod" ] || [ "$APP_ENV" = "production" ]; then
  echo ""
  echo "---------------------------------------------------------------------"
  echo "Hint: You should restart the server for the changes to take effect."
  echo "Run the following commands:"
  echo "  sudo systemctl restart apache2"
  echo "  sudo systemctl restart php7.4-fpm"
  echo "---------------------------------------------------------------------"
  echo ""
fi
