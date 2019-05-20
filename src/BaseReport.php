<?php namespace Tatter\Reports;

use CodeIgniter\Database\ConnectionInterface;

class BaseReport
{
	// the table for the report to use
	protected $table;
	
	// an instance of the Builder class, prepped for $table
	protected $builder;
	
	// any messages that might come up while running reports
	protected $messages = [];
	
	public function __construct(ConnectionInterface &$db = null)
	{
		if (empty($this->table))
			throw new \RuntimeException('You must set the report table property!');

		$db = db_connect();
		$this->builder = $db->table($this->table);
	}
	
	// fetches and organizes report results based on supplied parameters
	public function get(array $criteria = null, array $groups = null, string $order = null): array
	{
		// start the query
		$query = $this->builder;
		
		// check for criteria
		if (! empty($criteria))
			$query->where($criteria);
			
		// check for an order request, e.g. 'title DESC, name ASC'
		if (! empty($order))
			$query->orderBy($order);
		
		// fetch the results
		$rows = $query->get()->getResultArray();
		
		// if no grouping requests just return raw arrays
		if (empty($groups))
			return $rows;
		
		// get the field to be the value
		$valkey = array_pop($groups);
		
		// group by requested fields - last field becomes key, use ID for "generic"
		// https://stackoverflow.com/questions/3387472/string-to-variable-depth-multidimensional-array
		$results = [];
		foreach ($rows as $row):
			// (re)set pointer to top level
			$ptr = &$results;
			
			// move the pointer down each level
			foreach ($groups as $group):
				// get the key value from the row
				$key = $row[$group];
				
				// create any missing sub-arrays
				if (! isset($ptr[$key]))
					$ptr[$key] = [];
				
				// move the pointer deeper
				$ptr = &$ptr[$key];
			endforeach;
			
			// pointer is at the bottom:
			
			// if it is a new key then set a flat value
			if (empty($ptr))
				$ptr = $row[$valkey];
				
			// if it is already an array of values, add to it
			elseif (is_array($ptr))
				$ptr[] = $row[$valkey];
			
			// if it was a flat value, turn it into an array and add the new value
			else
				$ptr = [$ptr, $row[$valkey]];

		endforeach;
		
		return $results;
	}
	
	public function getMessages(): array
	{
		return $this->messages;
	}
}
