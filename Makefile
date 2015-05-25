# vars

# replace this with your netid
USER="mrmillr3"

all: sync access upload request

sync:
	echo "Trying local"
	./git-sync-local
	echo "Trying remote..."
	echo "cd /var/www/html/secure/spimarena; ./git-sync-remote" | ssh $(USER)@edu-staging.cs.illinois.edu

access:

upload:

request: