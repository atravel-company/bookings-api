#!/bin/bash
#
# This script only exists because of the poor code;
# Should be removed.
#
# This script continuously monitors the 'storage/app/public'
# directory for changes and synchronizes them with the 'public/storage'
# directory using inotifywait and rsync, ensuring that uploaded 
# files are immediately accessible via the webserver.

mkdir -p public/storage/

rsync -av --delete storage/app/public/ public/storage/

while inotifywait -r -e modify,create,delete,move storage/app/public/; do
    rsync -av --delete storage/app/public/ public/storage/
done