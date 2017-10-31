<?php

use Drupal\DrupalExtension\Context;
use Drupal\DrupalExtension\Context\RawDrupalContext;
use Behat\Behat\Context\SnippetAcceptingContext;
use Drupal\node\Entity\Node;
use Drupal\taxonomy\Entity\Term;
use Behat\Testwork\Hook\Scope\BeforeSuiteScope;

class RunsheetContext extends RawDrupalContext implements SnippetAcceptingContext
{
    /**
     * Install Draco Runsheet module.
     *
     * @BeforeSuite
     */
    public static function prepare(BeforeSuiteScope $scope)
    {
        /** @var \Drupal\Core\Extension\ModuleHandler $moduleHandler */
        $moduleHandler = \Drupal::service('module_handler');
        if (!$moduleHandler->moduleExists('draco_runsheet')) {
            \Drupal::service('module_installer')->install(['draco_runsheet']);
        }
    }
    /**
     * @Then I create a runsheet teaser bundle :arg1
     */
    public function iCreateARunsheetTeaserBundle($RunsheetBundle)
    {
        $this->visitPath("/admin/structure/runsheet_teaser_bundle/add");
        $this->getSession()->resizeWindow(1440, 900, 'current');
        $page = $this->getSession()->getPage();
        $page->fillField('edit-label', $RunsheetBundle);
        $this->getSession()->wait(1000);
        $page->fillField('edit-description', "This teaser bundle is used to classify teasers that relate to '$RunsheetBundle'");
        $page->pressButton('Save');
        $this->visitPath("/admin/structure/runsheet_teaser_bundle");
        $this->assertSession()->pageTextContains($RunsheetBundle);
    }
    /**
     * @When I add two more position to the form
     */
    public function iAddTwoMorePositionToTheForm()
    {
        $this->getSession()->getPage()->pressButton('Add position');
        $this->getSession()->wait(2000);
        $page = $this->getSession()->getPage();
        $page->fillField('positions[1][label]', 'SecondPosition');
        $page->fillField('positions[1][id]', '2');
        $page->pressButton('Add position');
        $this->getSession()->wait(1000);
        $page->fillField('positions[2][label]', 'ThirdPosition');
        $page->fillField('positions[2][id]', '3');
        $page->pressButton('Add position');
        $this->getSession()->wait(1000);
        $page->fillField('positions[3][label]', 'FourthPosition');
        $page->fillField('positions[3][id]', '4');
        $page->pressButton('Save');
    }
    /**
     * @When I click save with duplicate positions
     */
    public function iClickSaveWithDuplicatePositions()
    {
        $page = $this->getSession()->getPage();
        $page->pressButton('Add position');
        $this->getSession()->wait(2000);
        $page->fillField('positions[4][label]', 'DuplicatePosition');
        $page->fillField('positions[4][id]', '2');
        $page->pressButton('Save');
        $this->getSession()->wait(2000);
    }
    /**
     * @When I create a runsheet teaser :arg1
     */
    public function iCreateARunsheetTeaser($Title)
    {
        $page = $this->getSession()->getPage();
        $this->getSession()->wait(2000);
        $page->fillField('title[0][value]', $Title);
        $page->pressButton('Save');
        $this->getSession()->wait(2000);
        $this->assertSession()->pageTextContains($Title);
    }
    public function runsheetTeaserWithFourFields($Title,$Email,$Caption,$Description)
    {
        $page = $this->getSession()->getPage();
        $this->getSession()->wait(2000);
        $page->fillField('title[0][value]', $Title);
        $page->fillField('edit-field-description-0-value',$Description);
        $page->fillField('edit-field-caption-0-value',$Caption);
        $page->fillField('edit-field-email-0-value',$Email);
        $page->pressButton('Save');
        $this->getSession()->wait(2000);
        $this->assertSession()->pageTextContains($Title);
    }
    public function runsheetTeaserWithThreeFields($Title,$Email,$Caption)
    {
        $page = $this->getSession()->getPage();
        $this->getSession()->wait(2000);
        $page->fillField('title[0][value]', $Title);
        $page->fillField('edit-field-caption-0-value',$Caption);
        $page->fillField('edit-field-email-0-value',$Email);
        $page->pressButton('Save');
        $this->getSession()->wait(2000);
        $this->assertSession()->pageTextContains($Title);
    }
    /**
     * @When I create a runsheet :arg1
     */
    public function iCreateARunsheet($Runsheet)
    {
        $this->visitPath("/admin/structure/runsheet/add");
        $this->assertSession()->addressEquals("/admin/structure/runsheet/add");
        $page = $this->getSession()->getPage();
        $page->fillField('edit-label', $Runsheet);
        $this->getSession()->wait(2000);
        $page->fillField('positions[0][label]', 'left');
        $page->fillField('positions[0][id]', 'position0');
        $page->pressButton('Add position');
        $this->getSession()->wait(3000);
        $page->fillField('positions[1][label]', 'right');
        $page->fillField('positions[1][id]', 'position1');
        $page->pressButton('Save');
        $this->assertSession()->pageTextContains("Created the $Runsheet Runsheet.");
    }
    /**
     * @When I delete a runsheet
     */
    public function iDeleteARunsheet()
    {
        $page = $this->getSession()->getPage();
        $page->clickLink('Delete');
        $this->getSession()->getPage()->pressButton('Delete');
    }
    /**
     * @When I modified the existing runsheet label and runsheet positions
     */
    public function iModifiedTheExistingRunsheetLabelAndRunsheetPositions()
    {
        $page = $this->getSession()->getPage();
        $page->fillField('label', 'RunsheetModified');
        $page->fillField('positions[0][label]', 'Position0_Modified');
        $page->fillField('positions[0][id]', 'positionid_0');
        $page->fillField('positions[1][label]', 'Position1_Modified');
        $page->fillField('positions[1][id]', 'positionid_1');
        $page->fillField('positions[2][label]', 'Position2_Modified');
        $page->fillField('positions[2][id]', 'positionid_2');
        $page->fillField('positions[3][label]', 'Position3_Modified');
        $page->fillField('positions[3][id]', 'positionid_3');
        $page->pressButton('Save');
    }
    /**
     * @When I schedule an Item from content admin page
     */
    public function iScheduleAnItemFromContentAdminPage()
    {
        $this->visitPath("/admin/content/scheduled-items");
        $page = $this->getSession()->getPage();
        $page->clickLink('Schedule an item');
        $page->fillField('edit-card-id-0-target-id', 'TeaserForEpisode');
        $this->getSession()->wait(2000);
        $page->selectFieldOption('edit-runsheet-0-target-id', 'RunsheetModified');
        $this->getSession()->evaluateScript('jQuery(\'#edit-start-date-0-value-date\').val(\'2017-09-17\');');
        $this->getSession()->evaluateScript('jQuery(\'#edit-start-date-0-value-time\').val(\'09:00:00\');');
        $this->getSession()->evaluateScript('jQuery(\'#edit-end-date-0-value-date\').val(\'2017-09-18\');');
        $this->getSession()->evaluateScript('jQuery(\'#edit-end-date-0-value-time\').val(\'09:00:00\');');
        $this->getSession()->wait(1000);
        $page->pressButton('Save');
    }
    /**
     * @When I create a Runsheet to schedule an item
     */
    public function iCreateARunsheetToScheduleAnItem()
    {
        $this->assertSession()->addressEquals("/admin/structure/runsheet");
        $page = $this->getSession()->getPage();
        $page->clickLink('Add Runsheet');
        $this->assertSession()->addressEquals("/admin/structure/runsheet/add");
        $page->fillField('edit-label', 'RunsheetMovie');
        $this->getSession()->wait(2000);
        $page->fillField('edit-positions-0-label', 'Movielabel');
        $page->fillField('edit-positions-0-id', 'position1');
        $page->pressButton('Save');
        $this->assertSession()->pageTextContains('Created the RunsheetMovie Runsheet.');
    }
    /**
     * @When I Schedule an item from Runsheet operations drop down
     */
    public function iScheduleAnItemFromRunsheetOperationsDropDown()
    {
        $this->visitPath("/admin/structure/runsheet");
        $this->assertSession()->pageTextContains("RunsheetMovie");
        // Click on Runsheet Operations dropdown and select Schedule an Item
        $this->getSession()->wait(2000);
        $dropdown_button = "//div[@id='block-seven-content']/table/tbody/tr[2]/td[3]/div/div/ul/li[2]/button";
        $this->getSession()->getDriver()->click($dropdown_button);
        $this->getSession()->wait(1000);
        $ScheduleAnItem = "//div[@id='block-seven-content']/table/tbody/tr[2]/td[3]/div/div/ul/li[5]/a";
        $this->getSession()->getDriver()->click($ScheduleAnItem);
        $this->getSession()->wait(1000);
        //Schedule an item
        $page = $this->getSession()->getPage();
        $this->assertSession()->pageTextContains("Schedule an item");
        $page->fillField('edit-card-id-0-target-id', 'TeaserForSeries');
        $this->getSession()->wait(2000);
        $this->getSession()->evaluateScript('jQuery(\'#edit-start-date-0-value-date\').val(\'2017-09-17\');');
        $this->getSession()->evaluateScript('jQuery(\'#edit-start-date-0-value-time\').val(\'09:00:00\');');
        $this->getSession()->evaluateScript('jQuery(\'#edit-end-date-0-value-date\').val(\'2017-09-18\');');
        $this->getSession()->evaluateScript('jQuery(\'#edit-end-date-0-value-time\').val(\'09:00:00\');');
        $page->pressButton('Save');
        $this->getSession()->wait(2000);
    }
    /**
     * @When I click on Edit dropdown button to Schedule an item
     */
    public function iClickOnEditDropdownButtonToScheduleAnItem()
    {
        $this->visitPath("/admin/content/runsheet-teasers");
        // Click on Runsheet teasers edit dropdown and select Schedule an Item
        $this->getSession()->wait(2000);
        $dropdown_button = "//div[@id='block-seven-content']/div/div/div[3]/table/tbody/tr/td[4]/div/div/ul/li[2]/button";
        $this->getSession()->getDriver()->click($dropdown_button);
        $this->getSession()->wait(1000);
        $Schedule = "//div[@id='block-seven-content']/div/div/div[3]/table/tbody/tr/td[4]/div/div/ul/li[4]/a";
        $this->getSession()->getDriver()->click($Schedule);
        $this->getSession()->wait(1000);
        //Schedule an item
        $page = $this->getSession()->getPage();
        $this->assertSession()->pageTextContains("Schedule an item");
        $page->fillField('edit-card-id-0-target-id', 'TeaserForSeason');
        $this->getSession()->wait(2000);
        $page->selectFieldOption('edit-runsheet-0-target-id', 'RunsheetModified');
        $this->getSession()->evaluateScript('jQuery(\'#edit-start-date-0-value-date\').val(\'2017-10-20\');');
        $this->getSession()->evaluateScript('jQuery(\'#edit-start-date-0-value-time\').val(\'09:00:00\');');
        $this->getSession()->evaluateScript('jQuery(\'#edit-end-date-0-value-date\').val(\'2017-10-21\');');
        $this->getSession()->evaluateScript('jQuery(\'#edit-end-date-0-value-time\').val(\'09:00:00\');');
        $this->getSession()->wait(2000);
        $page->pressButton('Save');
    }
    /**
     * @Then I schedule an item timeline view page
     */
    public function iScheduleAnItemTimelineViewPage()
    {
        //Schedule an item
        $page = $this->getSession()->getPage();
        $this->assertSession()->pageTextContains("Schedule an item");
        $page->fillField('edit-card-id-0-target-id', 'TeaserForSeason');
        $this->getSession()->wait(2000);
        $this->getSession()->evaluateScript('jQuery(\'#edit-start-date-0-value-date\').val(\'2017-09-20\');');
        $this->getSession()->evaluateScript('jQuery(\'#edit-start-date-0-value-time\').val(\'09:00:00\');');
        $this->getSession()->evaluateScript('jQuery(\'#edit-end-date-0-value-date\').val(\'2017-09-21\');');
        $this->getSession()->evaluateScript('jQuery(\'#edit-end-date-0-value-time\').val(\'09:00:00\');');
        $this->getSession()->wait(2000);
        $page->pressButton('Save');
        $this->getSession()->wait(2000);
    }
    /**
     * @When I Edit Movie runsheet teaser
     */
    public function iEditMovieRunsheetTeaser()
    {
        $this->assertSession()->pageTextContains('Edit Runsheet teaser');
        $this->getSession()->getPage()->fillField('edit-title-0-value', 'TeaserModified_1');
        $this->getSession()->getPage()->pressButton('Save');
        $this->assertSession()->pageTextContains('Saved the TeaserModified_1 Runsheet teaser.');
        $this->assertSession()->pageTextNotContains('TeaserForMovie');
    }
    /**
     * @When I click on operation dropdown and edit Episode teaser
     */
    public function iClickOnOperationDropdownAndEditEpisodeTeaser()
    {
        $this->assertSession()->pageTextContains('TeaserForEpisode');
        $this->getSession()->getDriver()->click("//div[@id='block-seven-content']/div/div/div[3]/table/tbody/tr/td[7]/div/div/ul/li[2]/button");
        $page = $this->getSession()->getPage();
        $page->clickLink("Edit teaser");
        $this->assertSession()->pageTextContains('Edit Runsheet teaser');
        $this->getSession()->getPage()->fillField('edit-title-0-value', 'TeaserModified_2');
        $this->getSession()->getPage()->pressButton("Save");
    }
    /**
     * @When I Delete AmericanDad runsheet teaser
     */
    public function iDeleteAmericandadRunsheetTeaser()
    {
        $this->assertSession()->pageTextContains('Edit Runsheet teaser');
        $page = $this->getSession()->getPage();
        $page->clickLink("Delete");
        $this->assertSession()->pageTextContains('Are you sure you want to delete the runsheet teaser AmericanDad?');
        $this->getSession()->getPage()->pressButton('Delete');
        $this->assertSession()->pageTextContains('The runsheet teaser AmericanDad has been deleted.');
    }
    /**
     * @When I Edit the Scheduled teaser
     */
    public function iEditTheScheduledTeaser()
    {
        $page = $this->getSession()->getPage();
        $page->fillField('edit-card-id-0-target-id', 'TeaserForSeason');
        $this->getSession()->wait(2000);
        $this->getSession()->evaluateScript('jQuery(\'#edit-start-date-0-value-date\').val(\'2017-09-19\');');
        $this->getSession()->evaluateScript('jQuery(\'#edit-start-date-0-value-time\').val(\'09:00:00\');');
        $this->getSession()->evaluateScript('jQuery(\'#edit-end-date-0-value-date\').val(\'2017-09-20\');');
        $this->getSession()->evaluateScript('jQuery(\'#edit-end-date-0-value-time\').val(\'09:00:00\');');
        $this->getSession()->wait(1000);
        $page->pressButton('Save');
        $this->getSession()->wait(1000);
    }
    /**
     * @Then I should see changes gets updated and saved for schedule item
     */
    public function iShouldSeeChangesGetsUpdatedAndSavedForScheduleItem()
    {
        $this->visitPath("/admin/content/scheduled-items");
        $this->assertSession()->addressEquals("/admin/content/scheduled-items");
        $this->assertSession()->pageTextContains('TeaserForSeason');
        $this->assertSession()->pageTextContains('Tuesday, September 19, 2017 - 09:00');
        $this->assertSession()->pageTextContains('Wednesday, September 20, 2017 - 09:00');
    }
    /**
     * @When I Schdeule an items
     */
    public function iSchdeuleAnItems()
    {
        //Create a bundle
        $this->iCreateARunsheetTeaserBundle('Bundle Season');
        $this->getSession()->wait(2000);
        //Create runsheet teasers
        $this->visitPath("/admin/runsheet_teaser/add/bundle_episode");
        $this->iCreateARunsheetTeaser('TeaserEpisode');
        $this->visitPath("/admin/runsheet_teaser/add/bundle_movie");
        $this->iCreateARunsheetTeaser('TeaserMovie');
        $this->visitPath("/admin/runsheet_teaser/add/bundle_series");
        $this->iCreateARunsheetTeaser('TeaserSeries');
        $this->visitPath("/admin/runsheet_teaser/add/bundle_season");
        $this->iCreateARunsheetTeaser('TeaserSeason');
        //Schedule items
        $this->visitPath("/admin/content/scheduled-items");
        $this->ScheduleAnItem('TeaserEpisode', 'RunsheetEpisode');
        $this->ScheduleAnItem('TeaserMovie', 'RunsheetMovie');
        $this->ScheduleAnItem('TeaserSeries', 'RunsheetSeries');
        $this->ScheduleAnItem('TeaserSeason', 'RunsheetSeason');
        $this->ScheduleAnItem('TeaserEpisode', 'RunsheetEpisode');
        $this->ScheduleAnItem('TeaserMovie', 'RunsheetMovie');
        $this->ScheduleAnItem('TeaserSeries', 'RunsheetSeries');
        $this->ScheduleAnItem('TeaserSeason', 'RunsheetSeason');
        $this->ScheduleAnItem('TeaserEpisode', 'RunsheetEpisode');
        $this->ScheduleAnItem('TeaserMovie', 'RunsheetMovie');
        $this->ScheduleAnItem('TeaserSeries', 'RunsheetSeries');
        $this->ScheduleAnItem('TeaserSeason', 'RunsheetSeason');
    }
    public function ScheduleAnItem($Teaser, $Runsheet)
    {
        $this->visitPath("/admin/schedule-item/add");
        $page = $this->getSession()->getPage();
        $this->assertSession()->pageTextContains("Schedule an item");
        $this->getSession()->wait(1000);
        $page->fillField('edit-card-id-0-target-id', $Teaser);
        $this->getSession()->wait(1000);
        $page->selectFieldOption('edit-runsheet-0-target-id', $Runsheet);
        $this->getSession()->wait(1000);
        $this->getSession()->evaluateScript('jQuery(\'#edit-start-date-0-value-date\').val(\'2017-09-22\');');
        $this->getSession()->evaluateScript('jQuery(\'#edit-start-date-0-value-time\').val(\'09:00:00\');');
        $this->getSession()->evaluateScript('jQuery(\'#edit-end-date-0-value-date\').val(\'2017-09-23\');');
        $this->getSession()->evaluateScript('jQuery(\'#edit-end-date-0-value-time\').val(\'09:00:00\');');
        $page->pressButton('Save');
        $this->getSession()->wait(1000);
    }
    public function FirstTeaser($Teaser, $Runsheet)
    {
        $this->visitPath("/admin/schedule-item/add");
        $page = $this->getSession()->getPage();
        $this->assertSession()->pageTextContains("Schedule an item");
        $this->getSession()->wait(1000);
        $page->fillField('edit-card-id-0-target-id', $Teaser);
        $this->getSession()->wait(1000);
        $page->selectFieldOption('edit-runsheet-0-target-id', $Runsheet);
        $this->getSession()->wait(1000);
        $this->getSession()->evaluateScript('jQuery(\'#edit-start-date-0-value-date\').val(\'2017-10-01\');');
        $this->getSession()->evaluateScript('jQuery(\'#edit-start-date-0-value-time\').val(\'09:00:00\');');
        $this->getSession()->evaluateScript('jQuery(\'#edit-end-date-0-value-date\').val(\'2017-10-02\');');
        $this->getSession()->evaluateScript('jQuery(\'#edit-end-date-0-value-time\').val(\'08:59:59\');');
        $page->pressButton('Save');
        $this->getSession()->wait(1000);
    }
    public function SecondTeaser($Teaser, $Runsheet)
    {
        $this->visitPath("/admin/schedule-item/add");
        $page = $this->getSession()->getPage();
        $this->assertSession()->pageTextContains("Schedule an item");
        $this->getSession()->wait(1000);
        $page->fillField('edit-card-id-0-target-id', $Teaser);
        $this->getSession()->wait(1000);
        $page->selectFieldOption('edit-runsheet-0-target-id', $Runsheet);
        $this->getSession()->wait(1000);
        $this->getSession()->evaluateScript('jQuery(\'#edit-start-date-0-value-date\').val(\'2017-10-03\');');
        $this->getSession()->evaluateScript('jQuery(\'#edit-start-date-0-value-time\').val(\'09:00:00\');');
        $this->getSession()->evaluateScript('jQuery(\'#edit-end-date-0-value-date\').val(\'2017-10-04\');');
        $this->getSession()->evaluateScript('jQuery(\'#edit-end-date-0-value-time\').val(\'08:59:59\');');
        $page->pressButton('Save');
        $this->getSession()->wait(1000);
    }
    public function ThirdTeaser($Teaser, $Runsheet)
    {
        $this->visitPath("/admin/schedule-item/add");
        $page = $this->getSession()->getPage();
        $this->assertSession()->pageTextContains("Schedule an item");
        $this->getSession()->wait(1000);
        $page->fillField('edit-card-id-0-target-id', $Teaser);
        $this->getSession()->wait(1000);
        $page->selectFieldOption('edit-runsheet-0-target-id', $Runsheet);
        $this->getSession()->wait(1000);
        $this->getSession()->evaluateScript('jQuery(\'#edit-start-date-0-value-date\').val(\'2017-10-05\');');
        $this->getSession()->evaluateScript('jQuery(\'#edit-start-date-0-value-time\').val(\'09:00:00\');');
        $this->getSession()->evaluateScript('jQuery(\'#edit-end-date-0-value-date\').val(\'2017-10-06\');');
        $this->getSession()->evaluateScript('jQuery(\'#edit-end-date-0-value-time\').val(\'08:59:59\');');
        $page->pressButton('Save');
        $this->getSession()->wait(1000);
    }
    /**
     * @Then I should see :arg1 scheduled items per page
     */
    public function iShouldSeeScheduledItemsPerPage($count)
    {
        $this->getSession()->wait(1000);
        $this->assertSession()->elementsCount('css', 'tbody tr', $count);
        //Page 2
        $this->visitPath("/admin/content/scheduled-items?rs=All&si=&page=1");
        $this->getSession()->wait(1000);
        $this->assertSession()->elementsCount('css', 'tbody tr', $count);
        //Page 3
        $this->visitPath("/admin/content/scheduled-items?rs=All&si=&page=2");
        $this->getSession()->wait(1000);
        $this->assertSession()->elementsCount('css', 'tbody tr', $count);
    }
    /**
     * @Given I create :arg1 runsheet teasers for different types
     */
    public function iCreateRunsheetTeasersForDifferentTypes($Teasers)
    {
        if ($Teasers > 30) {
            $this->runsheetTeasers('bundle_episode', 11, 40);
            $this->runsheetTeasers('bundle_movie', 11, 40);
            $this->runsheetTeasers('bundle_series', 11, 40);
        } else {
            $this->runsheetTeasers('bundle_episode', 11, 30);
        }
    }
    public function runsheetTeasers($TYPE, $low, $high)
    {
        $this->visitPath("/admin/runsheet_teaser/add/$TYPE");
        foreach (range($low, $high) as $val) {
            $values = ['type' => $TYPE,
                'title' => 'Teaser ' . $val];
            $entity = \Drupal\draco_runsheet\Entity\RunsheetTeaser::create($values);
            $entity->save();
        }
    }
    /**
     * @When I open runsheet teasers view page
     */
    public function iOpenRunsheetTeasersViewPage()
    {
        $this->visitPath("/admin/structure/views/view/runsheet_teasers");
    }
    /**
     * @When I open Scheduled items view page
     */
    public function iOpenScheduledItemsViewPage()
    {
        $this->visitPath("/admin/structure/views/view/schedule_cards");
    }
    /**
     * @Then I should see :arg1 Runsheet teasers per page
     */
    public function iShouldSeeRunsheetTeasersPerPage($count)
    {
        $this->getSession()->wait(1000);
        $this->assertSession()->elementsCount('css', 'tbody tr', $count);
        //Page 2
        $this->visitPath("/admin/content/runsheet-teasers?title=&type=All&page=1");
        $this->getSession()->wait(1000);
        $this->assertSession()->elementsCount('css', 'tbody tr', $count);
        //Page 3
        $this->visitPath("/admin/content/runsheet-teasers?title=&type=All&page=2");
        $this->getSession()->wait(1000);
        $this->assertSession()->elementsCount('css', 'tbody tr', $count);
    }
    /**
     * @When I search runsheet teasers with Title :arg1
     */
    public function iSearchRunsheetTeasersWithTitle($Title)
    {
        $page = $this->getSession()->getPage();
        $page->fillField('edit-title', $Title);
        $page->pressButton('Apply');
        $this->getSession()->wait(5000);
    }
    /**
     * @Then I should see all runsheet teasers with Title :arg1
     */
    public function iShouldSeeAllRunsheetTeasersWithTitle($Title)
    {
        $this->assertSession()->pageTextContains($Title);
        $this->assertSession()->elementsCount('css', 'tbody tr', 3);
    }
    /**
     * @When I search runsheet teasers with Type :arg1
     */
    public function iSearchRunsheetTeasersWithType($Type)
    {
        $page = $this->getSession()->getPage();
        $page->pressButton('edit-reset');
        $page->selectFieldOption('edit-type', $Type);
        $page->pressButton('Apply');
        $this->getSession()->wait(5000);
    }
    /**
     * @Then I should see all runsheet teasers with Type :arg1
     */
    public function iShouldSeeAllRunsheetTeasersWithType($Type)
    {
        $this->assertSession()->pageTextContains($Type);
        $this->assertSession()->elementsCount('css', 'tbody tr', 30);
    }
    /**
     * @When I search runsheet teasers with Title :arg1 and Type :arg2
     */
    public function iSearchRunsheetTeasersWithTitleAndType($Title, $Type)
    {
        $page = $this->getSession()->getPage();
        $page->pressButton('edit-reset');
        $page->fillField('edit-title', $Title);
        $page->selectFieldOption('edit-type', $Type);
        $page->pressButton('Apply');
        $this->getSession()->wait(5000);
    }
    /**
     * @Then I should see only one runsheet teasers with Title :arg1 and Type :arg2
     */
    public function iShouldSeeOnlyOneRunsheetTeasersWithTitleAndType($Title, $Type)
    {
        $this->assertSession()->pageTextContains($Title);
        $this->assertSession()->pageTextContains($Type);
        $this->assertSession()->elementsCount('css', 'tbody tr', 1);
    }
    /**
     * @Given I logged in as admin
     */
    public function iLoggedInAsAdmin()
    {
        $this->visitPath("/user/login");
        //enter username and password
        $this->getSession()->getDriver()->setValue('.//input[@id=\'edit-name\']', 'draco');
        $this->getSession()->getDriver()->setValue('.//input[@id=\'edit-pass\']', 'draco');
        //click login button
        $this->getSession()->getDriver()->click('.//input[@id=\'edit-submit\']');
    }
    /**
     * @When I change the user pager to Mini
     */
    public function iChangeTheUserPagerToMini()
    {
        $this->assertSession()->addressEquals("/admin/structure/views/view/runsheet_teasers");
        $page = $this->getSession()->getPage();
        $this->getSession()->wait(1000);
        $page->clickLink("Full");
        $this->getSession()->wait(1000);
        $this->getSession()->getDriver()->switchToIFrame();
        $iFrame = $this->getSession()->getPage();
        $iFrame->selectFieldOption('', '', '');
        $this->getSession()->wait(3000);
        $this->getSession()->getDriver()->selectOption('.//*[@id=\'edit-pager-type--4AY2lcVNxYU\']/div[4]/label', ' Paged output, mini pager');
        $this->getSession()->getDriver()->click("html/body/div[6]/div[3]/div/button[1]");
        $this->getSession()->wait(1000);
//        $page->clickLink("Paged, 10 items");
//        $this->getSession()->getDriver()->switchToIFrame();
//        $this->getSession()->wait(1000);
//        $this->getSession()->getPage()->fillField('pager_options[items_per_page]','5');
//        $this->getSession()->wait(1000);
//        $this->getSession()->getDriver()->click("html/body/div[6]/div[3]/div/button[1]");
//        $this->getSession()->getPage()->clickLink('Save');
//        $this->assertSession()->pageTextContains('The view Schedule cards has been saved.');
    }
    /**
     * @When I search runsheet teaser with invalid title
     */
    public function iSearchRunsheetTeaserWithInvalidTitle()
    {
        $page = $this->getSession()->getPage();
        $page->fillField('edit-title', 'InvalidTitle');
        $page->pressButton('Apply');
        $this->getSession()->wait(2000);
    }
    /**
     * @Then I should not see any runsheet teaser
     */
    public function iShouldNotSeeAnyRunsheetTeaser()
    {
        $this->assertSession()->elementsCount('css', 'tbody tr', 0);
    }
    /**
     * @Then I search runsheet teaser with special characters
     */
    public function iSearchRunsheetTeaserWithSpecialCharacters()
    {
        $page = $this->getSession()->getPage();
        $page->fillField('edit-title', '^@*^(@*^(*#^(*@#^(#@');
        $page->pressButton('Apply');
        $this->getSession()->wait(2000);
    }
    /**
     * @Then I search runsheet teaser with empty spaces
     */
    public function iSearchRunsheetTeaserWithEmptySpaces()
    {
        $page = $this->getSession()->getPage();
        $page->fillField('edit-title', '          ');
        $page->pressButton('Apply');
        $this->getSession()->wait(2000);
    }
    /**
     * @Then I should see only :arg1 runsheet teasers on the page
     */
    public function iShouldSeeOnlyRunsheetTeasersOnThePage($count)
    {
        $this->assertSession()->elementsCount('css', 'tbody tr', $count);
    }
    /**
     * @Then I should not see pagination at the bottom of the page
     */
    public function iShouldNotSeePaginationAtTheBottomOfThePage()
    {
        //Page 2
        $this->visitPath("/admin/content/runsheet-teasers?title=&type=All&page=1");
        $this->getSession()->wait(1000);
        $this->assertSession()->elementsCount('css', 'tbody tr', 0);
        //Page 3
        $this->visitPath("/admin/content/runsheet-teasers?title=&type=All&page=2");
        $this->getSession()->wait(1000);
        $this->assertSession()->elementsCount('css', 'tbody tr', 0);
    }
    /**
     * @When I search Scheduled Items with Runsheet :arg1
     */
    public function iSearchScheduledItemsWithRunsheet($Runsheet)
    {
        $page = $this->getSession()->getPage();
        $page->selectFieldOption('edit-rs', $Runsheet);
        $page->pressButton('Apply');
        $this->getSession()->wait(5000);
    }
    /**
     * @Then I should see :arg2 Scheduled items with Runsheet :arg1
     */
    public function iShouldSeeScheduledItemsWithRunsheet($count, $Runsheet)
    {
        $this->getSession()->wait(1000);
        $this->assertSession()->pageTextContains($Runsheet);
        $this->assertSession()->elementsCount('css', 'tbody tr', $count);
    }
    /**
     * @Then I search Scheduled Items with teaser :arg1
     */
    public function iSearchScheduledItemsWithTeaser($Teaser)
    {
        $page = $this->getSession()->getPage();
        $page->pressButton('edit-reset');
        $page->fillField('edit-si', $Teaser);
        $page->pressButton('Apply');
        $this->getSession()->wait(5000);
    }
    /**
     * @Then I should see :arg2 Scheduled items with teaser :arg1
     */
    public function iShouldSeeScheduledItemsWithTeaser($count, $Teaser)
    {
        $this->getSession()->wait(1000);
        $this->assertSession()->pageTextContains($Teaser);
        $this->assertSession()->elementsCount('css', 'tbody tr', $count);
    }
    /**
     * @Then I search Scheduled Items with Runsheet :arg1 and Teaser :arg2
     */
    public function iSearchScheduledItemsWithRunsheetAndTeaser($Runsheet, $Teaser)
    {
        $page = $this->getSession()->getPage();
        $page->pressButton('edit-reset');
        $page->selectFieldOption('edit-rs', $Runsheet);
        $page->fillField('edit-si', $Teaser);
        $page->pressButton('Apply');
        $this->getSession()->wait(5000);
    }
    /**
     * @Then I should see :arg3 Scheduled items with Runsheet :arg1 and Teaser :arg2
     */
    public function iShouldSeeScheduledItemsWithRunsheetAndTeaser($count, $Runsheet, $Teaser)
    {
        $this->getSession()->wait(1000);
        $this->assertSession()->pageTextContains($Runsheet);
        $this->assertSession()->pageTextContains($Teaser);
        $this->assertSession()->elementsCount('css', 'tbody tr', $count);
    }
    /**
     * @When I search Scheduled item with invalid teaser :arg1
     */
    public function iSearchScheduledItemWithInvalidTeaser($Teaser)
    {
        $this->visitPath("/admin/content/scheduled-items");
        $page = $this->getSession()->getPage();
        $page->fillField('edit-si', $Teaser);
        $page->pressButton('Apply');
        $this->getSession()->wait(2000);
    }
    /**
     * @When I search Scheduled item with Special Characters :arg1
     */
    public function iSearchScheduledItemWithSpecialCharacters($Teaser)
    {
        $page = $this->getSession()->getPage();
        $page->pressButton('edit-reset');
        $page->fillField('edit-si', $Teaser);
        $page->pressButton('Apply');
        $this->getSession()->wait(2000);
    }
    /**
     * @When I search Scheduled item with Empty Spaces :arg1
     */
    public function iSearchScheduledItemWithEmptySpaces($Teaser)
    {
        $page = $this->getSession()->getPage();
        $page->pressButton('edit-reset');
        $page->fillField('edit-si', $Teaser);
        $page->pressButton('Apply');
        $this->getSession()->wait(2000);
    }
    /**
     * @Given I Scheduled :arg1 items
     */
    public function iScheduledItems($arg1)
    {
        //Create runsheet teasers
        $this->visitPath("/admin/runsheet_teaser/add/bundle_episode");
        $this->iCreateARunsheetTeaser('TeaserEpisode');
        $this->visitPath("/admin/runsheet_teaser/add/bundle_movie");
        $this->iCreateARunsheetTeaser('TeaserMovie');
        $this->visitPath("/admin/runsheet_teaser/add/bundle_series");
        $this->iCreateARunsheetTeaser('TeaserSeries');
        //Schedule items
        $this->visitPath("/admin/content/scheduled-items");
        $this->ScheduleAnItem('TeaserEpisode', 'RunsheetEpisode');
        $this->ScheduleAnItem('TeaserMovie', 'RunsheetMovie');
        $this->ScheduleAnItem('TeaserSeries', 'RunsheetSeries');
    }
    /**
     * @Then I should see only :arg1 Scheduled items on the page
     */
    public function iShouldSeeOnlyScheduledItemsOnThePage($count)
    {
        $this->getSession()->wait(1000);
        $this->assertSession()->elementsCount('css', 'tbody tr', $count);
    }
    /**
     * @Then I should not see pagination on Scheduled item page
     */
    public function iShouldNotSeePaginationOnScheduledItemPage()
    {
        //Page 2
        $this->visitPath("/admin/content/scheduled-items?rs=All&si=&page=1");
        $this->getSession()->wait(1000);
        $this->assertSession()->elementsCount('css', 'tbody tr', 0);
        //Page 3
        $this->visitPath("/admin/content/scheduled-items?rs=All&si=&page=2");
        $this->getSession()->wait(1000);
        $this->assertSession()->elementsCount('css', 'tbody tr', 0);
    }
    /**
     * @Given I Scheduled :arg1 items for same teaser
     */
    public function iScheduledItemsForSameTeaser($arg1)
    {
        //Create runsheet teasers
        $this->visitPath("/admin/runsheet_teaser/add/bundle_episode");
        $this->iCreateARunsheetTeaser('TeaserEpisode');
        //Schedule an Item
        $this->FirstTeaser('TeaserEpisode','RunsheetEpisode');
        $this->SecondTeaser('TeaserEpisode','RunsheetEpisode');
    }
    /**
     * @When I configure different fields to multiple teasers
     */
    public function iConfigureDifferentFieldsToMultipleTeasers()
    {
        $this->visitPath("/admin/runsheet_teaser/add/bundle_episode");
        $this->runsheetTeaserWithFourFields('TeaserEpisode','draco@qa.com','GOTSpoilers','Jon organizes the defense of the North.Daenerys comes home.');
        $this->visitPath("/admin/runsheet_teaser/add/bundle_episode");
        $this->runsheetTeaserWithThreeFields('TeaserSeries','test@qa.com','ThreeRangers');
        $this->visitPath("/admin/runsheet_teaser/add/bundle_episode");
        $this->iCreateARunsheetTeaser('TeaserMovie');
    }
    /**
     * @Given I Scheduled :arg1 items for different teasers
     */
    public function iScheduledItemsForDifferentTeasers($arg1)
    {
        //Schedule an Item
        $this->FirstTeaser('TeaserEpisode','RunsheetEpisode');
        $this->SecondTeaser('TeaserSeries','RunsheetEpisode');
        $this->ThirdTeaser('TeaserMovie','RunsheetEpisode');
    }
    /**
     * @When I schedule an item
     */
    public function iScheduleAnItem()
    {
        $this->FirstTeaser('TeaserEpisode','RunsheetEpisode');
    }
    /**
     * @When I click on first Teaser
     */
    public function iClickOnFirstTeaser()
    {
        $this->visitPath('/admin/runsheet/runsheetepisode/timeline');
        $this->getSession()->wait(2000);
        $this->getSession()->getDriver()->click("//div[@id='runsheet-timeline']/div/div[4]/div[1]/div/div[2]/div[1]/div[1]/div[1]/div/div");
    }
    /**
     * @Then I should see Details of First Teaser
     */
    public function iShouldSeeDetailsOfFirstTeaser()
    {
        $this->getSession()->wait(2000);
        $this->assertSession()->pageTextContains("Details of TeaserEpisode");
        $this->assertSession()->pageTextContains("Title");
        $this->assertSession()->pageTextContains("TeaserEpisode");
    }
    /**
     * @When I click on Second Teaser
     */
    public function iClickOnSecondTeaser()
    {
        $this->visitPath('/admin/runsheet/runsheetepisode/timeline');
        $this->getSession()->wait(2000);
        $this->getSession()->getDriver()->click("//div[@id='runsheet-timeline']/div/div[4]/div[1]/div/div[2]/div[1]/div[2]/div[1]/div/div");
    }
    /**
     * @Then I should see Details of Second TeaserTeaser
     */
    public function iShouldSeeDetailsOfSecondTeaserteaser()
    {
        $this->getSession()->wait(3000);
        $this->assertSession()->pageTextContains("Details of TeaserEpisode");
        $this->assertSession()->pageTextContains("Title");
        $this->assertSession()->pageTextContains("TeaserEpisode");
    }
    /**
     * @Given I add more fields to the runsheet teaser bundle
     */
    public function iAddMoreFieldsToTheRunsheetTeaserBundle()
    {
        $this->visitPath("/admin/structure/runsheet_teaser_bundle");
        $this->getSession()->getDriver()->click("//div[@id='block-seven-content']/table/tbody/tr/td[3]/div/div/ul/li[2]/button");
        $this->getSession()->wait(2000);
        $this->getSession()->getDriver()->click("//div[@id='block-seven-content']/table/tbody/tr/td[3]/div/div/ul/li[3]/a");
        $this->assertSession()->pageTextContains("Manage fields");
        $this->addFieldToBundle('Text (plain, long)','Description');
        $this->addFieldToBundle('Text (plain, long)','Caption');
        $this->addFieldToBundle('Email','Email');
    }
    public function addFieldToBundle($Field,$Name){
        $this->getSession()->getPage()->clickLink("Add field");
        $this->assertSession()->pageTextContains("Add field");
        $this->getSession()->wait(2000);
        $this->getSession()->getPage()->selectFieldOption('Add a new field',$Field);
        $this->getSession()->getPage()->fillField('edit-label',$Name);
        $this->getSession()->wait(2000);
        $this->getSession()->getPage()->pressButton('Save and continue');
        $this->getSession()->wait(2000);
        $this->getSession()->getPage()->pressButton('Save field settings');
        $this->getSession()->wait(2000);
        $this->getSession()->getPage()->pressButton('Save settings');
        $this->assertSession()->pageTextContains("Saved $Name configuration.");
    }
    /**
     * @When I Configure the teaser with new fields
     */
    public function iConfigureTheTeaserWithNewFields()
    {
        $this->visitPath("/admin/runsheet_teaser/add/bundle_episode");
        $this->runsheetTeaserWithFourFields('TeaserEpisode','draco@qa.com','GOTSpoilers','Jon organizes the defense of the North.Daenerys comes home.');
    }
    public function iConfigureFirstTeaser(){
        $this->getSession()->getDriver()->click("//div[@id='block-seven-content']/div/div/div[3]/table/tbody/tr[1]/td[7]/div/div/ul/li[2]/button");
        $this->getSession()->wait(2000);
        $this->getSession()->getDriver()->click("//div[@id='block-seven-content']/div/div/div[3]/table/tbody/tr[1]/td[7]/div/div/ul/li[4]/a");
        $this->getSession()->getPage()->fillField('edit-field-description-0-value','Jon organizes the defense of the North.Cersei tries to even the odds.Daenerys comes home.');
        $this->getSession()->getPage()->fillField('edit-field-caption-0-value','Games Of Thrones Spoilers');
        $this->getSession()->getPage()->fillField('edit-field-email-0-value','draco@qa.com');
        $this->getSession()->wait(2000);
        $this->getSession()->getPage()->pressButton('Save');
    }
    /**
     * @Then I should see the configured fields data in the Details of teaser
     */
    public function iShouldSeeTheConfiguredFieldsDataInTheDetailsOfTeaser()
    {
        $this->getSession()->wait(3000);
        $this->visitPath("/admin/runsheet/runsheetepisode/timeline");
        $this->getSession()->wait(3000);
        $this->getSession()->getDriver()->click("//div[@id='runsheet-timeline']/div/div[4]/div[1]/div/div[2]/div[1]/div[1]/div[1]/div/div");
        $this->getSession()->wait(3000);
        $this->assertSession()->pageTextContains("Details of TeaserEpisode");
        $this->assertSession()->pageTextContains("Title");
        $this->assertSession()->pageTextContains("Description");
        $this->assertSession()->pageTextContains("Jon organizes the defense of the North.Daenerys comes home.");
        $this->assertSession()->pageTextContains("Caption");
        $this->assertSession()->pageTextContains("GOTSpoilers");
        $this->assertSession()->pageTextContains("Email");
        $this->assertSession()->pageTextContains("draco@qa.com");
    }
    public function iConfigureSecondTeaser(){
        $this->getSession()->getDriver()->click("//div[@id='block-seven-content']/div/div/div[3]/table/tbody/tr[2]/td[7]/div/div/ul/li[2]/button");
        $this->getSession()->wait(2000);
        $this->getSession()->getDriver()->click("//div[@id='block-seven-content']/div/div/div[3]/table/tbody/tr[2]/td[7]/div/div/ul/li[4]/a");
        $this->getSession()->getPage()->fillField('edit-field-email-0-value','draco@qa.com');
        $this->getSession()->wait(2000);
        $this->getSession()->getPage()->pressButton('Save');
        $this->getSession()->wait(2000);
        $this->assertSession()->pageTextContains('Saved the TeaserEpisode Runsheet teaser.');
    }
    /**
     * @Then the Details of multiple teasers should be displayed as configured
     */
    public function theDetailsOfMultipleTeasersShouldBeDisplayedAsConfigured()
    {
        $this->iShouldSeeTheConfiguredFieldsDataInTheDetailsOfTeaser();
        $this->secondTeaserOnTimeline();
        $this->thirdTeaserOnTimeline();
    }
    public function secondTeaserOnTimeline(){
        $this->getSession()->wait(3000);
        $this->visitPath("/admin/runsheet/runsheetepisode/timeline");
        $this->getSession()->wait(3000);
        $this->getSession()->getDriver()->click("//div[@id='runsheet-timeline']/div/div[4]/div[1]/div/div[2]/div[1]/div[2]/div[1]/div/div");
        $this->getSession()->wait(3000);
        $this->assertSession()->pageTextContains("Details of TeaserSeries");
        $this->assertSession()->pageTextContains("Title");
        $this->assertSession()->pageTextContains("Email");
        $this->assertSession()->pageTextContains("test@qa.com");
        $this->assertSession()->pageTextContains("Caption");
        $this->assertSession()->pageTextContains("ThreeRangers");
    }
    public function thirdTeaserOnTimeline(){
        $this->getSession()->wait(3000);
        $this->visitPath("/admin/runsheet/runsheetepisode/timeline");
        $this->getSession()->wait(3000);
        $this->getSession()->getDriver()->click("//div[@id='runsheet-timeline']/div/div[4]/div[1]/div/div[2]/div[1]/div[3]/div[1]/div/div");
        $this->getSession()->wait(3000);
        $this->assertSession()->pageTextContains("Details of TeaserMovie");
        $this->assertSession()->pageTextContains("Title");
    }
    /**
     * Remove Runsheet Teasers Bundle.
     *
     * @AfterScenario
     */
    public function cleanupRunsheetBundle()
    {
        $storage = \Drupal::entityTypeManager()->getStorage('runsheet_teaser_bundle');
        $ids = $storage->getQuery()->condition('id', 'bundle_', 'STARTS_WITH')->execute();
        $entities = $storage->loadMultiple($ids);
        $storage->delete($entities);
    }
    /**
     * Removes sample Runsheets.
     *
     * @AfterScenario
     */
    public function cleanupRunsheets()
    {
        $storage = \Drupal::entityTypeManager()->getStorage('runsheet');
        $ids = $storage->getQuery()->condition('id', 'runsheet', 'STARTS_WITH')->execute();
        $entities = $storage->loadMultiple($ids);
        $storage->delete($entities);
    }
    /**
     * Remove sample Runsheet teasers.
     *
     * @AfterScenario
     */
    public function cleanupRunsheetTeasers()
    {
        $storage = \Drupal::entityTypeManager()->getStorage('runsheet_teaser');
        $ids = $storage->getQuery()->condition('title', 'teaser', 'STARTS_WITH')->execute();
        $entities = $storage->loadMultiple($ids);
        $storage->delete($entities);
    }
    /**
     * Removes sample Runsheet scheduled items.
     *
     * @AfterScenario
     */
    public function cleanupRunsheetScheduledItems()
    {
        $storage = \Drupal::entityTypeManager()->getStorage('schedule_card');
        $ids = $storage->getQuery()->condition('runsheet.target_id', 'bdd_', 'STARTS_WITH')->execute();
        $entities = $storage->loadMultiple($ids);
        $storage->delete($entities);
    }
}