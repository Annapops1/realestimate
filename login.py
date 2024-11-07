from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
import time
import re
import easygui

driver = webdriver.Chrome()
driver.get("http://localhost/miniproj/index.php")
print("Opened the login page.")
driver.maximize_window()
time.sleep(3) 
wait = WebDriverWait(driver, 20)

# def validate_email(email):
#     """ Validate email format. """
#     pattern = re.compile(r'^[a-zA-Z0-9._%+-]+@(gmail\.com|yahoo\.com|in)\b')
#     return pattern.match(email)

# def validate_phone(phone):
#     """ Validate phone number format. """
#     pattern = re.compile(r'^[6789]\d{9}$')
#     return pattern.match(phone)


username_field = wait.until(EC.visibility_of_element_located((By.NAME, "username")))
username_field.clear()
username_field.send_keys('annapops')
print("Entered username.")
time.sleep(1) 

password_field = wait.until(EC.visibility_of_element_located((By.NAME, 'password')))
password_field.clear()
password_field.send_keys('annapop')
print("Entered password.")
time.sleep(1) 

time.sleep(1)

# Wait for the page to load after entering username and password
wait.until(EC.presence_of_element_located((By.CSS_SELECTOR, 'input.btn')))

login_button = wait.until(EC.element_to_be_clickable((By.CSS_SELECTOR, 'input.btn')))
print("Login button found. Clicking the button.")
login_button.click()

# Wait for the page to load after clicking the login button
wait.until(EC.url_contains("dashboard") or EC.url_contains("success_page"))

if "dashboard" in driver.current_url or "success_page" in driver.current_url:
    print("Login successful.")
else:
    raise Exception(f"Login failed. Current URL: {driver.current_url}")


driver.get("http://localhost/miniproj/index1.php")
print("Navigated to the profile update page.")
time.sleep(3)

# Close the driver after operations
driver.quit()  # Ensure the driver is closed properly

# Wait for the page to load
WebDriverWait(driver, 3).until(EC.visibility_of_element_located((By.TAG_NAME, 'body')))