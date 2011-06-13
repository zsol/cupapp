#!/bin/bash

if [ "$1" == "" ]; then
    echo "Usage: $0 <web/uploads dir>" >&2
    exit 1;
fi

find "$1" -iname '*.SC2Replay' | \
while read file; do
    if ! echo $file | grep -q 'replay\.SC2Replay'; then
        dir=$(echo $file | sed 's/\.SC2Replay.*$//')
        mkdir -p $dir
        mv $file $dir/replay.SC2Replay
    fi
done

