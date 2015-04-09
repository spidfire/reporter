<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1428517346.
 * Generated on 2015-04-08 20:22:26 
 */
class PropelMigration_1428517346
{
    public $comment = '';

    public function preUp($manager)
    {
        // add the pre-migration code here
    }

    public function postUp($manager)
    {
        // add the post-migration code here
    }

    public function preDown($manager)
    {
        // add the pre-migration code here
    }

    public function postDown($manager)
    {
        // add the post-migration code here
    }

    /**
     * Get the SQL statements for the Up migration
     *
     * @return array list of the SQL strings to execute for the Up migration
     *               the keys being the datasources
     */
    public function getUpSQL()
    {
        return array (
  'reporter' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

DROP INDEX `key` ON `metric`;

ALTER TABLE `metric`

  ADD `label` VARCHAR(64) NOT NULL AFTER `id`,

  DROP `key`;

CREATE INDEX `label` ON `metric` (`label`);

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

    /**
     * Get the SQL statements for the Down migration
     *
     * @return array list of the SQL strings to execute for the Down migration
     *               the keys being the datasources
     */
    public function getDownSQL()
    {
        return array (
  'reporter' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

DROP INDEX `label` ON `metric`;

ALTER TABLE `metric`

  ADD `key` VARCHAR(64) NOT NULL AFTER `id`,

  DROP `label`;

CREATE INDEX `key` ON `metric` (`key`);

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}