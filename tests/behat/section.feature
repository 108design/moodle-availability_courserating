@availability @availability_courserating
Feature: Section 0 availability_courserating
  Section 0 can be restricted by course rating

  Background:
    Given the following "courses" exist:
      | fullname | shortname | category |
      | Course 1 | C1        | 0        |
    And the following "users" exist:
      | username |
      | teacher1 |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C1     | editingteacher |

  @javascript
  Scenario: Restrict section0 by course rating
    When I log in as "admin"
    And I am on "Course 1" course homepage
    And I turn editing mode on
    And I edit the section "0"
    And I expand all fieldsets
    And I click on "Add restriction..." "button"
    Then "Course rated" "button" should exist in the "Add restriction..." "dialogue"
