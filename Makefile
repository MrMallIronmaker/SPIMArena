# vars

# replace this with your netid
USER="mrmillr3"

all: sync access upload request

sync:
	./git-sync-local
	echo "cd /var/www/html/secure/spimarena; ./git-sync-remote" | ssh $(USER)@edu-staging.cs.illinois.edu

access:
	python test_access.py $(USER)

upload:
	python test_upload.py $(USER)

request: