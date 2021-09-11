Feature: basics
  In order to test my plugin
  As a plugin developer
  I need to have tests

  Background:
    Given I have the following config
      """
      <?xml version="1.0"?>
      <psalm totallyTyped="true">
        <projectFiles>
          <directory name="."/>
        </projectFiles>
        <plugins>
          <pluginClass class="Leighman\PsalmPluginTodoBy\Plugin" />
        </plugins>
      </psalm>
      """
  Scenario: run without errors
    Given I have the following code
      """
      <?php
      /**
       * @todo-by 3020 - remove this file
       */

       /**
        * @todo-by 3020-02 - replace the taps
        */
      class Tap {
        /**
         * @todo-by 3020-02-20 - and this
         * @return 1
         */
        public function something() {
          /** @todo-by 3020-02-20T13:00:00Z - do this thing also */
          $a = 1;

          return $a;
        }
      }
      """
    When I run Psalm
    Then I see no errors

  Scenario: run with errors
    Given I have the following code
      """
      <?php
      /**
      * @todo-by 2020 - remove this file
      */

      /**
      * @todo-by 2020-02 - replace the taps
      */
      class Tap {
        /**
        * @todo-by 2020-02-20 and this
        * @return 1
        */
        public function something() {
          /** @todo-by 2020-02-20T13:00:00Z - do this thing also */
          $a = 1;

          return $a;
        }
      }
      """
    When I run Psalm
    Then I see these errors
      | Type                  | Message                                                                             |
      | TodoByExceeded | Todo time limit to "replace the taps" has passed |
      | InvalidTodoBy | Failed to parse @todo-by docblock annotation |
      | TodoByExceeded | Todo time limit to "do this thing also" has passed |
    And I see no other errors

