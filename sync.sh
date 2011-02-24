#!/bin/bash

BASEDIR=${BASEDIR:-$(dirname $(readlink -f $0))}
BRANCH=${BRANCH:-"master"}
pushd "$BASEDIR" > /dev/null

function needs_sync_with()
{
    if [ "$(git ls-remote --heads $1 $BRANCH)" == "$(git ls-remote --heads ./. $BRANCH)" ]; then
	return 1
    fi
    return 0
}

if needs_sync_with "origin" ; then
    cmd="git pull --ff-only -- origin $BRANCH"
    echo "Syncing (running $cmd)"
    eval "$cmd" || exit 2
fi

popd > /dev/null

