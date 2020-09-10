<?php namespace Tatter\Reports;

use CodeIgniter\Database\ConnectionInterface;
use Tatter\Handlers\BaseHandler;

abstract class BaseReport extends BaseHandler
{
	/**
	 * Attributes for Tatter\Handlers
	 *
	 * @var array<string, string>  Must include keys: name, table
	 */
	public $attributes;

	/**
	 * An instance of the Builder class, prepped for $table.
	 *
	 * @var \CodeIgniter\Database\BaseBuilder
	 */
	protected $builder;

	/**
	 * Any messages that might come up while running reports
	 *
	 * @var array
	 */
	protected $messages = [];

	/**
	 * Tests all expected report criteria. Calls run() and saves results for any missing content.
	 */
	abstract public function generate();

	/**
	 * Calculates content for a specific missing set of criteria.
	 *
	 * @param mixed ...$params
	 *
	 * @return mixed
	 */
	abstract public function run(...$params);

	//--------------------------------------------------------------------

	/**
	 * Initializes the database.
	 *
	 * @param ConnectionInterface|null $db
	 */
	public function __construct(ConnectionInterface &$db = null)
	{
		if (empty($this->attributes['table']))
		{
			throw new \RuntimeException('You must set the report $table property!');
		}

		$db = $db ?? db_connect();

		$this->builder = $db->table($this->attributes['table']);
	}

	/**
	 * Fetches and organizes report results based on supplied parameters.
	 *
	 * @param array<string, mixed>|null $criteria  Criteria to pass to the Builder
	 * @param array<string>|null $groups           Fields to group by
	 * @param string|null $order                   Order for results
	 *
	 * @return array
	 */
	public function get(array $criteria = null, array $groups = null, string $order = null): array
	{
		// Start the query
		$query = $this->builder;
		
		// Check for criteria
		if (! empty($criteria))
		{
			$query->where($criteria);
		}
			
		// Check for an order request, e.g. 'title DESC, name ASC'
		if (! empty($order))
		{
			$query->orderBy($order);
		}
		
		// Fetch the results
		$rows = $query->get()->getResultArray();
		
		// If no grouping was requested then return raw arrays
		if (empty($groups))
		{
			return $rows;
		}
		
		// Get the field to be the value
		$valkey = array_pop($groups);
		
		// Group by requested fields - last field becomes key, use ID for "generic"
		// https://stackoverflow.com/questions/3387472/string-to-variable-depth-multidimensional-array
		$results = [];
		foreach ($rows as $row)
		{
			// (Re)set pointer to top level
			$ptr = &$results;

			// Move the pointer down each level
			foreach ($groups as $group)
			{
				// Get the key value from the row
				$key = $row[$group];

				// Create any missing sub-arrays
				if (! isset($ptr[$key]))
				{
					$ptr[$key] = [];
				}

				// Move the pointer deeper
				$ptr = &$ptr[$key];
			}
			
			/* Pointer is now at the bottom */

			// If it is a new key then set a flat value
			if (empty($ptr))
			{
				$ptr = $row[$valkey];
			}

			// If it is already an array of values, add to it
			elseif (is_array($ptr))
			{
				$ptr[] = $row[$valkey];
			}

			// If it was a flat value, turn it into an array and add the new value
			else // @phpstan-ignore-line
			{
				$ptr = [$ptr, $row[$valkey]];
			}
		}
		
		return $results;
	}

	/**
	 * Returns any messages.
	 *
	 * @return array
	 */
	public function getMessages(): array
	{
		return $this->messages;
	}
}
