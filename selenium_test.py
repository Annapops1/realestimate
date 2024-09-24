from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
import time

# Set up the WebDriver (make sure to have the appropriate driver installed)
driver = webdriver.Chrome()  # or webdriver.Firefox(), etc.

try:
    # Navigate to the login page
    driver.get("http://localhost/path/to/login.php")  # Update with your local path

    # Test with valid credentials
    username_field = driver.find_element(By.ID, "annapops")  # Ensure this ID matches your HTML element
    password_field = driver.find_element(By.ID, "annapops1")  # Ensure this ID matches your HTML element
    login_button = driver.find_element(By.XPATH, "//button[@type='submit']")

    username_field.send_keys("admin")  # Replace with a valid username
    password_field.send_keys("admin1234")  # Replace with a valid password
    login_button.click()

    # Wait for the page to load
    time.sleep(2)

    # Check if redirected to the admin dashboard
    assert "admin_dash.php" in driver.current_url

    # Test with invalid credentials
    driver.get("http://localhost/path/to/login.php")  # Reload the login page
    username_field = driver.find_element(By.ID, "username")
    password_field = driver.find_element(By.ID, "password")
    login_button = driver.find_element(By.XPATH, "//button[@type='submit']")

    username_field.send_keys("invalid_user")
    password_field.send_keys("wrong_password")
    login_button.click()

    # Wait for the error message to appear
    time.sleep(2)

    # Check for error message
    error_message = driver.find_element(By.CLASS_NAME, "error")
    assert "Invalid username or password." in error_message.text

finally:
    # Close the browser
    driver.quit()
