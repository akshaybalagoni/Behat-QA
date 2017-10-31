@javascript @api
Feature: Runsheet management
  In order to manage runsheets
  As an authenticated user
  I need to be able to manage runsheets, runsheet teasers and sheduled cards

  Scenario: Verify Runsheet module is enabled
    Given I am logged in as a user with the "administrator" role
    When I am at "/admin/structure"
    Then I should see "Runsheet teaser bundle"
    And I should see "Runsheets"

  Scenario: Create a Runsheet Teaser bundle for Episode,Series,Movie
    Given I am logged in as a user with the "administrator" role
    When I am at "/admin/structure/runsheet_teaser_bundle"
    And I create a runsheet teaser bundle 'Bundle Episode'
    And I create a runsheet teaser bundle 'Bundle Series'
    And I create a runsheet teaser bundle 'Bundle Movie'
    When I am at '/admin/structure/runsheet_teaser_bundle'
    Then I should see "Bundle Episode"
    And I should see "Bundle Movie"
    And I should see "Bundle Series"

  Scenario: Create a Runsheet
    Given I am logged in as a user with the "administrator" role
    When I am at "/admin/structure/runsheet"
    And I click "Add Runsheet"
    And I fill in "RunsheetForEpisode" for "Runsheet label"
    And I wait for "2" secs
    And I fill in "FirstPosition" for "Position label"
    And I fill in "position0" for "Position ID"
    When I press "Save"
    Then I should see "Created the RunsheetForEpisode Runsheet."

  Scenario: Delete a Runsheet
    Given I am logged in as a user with the "administrator" role
    When I am at "/admin/structure/runsheet"
    And I create a runsheet 'RunsheetLabel'
    And I click "Edit" in the "RunsheetLabel" row
    When I delete a runsheet
    Then I should see "content runsheet: deleted RunsheetLabel."

  Scenario: Adding a Runsheet position
    Given I am logged in as a user with the "administrator" role
    When I am at "/admin/structure/runsheet"
    And I wait for "2" secs
    And I click 'Edit' in the 'RunsheetForEpisode' row
    When I add two more position to the form
    Then I should see "Saved the RunsheetForEpisode Runsheet."

  Scenario: Verify Duplicate Runsheet position check
    Given I am logged in as a user with the "administrator" role
    When I am at "/admin/structure/runsheet"
    And I wait for "2" secs
    And I click 'Edit' in the 'RunsheetForEpisode' row
    When I click save with duplicate positions
    Then I should see "The machine-readable name 2 is already in use. It must be unique."

  Scenario: Edit an existing Runsheet and its positions
    Given I am logged in as a user with the "administrator" role
    When I am at "/admin/structure/runsheet"
    And I wait for "2" secs
    And I click 'Edit' in the 'RunsheetForEpisode' row
    And I modified the existing runsheet label and runsheet positions
    Then I should see "Saved the RunsheetModified Runsheet."

  Scenario: Delete Runsheet Position
    Given I am logged in as a user with the "administrator" role
    When I am at "/admin/structure/runsheet"
    And I click 'Edit' in the 'RunsheetModified' row
    When I press "delete_position_3"
    When I press "Save"
    Then I should see "Saved the RunsheetModified Runsheet."
    When I am at "/admin/structure/runsheet/testrunsheet"
    Then I should not see "positions[3][label]"
    And I should not see "positions[3][id]"

  Scenario: Create a Runsheet Teaser,Schedule an  item from content page and Edit runsheet teaser
    Given I am logged in as a user with the "administrator" role
    When I am at "/admin/runsheet_teaser/add/bundle_episode"
    And I create a runsheet teaser 'TeaserForEpisode'
    When I am at "/admin/runsheet_teaser/add/bundle_movie"
    And I create a runsheet teaser 'TeaserForMovie'
    And I schedule an Item from content admin page
    When I am at "/admin/content/scheduled-items"
    Then I should see "TeaserForEpisode"
    And I am at "/admin/content/runsheet-teasers"
    When I click 'Edit' in the 'TeaserForMovie' row
    And I Edit Movie runsheet teaser
    When I am at "/admin/content/scheduled-items"
    When I click on operation dropdown and edit Episode teaser
    Then I should see "Saved the TeaserModified_2 Runsheet teaser."
    And I should not see "TeaserForEpisode"

  Scenario: Method 2 - Create schedule an item from Runsheet operations drop down
    Given I am logged in as a user with the "administrator" role
    When I am at "/admin/structure/runsheet"
    And I create a Runsheet to schedule an item
    When I am at "/admin/runsheet_teaser/add/bundle_series"
    And I create a runsheet teaser 'TeaserForSeries'
    And I Schedule an item from Runsheet operations drop down
    When I am at "/admin/content/scheduled-items"
    Then I should see "TeaserForSeries"

  Scenario: Method 3 – Create Schedule a item from Runsheet teaser Edit drop down
    Given I am logged in as a user with the "administrator" role
    When I am at "/admin/runsheet_teaser/add/bundle_episode"
    And I create a runsheet teaser 'TeaserForSeason'
    And I click on Edit dropdown button to Schedule an item
    When I am at "/admin/content/scheduled-items"
    Then I should see "TeaserForSeason"

  Scenario: Method 4: Schedule an item from 'Timeline View' page
    Given I am logged in as a user with the "administrator" role
    When I am at "/admin/runsheet_teaser/add/bundle_episode"
    And I create a runsheet teaser 'TeaserForSeason'
    When I am at "/admin/runsheet/runsheetmovie/timeline"
    Then I should see "Timeline for RunsheetMovie"
    And I click "Schedule an item"
    And I schedule an item timeline view page
    When I am at "/admin/content/scheduled-items?rs=runsheetmovie"
    Then I should see "TeaserForSeason"

  Scenario: Delete a Runsheet Teaser
    Given I am logged in as a user with the "administrator" role
    When I am at "/admin/runsheet_teaser/add/bundle_episode"
    And I create a runsheet teaser 'AmericanDad'
    When I am at "/admin/content/runsheet-teasers"
    Then I should see "AmericanDad"
    When I click "Edit" in the "AmericanDad" row
    And I Delete AmericanDad runsheet teaser
    When I am at "/admin/content/runsheet-teasers"
    And I wait for "2" secs
    Then I should not see "AmericanDad"

  Scenario: View list of scheduled items and Edit the Scheduled Teaser
    Given I am logged in as a user with the "administrator" role
    When I am at "/admin/runsheet_teaser/add/bundle_series"
    And I create a runsheet teaser 'TeaserForSeries'
    When I am at "/admin/runsheet_teaser/add/bundle_episode"
    And I create a runsheet teaser 'TeaserForSeason'
    And I Schedule an item from Runsheet operations drop down
    When I am at "/admin/content/scheduled-items"
    Then I should see "TeaserForSeries"
    And I click "Edit" in the "TeaserForSeries" row
    Then I should see "Edit Scheduled item"
    When I Edit the Scheduled teaser
    Then I should see changes gets updated and saved for schedule item
    And I should not see "TeaserForSeries"

  Scenario: Verify the default pagination value
    Given I am logged in as a user with the "administrator" role
    When I am at "/admin/structure/views"
    And I click "Edit" in the "Runsheet teasers" row
    And I should see "Use pager: Full"
    Then I should see "Paged, 30 items"

  Scenario: Verify pagination for Runsheet teaser list
    Given I am logged in as a user with the "administrator" role
    And I open runsheet teasers view page
    And I should see "Paged, 30 items"
    And I create 90 runsheet teasers for different types
    When I am at "/admin/content/runsheet-teasers"
    Then I should see 30 Runsheet teasers per page

  Scenario: Verify Search Option functionality on Runsheet teasers page
    Given I am logged in as a user with the "administrator" role
    And I create 90 runsheet teasers for different types
    When I am at "/admin/content/runsheet-teasers"
    When I search runsheet teasers with Title 'Teaser 20'
    Then I should see all runsheet teasers with Title 'Teaser 20'
    When I search runsheet teasers with Type 'Bundle Movie'
    Then I should see all runsheet teasers with Type 'Bundle Movie'
    When I search runsheet teasers with Title 'Teaser 15' and Type 'Bundle Series'
    Then I should see only one runsheet teasers with Title 'Teaser 15' and Type 'Bundle Series'

  Scenario: Verify Search Option functionality on Runsheet teasers page with invalid inputs
    Given I am logged in as a user with the "administrator" role
    And I create 90 runsheet teasers for different types
    When I am at "/admin/content/runsheet-teasers"
    And I search runsheet teaser with invalid title
    Then I should not see any runsheet teaser
    And I search runsheet teaser with special characters
    Then I should not see any runsheet teaser
    And I search runsheet teaser with empty spaces
    Then I should not see any runsheet teaser

  Scenario: Verify no pagination displayed when Runsheet teasers less then configured items per page
    Given I am logged in as a user with the "administrator" role
    And I create 20 runsheet teasers for different types
    When I am at "/admin/content/runsheet-teasers"
    Then I should see only 20 runsheet teasers on the page
    And I should not see pagination at the bottom of the page

  Scenario: Verify Pagination for Schedule Runsheet items
    Given I am logged in as a user with the "administrator" role
    When I open Scheduled items view page
    And I should see "Paged, 4 items"
    And I create a runsheet 'RunsheetSeries'
    And I create a runsheet 'RunsheetEpisode'
    And I create a runsheet 'RunsheetSeason'
    And I Schdeule an items
    When I am at "/admin/content/scheduled-items"
    Then I should see 4 scheduled items per page

  Scenario: Verify Pagination for Schedule Runsheet items
    Given I am logged in as a user with the "administrator" role
    And I Schdeule an items
    When I am at "/admin/content/scheduled-items"
    And I search Scheduled Items with Runsheet 'RunsheetEpisode'
    Then I should see 3 Scheduled items with Runsheet 'RunsheetEpisode'
    And I search Scheduled Items with teaser 'TeaserMovie'
    Then I should see 3 Scheduled items with teaser 'TeaserMovie'
    And I search Scheduled Items with Runsheet 'RunsheetSeries' and Teaser 'TeaserSeries'
    Then I should see 3 Scheduled items with Runsheet 'RunsheetSeries' and Teaser 'TeaserSeries'

  Scenario: Verify Search Option functionality to the Schedule Runsheet items with invalid inputs
    Given I am logged in as a user with the "administrator" role
    And I Scheduled 3 items
    When I search Scheduled item with invalid teaser 'xyz'
    Then I should see "No scheduled items available."
    When I search Scheduled item with Special Characters '@&^*@#^@*@^*'
    Then I should see "No scheduled items available."
    When I search Scheduled item with Empty Spaces '      '
    Then I should see "No scheduled items available."

  Scenario: Verify no pagination is displayed when scheduled runsheet items less then configured items per page
    Given I am logged in as a user with the "administrator" role
    And I Scheduled 3 items
    When I am at "/admin/content/scheduled-items"
    Then I should see only 3 Scheduled items on the page
    And I should not see pagination on Scheduled item page

  Scenario: Verify More details added to teasers in timeline view
    Given I am logged in as a user with the "administrator" role
    And I Scheduled 2 items for same teaser
    And I click on first Teaser
    Then I should see Details of First Teaser
    When I click on Second Teaser
    Then I should see Details of Second TeaserTeaser

  Scenario: Add more fields to bundle and verify details of an item are updated in Timeline view
    Given I am logged in as a user with the "administrator" role
    And I add more fields to the runsheet teaser bundle
    When I Configure the teaser with new fields
    And I schedule an item
    Then I should see the configured fields data in the Details of teaser

  Scenario: Configure fields to multiple teasers and verify details of teasers are displayed as configured
    Given I am logged in as a user with the "administrator" role
    When I configure different fields to multiple teasers
    And I Scheduled 3 items for different teasers
    Then the Details of multiple teasers should be displayed as configured