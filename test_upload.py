# assuming the two previous tests worked, that is
# the git is synced
# and all the pages have access

from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as ec

from test_access import shib_login
from sys import argv
from subprocess import Popen, PIPE
from os.path import abspath
from time import sleep


# Initialize the ssh connection
def ssh_init():
	proc = Popen("ssh -tt {0}@edu-staging.cs.illinois.edu".format(argv[1]), # args
		stdin=PIPE, stdout=PIPE, stderr=PIPE, shell=True)
	#print proc.communicate()
	return proc

# upload through the web interface
def upload_bot(driver, bot_filename):
	driver.get("https://edu-staging.cs.illinois.edu/secure/spimarena/upload.php")
	driver.find_element_by_id("fileToUpload").clear()
	driver.find_element_by_id("fileToUpload").send_keys(abspath(bot_filename))
	driver.find_element_by_name("submit").click()

def verify_upload(driver):
	attempts_remaining = 10
	while "has been uploaded and renamed as" not in driver.page_source:
		attempts_remaining -= 1
		if attempts_remaining == 0:
			assert("Upload failed" and False)
		sleep(0.5)

# upload and make sure a bot passed and was updated
def verify_bot_pass(driver, bot_filename):
	upload_bot(driver, bot_filename)
	verify_upload(driver)
	# but first, make sure it made it to pending
	# use ssh to make sure the uploaded file made it to bots

# verify_fail
def verify_bot_fail(driver, bot_filename):
	upload_bot(driver, bot_filename)
	verify_upload(driver)

# main
if __name__ == "__main__":
	driver = webdriver.Firefox()
	shib_login(driver)
	# ssh init
	#ssh_proc = ssh_init()
	#print ssh_proc.communicate("ls\n")

	# upload, expect fail
	verify_bot_pass(driver, "goodbot.s")
	# upload, expect pass

	# cleanup
	ssh_proc.wait()