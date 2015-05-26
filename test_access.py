# -*- coding: utf-8 -*-
from selenium import webdriver
from getpass import getpass

driver = webdriver.Firefox()
driver.get("https://edu-staging.cs.illinois.edu/secure/spimarena/")
driver.find_element_by_id("j_username").clear()
driver.find_element_by_id("j_password").clear()
netid = raw_input("Enter your netID: ")
password = getpass("Enter your AD password: ")
driver.find_element_by_id("j_username").send_keys(netid)
driver.find_element_by_id("j_password").send_keys(password)
driver.find_element_by_css_selector("input[type=\"submit\"]").click()
assert(len(driver.page_source.split()) > 50)
driver.get("https://edu-staging.cs.illinois.edu/secure/spimarena/upload.php")
assert(len(driver.page_source.split()) > 50)
driver.get("https://edu-staging.cs.illinois.edu/secure/spimarena/request.php")
assert(len(driver.page_source.split()) > 50)

driver.quit()