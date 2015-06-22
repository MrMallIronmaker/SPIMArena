# assuming the two previous tests worked, that is
# the git is synced
# and all the pages have access

from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as ec

from test_access import shib_login
from sys import argv
from os.path import abspath
from time import sleep
from getpass import getpass
import paramiko


# Initialize the ssh connection
def ssh_init():
	ssh = paramiko.SSHClient()
	# change this if you run into security issues
	ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())

	# give the user three tries to enter the password.
	connected = False
	for i in range(3):
		password_ = getpass("SSH password for edu-staging:")
		try:
			ssh.connect('edu-staging.cs.illinois.edu', username='mrmillr3', password=password_)
			connected = True
			break
		except paramiko.AuthenticationException:
			# give the user another try
			pass

	# take care of broken connections
	if not connected:
		print "Failed to login."
		exit(2)

	return ssh


# upload through the web interface
def upload_bot(driver, bot_filename):
	driver.get("https://edu-staging.cs.illinois.edu/secure/spimarena/upload.php")
	#driver.find_element_by_id("fileToUpload").clear()
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
def verify_bot_pass(driver, ssh, bot_filename):
	upload_bot(driver, bot_filename)
	verify_upload(driver)
	# but first, make sure it made it to pending
	# use ssh to make sure the uploaded file made it to bots

# verify_fail
def verify_bot_fail(driver, ssh, bot_filename):
	upload_bot(driver, bot_filename)
	verify_upload(driver)

# returns true if file exists on the remote server, relative to cd
def remote_file_exists(ssh, filename):
	_, stdout, _ = ssh.exec_command("[ -f {0} ] && echo exists".format(filename))
	sleep(1)
	s = stdout.readlines()
	print s
	q = "exists" in s
	if q:
		print "File {0} found.".format(filename)
	return q

# main
if __name__ == "__main__":
	#driver = webdriver.Firefox()
	#shib_login(driver)

	# initialize the secure shell
	ssh = ssh_init()
	a, b, c = ssh.exec_command("cd /var/www/html/secure/spimarena")
	d, e, f = ssh.exec_command("ls")#"[ -f pending/mrmillr3 ] && echo exists")
	for i in [ b, c, e, f]:
		print i.readlines()

		# OK. SO HERE'S WHERE I LEFT OFF:
		# I can't tell why the remote_file_exists function fails to work.
		# I tried ls, but then it kicked me off for too many incorrect passwords.
		# lol

	ssh.close()
	exit()

	# upload, expect pass
	#verify_bot_pass(driver, ssh, "goodbot.s")
	remote_file_exists(ssh, "pending/mrmillr3")
	# upload, expect fail

	# cleanup
	ssh.close()