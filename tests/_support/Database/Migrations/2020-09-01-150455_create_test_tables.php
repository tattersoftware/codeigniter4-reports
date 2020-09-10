<?php namespace Tests\Support\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTestTables extends Migration
{
	public function up()
	{
		// MoneyReport
		$this->forge->addField('id');
		$this->forge->addField([
			'day'        => ['type' => 'date'],
			'amount'     => ['type' => 'int'],
			'created_at' => [
				'type' => 'datetime',
				'null' => true,
			],
		]);
		$this->forge->createTable('finances');

		// WidgetReport
		$this->forge->addField('id');
		$this->forge->addField([
			'index'      => ['type' => 'date'],
			'total'      => ['type' => 'int'],
			'created_at' => [
				'type' => 'datetime',
				'null' => true,
			],
		]);
		$this->forge->createTable('widget_reports');
	}

	public function down()
	{
		$this->forge->dropTable('finances');
		$this->forge->dropTable('widget_reports');
	}
}
