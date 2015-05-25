# vars

# replace this with your netid
USER="mrmillr3"

all: sync access upload request

sync:
	./git-sync
	echo "Trying remote..."
	cat git-sync | ssh $(USER)@edu-staging.cs.illinois.edu

access:

upload:

request: