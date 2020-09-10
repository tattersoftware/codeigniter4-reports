<?php

use CodeIgniter\Config\Config;
use CodeIgniter\Test\Filters\CITestStreamFilter;
use Tests\Support\ReportsTestCase;

/**
 * @see https://github.com/codeigniter4/CodeIgniter4/blob/develop/tests/system/Commands/HelpCommandTest.php
 */
class CommandTest extends ReportsTestCase
{
	private $streamFilter;

	protected function setUp(): void
	{
		parent::setUp();

		CITestStreamFilter::$buffer = '';
		$this->streamFilter         = stream_filter_append(STDOUT, 'CITestStreamFilter');
		$this->streamFilter         = stream_filter_append(STDERR, 'CITestStreamFilter');
	}

	protected function tearDown(): void
	{
		stream_filter_remove($this->streamFilter);
	}

	protected function getBuffer()
	{
		return CITestStreamFilter::$buffer;
	}

	//--------------------------------------------------------------------

	public function testGenerateRunsReports()
	{
		command('reports:generate');

		$this->assertStringContainsString('Checking Money Report for new content', $this->getBuffer());
		$this->assertStringContainsString('New report saved: ' . date('Y-m-d'), $this->getBuffer());
	}
}
