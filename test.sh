#!/bin/bash

export SUBSCRIBERS_API_ENV="test"
export BIND_ADDRESS="0.0.0.0"
export BIND_PORT="7000"
export ROOT_FOLDER="public/"
ARGS=$@

# start the server
printf "Starting server at $BIND_ADDRESS:$BIND_PORT on $ROOT_FOLDER\n"
php -S "$BIND_ADDRESS:$BIND_PORT" -t $ROOT_FOLDER >& /dev/null &
SERVER_PID=$!

if [ $? -eq 0 ]
then
	sleep 3
	printf "Server started (PID $SERVER_PID)\n\n"
	if [[ $ARGS == "" ]]; then
		echo "No arguments given... running full suite"
		vendor/bin/phpunit --colors=auto --testdox --verbose tests/
	else
		echo "Running specific tests..."
		vendor/bin/phpunit --colors=auto --testdox --verbose $ARGS
	fi

	printf "\nStopping server (PID $SERVER_PID)\n"
	kill -9 -- $SERVER_PID
	exit 0
else
	printf "\nThere was a problem starting the server.\n"
	exit 1
fi
