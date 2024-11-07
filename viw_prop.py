from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.common.action_chains import ActionChains
import time

# Set up the Chrome WebDriver
service = Service('path/to/chromedriver')  # Replace with your ChromeDriver path
driver = webdriver.Chrome(service=service)

try:
    # Open the home page
    driver.get('http://localhost/index1.php')  # Replace with the URL of your home page

    # Wait for page to load
    time.sleep(2)  # Wait time may vary based on your page load time

    # Locate the 'Properties' dropdown menu
    properties_dropdown = driver.find_element(By.ID, "propertiesDropdown")

    # Hover over the dropdown menu to reveal options
    actions = ActionChains(driver)
    actions.move_to_element(properties_dropdown).perform()
    
    time.sleep(1)  # Brief pause for dropdown animation

    # Click on the 'View Property' link in the dropdown menu
    view_property_link = driver.find_element(By.LINK_TEXT, "View Property")
    view_property_link.click()

    # Pause to allow navigation to complete
    time.sleep(2)

    # Verify navigation by checking the URL or the page title
    assert "view_property.php" in driver.current_url, "Failed to navigate to view_property.php"
    print("Successfully navigated to view_property.php")

finally:
    # Close the browser
    driver.quit()
