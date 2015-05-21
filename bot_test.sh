#!/bin/bash

# Designed to test whether a bot compiles and runs, or if QtSpim hangs.
# This lets the student know their bot fails, but also prevents QtSpimbot
# from hanging until the call times out in normal competition calls.

# This is implemented by a debug  call to QtSpimbot that times out after 8s.
# The contents of the output are taken at 6 seconds (which is long enough
# so that QtSpimbot starts up and begins running), then is compared to the
# the process end at about 8s. If they're the same, then it broke somewhere
# before the 6s mark.

# startup an Xvfb server in the background
# this errors out if it's already created.
Xvfb :34 &

# the first argument is the bot file name
BOT=$1

# env DISPLAY=:34 sets the display to the :34 (the first argument to Xvfb)
# most of the other calls are QtSpimbot specifics, 
# so you should know them or get to know them
timeout 8s env DISPLAY=:34 $(dirname $0)/QtSpimbot -file \
$(dirname $0)/pending/$BOT -debug -maponly -run -tournament -exit_when_done \
> $(dirname $0)/bot_test_out.txt 2>bot_test_err.txt &

# let QtSpimbot run for six seconds, then get the output
sleep 6s
SIX=$(cat $(dirname $0)/bot_test_out.txt)

# wait until the most recent background command returns (which is QtSpimbot)
wait $!
EIGHT=$(cat $(dirname $0)/bot_test_out.txt)

# move or delete depending if the bot passed
if [ "$SIX" != "$EIGHT" ]
then # passed test
	mv $(dirname $0)/pending/$BOT $(dirname $0)/bots
else # failed test
	rm $(dirname $0)/pending/$BOT
fi