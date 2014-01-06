<?php

use Phinx\Migration\AbstractMigration;

class ArticleSearchable extends AbstractMigration
{
    /**
     * Up Method.
	 */
    public function up()
    {
		$table = $this->table('article');
		$table->addColumn('searchable', 'boolean', array(
			'comment' => 'Should web crawlers index this?',
			'null' => false,
			'after' => 'hidden',
			'default' => 1,
		))
		->addIndex(array('searchable'))
		->save();

		$this->execute('UPDATE `article` SET searchable = 0 WHERE `id` IN (1378, 1450)');
    }

    /**
     * Down Method.
	 */
    public function down()
    {
		$this->execute('ALTER TABLE article DROP searchable');
    }
}
