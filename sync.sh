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

function run_cmd()
{
    echo Running: \"$@\" ...
    if eval "$@"; then
	echo OK
    else
	echo "Failed..."
	exit 1
    fi
}

if needs_sync_with "origin" ; then
    run_cmd git pull --ff-only -- origin $BRANCH
    run_cmd git submodule update --init
    run_cmd ./symfony propel:migrate
    run_cmd ./symfony propel:build-model
    run_cmd ./symfony propel:build-forms
    run_cmd ./symfony propel:build-filters
    run_cmd ./symfony propel:build-sql
    run_cmd ./symfony cc
fi

popd > /dev/null

