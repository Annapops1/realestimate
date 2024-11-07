# Selenium Test Code for PHP Project
import pytest
from selenium import webdriver
from selenium.webdriver.common.by import By

class TestDemotest:
    def setup_method(self, method):
        # Initialize the Chrome driver
        self.driver = webdriver.Chrome()  
        self.driver.maximize_window()  # Maximizes the window for consistency
        self.driver.implicitly_wait(10)  # Sets an implicit wait for finding elements

    def teardown_method(self, method):
        # Quit the driver after each test method
        self.driver.quit()

    def test_demotest(self):
        # Navigate to the local project URL
        self.driver.get("http://localhost/miniproj/index1.php")
        
        # Find and click the "Rent a Property" link
        rent_property_link = self.driver.find_element(By.LINK_TEXT, "Rent a Property")
        rent_property_link.click()
        
        # Click on the second property card's "View Details" button
        property_card = self.driver.find_element(By.CSS_SELECTOR, ".property-card:nth-child(2) .view-details")
        property_card.click()
        
        # Click on the interest button (assuming it is named "intrestBtn")
        interest_button = self.driver.find_element(By.NAME, "intrestBtn")
        interest_button.click()

        # Additional code can be added here to verify test results, such as checking for confirmation messages.

# Entry point to run the tests directly
if __name__ == "__main__":
    pytest.main(["-v", __file__])  # The "-v" flag enables verbose output
