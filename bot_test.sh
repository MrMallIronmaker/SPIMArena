#!/bin/bash

# Designed to test whether a bot compiles and runs, or if QtSpim hangs.
# This lets the student know their bot fails, but also prevents QtSpimbot
# from hanging until the call times out in normal competition calls.

# This is implemented by a debug  call to QtSpimbot that times out after 8s.
# The contents of the output are taken at 6 seconds (which is long enough
# so that QtSpimbot starts up and begins running), then is compared to the
# the process end at about 8s. If they're the same, then it broke somewhere
# before the 6s mark.

Xvfb :34 &

BOT=$1

#timeout 8s env DISPLAY=:67 $(dirname $0)/QtSpimbot -file $(dirname $0)/pending/$BOT -maponly -run -tournament -randommap -largemap -exit_when_done -multi_quadrants -quadrant_modes > $(dirname $0)/test_bot.txt 2>stderr.txt &
timeout 8s env DISPLAY=:34 $(dirname $0)/QtSpimbot -file $(dirname $0)/pending/$BOT -debug -maponly -run -tournament -exit_when_done > $(dirname $0)/test_bot.txt 2>stderr.txt &
sleep 6s
SIX=$(cat $(dirname $0)/test_bot.txt)
wait $! # waits until the most recent background command returns (which is QtSpimbot)
EIGHT=$(cat $(dirname $0)/test_bot.txt)
if [ "$SIX" != "$EIGHT" ]
then
	mv $(dirname $0)/pending/$BOT $(dirname $0)/bots
else
	rm $(dirname $0)/pending/$BOT
fi
