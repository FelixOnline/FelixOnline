<?php

use Phinx\Migration\AbstractMigration;

class CommentEmails extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     *
     * Uncomment this method if you would like to use it.
     *
    public function change()
    {
    }
    */
    
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table('comment_ext');
        $table->addColumn('email', 'string', array(
            'null' => false,
            'after' => 'IP',
            'length' => 300,
        ))
        ->addColumn('useragent', 'string', array(
            'null' => false,
            'after' => 'IP',
            'length' => 300,
        ))
        ->addColumn('referer', 'string', array(
            'null' => false,
            'after' => 'IP',
            'length' => 300,
        ))
        ->save();

    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->execute('ALTER TABLE comment_ext DROP email');
        $this->execute('ALTER TABLE comment_ext DROP useragent');
        $this->execute('ALTER TABLE comment_ext DROP referer');
    }
}