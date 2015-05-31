# -*- coding: utf-8 -*-
from selenium import webdriver
from getpass import getpass
from sys import argv

def shib_login(driver):
	driver.get("https://edu-staging.cs.illinois.edu/secure/spimarena/")
	driver.find_element_by_id("j_username").clear()
	driver.find_element_by_id("j_password").clear()
	netid = argv[1]
	print netid
	password = getpass("Enter your AD password: ")
	driver.find_element_by_id("j_username").send_keys(netid)
	driver.find_element_by_id("j_password").send_keys(password)
	driver.find_element_by_css_selector("input[type=\"submit\"]").click()

def access(driver):
	driver.get("https://edu-staging.cs.illinois.edu/secure/spimarena/")
	assert(len(driver.page_source.split()) > 50)
	driver.get("https://edu-staging.cs.illinois.edu/secure/spimarena/upload.php")
	assert(len(driver.page_source.split()) > 50)
	driver.get("https://edu-staging.cs.illinois.edu/secure/spimarena/request.php")
	assert(len(driver.page_source.split()) > 50)

if __name__ == "__main__":
	driver = webdriver.Firefox()
	shib_login(driver)
	access(driver)
	driver.quit()
