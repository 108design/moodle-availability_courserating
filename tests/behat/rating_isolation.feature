@availability @availability_courserating
Feature: Course rating restrictions are specific to each user
  A rating by one student must not unlock an activity for another student

  Background:
    Given the following "courses" exist:
      | fullname | shortname | format |
      | Course 1 | C1        | topics |
    And the following "activities" exist:
      | activity | name            | course | idnumber |
      | page     | Restricted page | C1     | page1    |
    And the following "users" exist:
      | username |
      | student1 |
      | student2 |
    And the following "course enrolments" exist:
      | user     | course | role    |
      | student1 | C1     | student |
      | student2 | C1     | student |

  @javascript
  Scenario: A rating unlocks access only for the student who rated
    Given I am on the "page1" "page activity editing" page logged in as "admin"
    And I expand all fieldsets
    And I click on "Add restriction..." "button"
    And I click on "Course rated" "button" in the "Add restriction..." "dialogue"
    And I set the field "Course rated" to "Yes"
    And I click on ".availability-item .availability-eye img" "css_element"
    And I click on "Save and return to course" "button"
    And I log out
    When I am on the "C1" "Course" page logged in as "student1"
    Then I should not see "Restricted page" in the "region-main" "region"
    And I log out
    And I add a rating for course "C1" by user "student1"
    When I am on the "C1" "Course" page logged in as "student1"
    Then I should see "Restricted page" in the "region-main" "region"
    And I log out
    When I am on the "C1" "Course" page logged in as "student2"
    Then I should not see "Restricted page" in the "region-main" "region"
