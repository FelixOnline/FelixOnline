<?php

use Phinx\Migration\AbstractMigration;

class ArticleSearchable extends AbstractMigration
{
    /**
     * Change Method.
	 */
    public function change()
    {
		$table = $this->table('article');
		$table->addColumn('searchable', 'boolean', array(
			'comment' => 'Should web crawlers index this?',
			'null' => false,
			'after' => 'hidden',
		))
		->addIndex(array('searchable'))
		->save();
    }
}
