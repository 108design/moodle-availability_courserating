@availability @availability_courserating
Feature: availability_courserating
  In order to control student access to activities
  As a teacher
  I need to set course rating conditions which prevent student access

  Background:
    Given the following "courses" exist:
      | fullname | shortname | format | numsections |
      | Course 1 | C1        | topics | 4           |
    And the following "activities" exist:
      | activity   | name   | intro            | course | idnumber    | section | visible |
      | page       | Page A | page description | C1     | page1       | 1       | 1       |
      | page       | Page B | page description | C1     | page2       | 1       | 1       |
      | page       | Page C | page description | C1     | page3       | 1       | 1       |
      | page       | Page D | page description | C1     | page4       | 1       | 1       |
    And the following "users" exist:
      | username | firstname | lastname | email                |
      | student1 | Student   | First    | student1@example.com |
      | student2 | Student   | Second   | student2@example.com |
      | teacher1 | Teacher   | First    | teacher1@example.com |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C1     | editingteacher |
      | student1 | C1     | student        |
      | student2 | C1     | student        |

  @javascript
  Scenario: Access switches after adding a course rating
    Given I am on the "C1" "Course" page logged in as "teacher1"

    # Configure Page A for users who did not rate this course.
    When I am on the "page1" "page activity editing" page
    And I expand all fieldsets
    And I click on "Add restriction..." "button"
    And I click on "Course rated" "button" in the "Add restriction..." "dialogue"
    Then I should see "Please choose" in the "region-main" "region"
    And I set the field "Course rated" to "No"
    And I click on ".availability-item .availability-eye img" "css_element"
    And I click on "Save and return to course" "button"

    # Configure page B for users who did not rate the course.
    When I am on the "page2" "page activity editing" page
    And I expand all fieldsets
    And I click on "Add restriction..." "button"
    And I click on "Course rated" "button"
    Then I should see "Please choose" in the "region-main" "region"
    And I set the field "Course rated" to "No"
    But I should not see "Please choose" in the "region-main" "region"
    And I click on "Save and return to course" "button"

    # Configure page C for users who rated the course.
    When I am on the "page3" "page activity editing" page
    And I expand all fieldsets
    And I click on "Add restriction..." "button"
    And I click on "Course rated" "button"
    And I set the field "Course rated" to "Yes"
    And I click on "Save and return to course" "button"

    # Configure page D for users who rated the course hidden.
    When I am on the "page4" "page activity editing" page
    And I expand all fieldsets
    And I click on "Add restriction..." "button"
    And I click on "Course rated" "button"
    And I set the field "Course rated" to "Yes"
    And I click on ".availability-item .availability-eye img" "css_element"
    And I click on "Save and return to course" "button"
    And I log out

    # Log in as student.
    When I am on the "C1" "Course" page logged in as "student1"
    Then I should see "Page A" in the "region-main" "region"
    And I should see "Page B" in the "region-main" "region"
    And I should see "Page C" in the "region-main" "region"
    And I should not see "Page D" in the "region-main" "region"
    And I log out

    When I am on the "C1" "Course" page logged in as "teacher1"
    And I add a rating for course "C1" by user "student1"
    And I log out

    When I am on the "C1" "Course" page logged in as "student1"
    And I should see "Page B" in the "region-main" "region"
    And I should see "Page C" in the "region-main" "region"
    And I should see "Page D" in the "region-main" "region"
    But I should not see "Page A" in the "region-main" "region"

  @javascript
  Scenario: See restricted feedback users who have not responded
    Given I am on the "C1" "Course" page logged in as "teacher1"

    And I add a rating for course "C1" by user "student2"
    And I am on "Course 1" course homepage with editing mode on
    And I add a feedback activity to course "Course 1" section "2" and I fill the form with:
      | Name                | Frogs                                             |
      | Description         | x                                                 |
      | Record user names   | User's name will be logged and shown with answers |
    And I am on the "Frogs" "feedback activity editing" page
    And I expand all fieldsets
    And I click on "Add restriction..." "button"
    And I click on "Course rated" "button"
    And I set the field "Course rated" to "No"
    And I click on "Save and return to course" "button"

    And I am on the Frogs "feedback activity" page
    And I click on "Edit questions" "link" in the "[role=main]" "css_element"
    And I add a "Short text answer" question to the feedback with:
      | Question | Y/N? |
    And I log out

    # Go in as student 1 and do the feedback.
    When I am on the Frogs "feedback activity" page logged in as student1
    And I follow "Answer the questions"
    And I set the field "Y/N?" to "Y"
    And I press "Submit your answers"
    And I log out

    # Go in as teacher and check the users who haven't completed it.
    And I am on the Frogs "feedback activity" page logged in as teacher1
    And I navigate to "Responses" in current page administration
    Then I select "Show non-respondents" from the "jump" singleselect
    But I should not see "Student 2"
    And I should not see "Student 1"
