#!/bin/bash

Xvfb :67 &

#first check for the signal file.
RUNNING=$(cat running.txt)
if [[ "$RUNNING" == "NOT RUNNING" ]]
then
	echo "RUNNING" >| running.txt
else
	exit 0
fi


while true
do

BOT=""
BOT2=""

# Is there a line in the file?
LINE=$(head -1 requests.txt)
if [[ -n "$LINE" ]]
then

	# Grab and process that line
	BOT=$(echo $LINE | cut -d " " -f 1)
	BOT2=$(echo $LINE | cut -d " " -f 3)
	
	# Swap names, if they're alphabetical
	if [[ $BOT > $BOT2 ]]
		then
		TEMP=$BOT
		BOT=$BOT2
		BOT2=$TEMP
	fi
else
	#go ahead and exit
	break

fi	
echo $BOT
echo $BOT2

# Run the spimbot competition and parse the output
FILENAME=$(echo $BOT)_v_$(echo $BOT2)
#echo "/usr/bin/timeout 60s env DISPLAY=:67 /usr/local/bin/QtSpimbot -file $(dirname $0)/bots/$BOT -file2 $(dirname $0)/bots/$BOT2 -maponly -run -tournament -randommap -largemap -drawfreq 19 -exit_when_done -quiet -debug > $(dirname $0)/debug/$FILENAME.txt"
timeout 90s env DISPLAY=:67 ./QtSpimbot -file $(dirname $0)/bots/$BOT -file2 $(dirname $0)/bots/$BOT2 -maponly -run -tournament -randommap -largemap -exit_when_done -multi_quadrants -quadrant_modes > $(dirname $0)/debug/$FILENAME.txt

# take screenshots of the battle?
#while ()
#	do
#	
#done

THING=$( tail -1 $(dirname $0)/debug/$FILENAME.txt | grep "winner:" | cut -d " " -f 2)
THING=$(basename $THING | cut -d "." -f 1)

mkdir $(dirname $0)/results/$(echo $BOT)_v_$BOT2

RESULT="winner: $(basename $THING )"
echo "$RESULT" > $(dirname $0)/results/$(echo $BOT)_v_$BOT2/winner.txt
echo "bot: $BOT" >> $(dirname $0)/results/$(echo $BOT)_v_$BOT2/winner.txt
echo "bot2: $BOT2" >> $(dirname $0)/results/$(echo $BOT)_v_$BOT2/winner.txt

TEMP=$(tail -n +2 requests.txt)
printf "$TEMP" > requests.txt # iirc printf preserves newlines

done

# let other processes know you're done

echo "NOT RUNNING" >| running.txt
