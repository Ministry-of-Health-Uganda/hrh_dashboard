<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Format internal_participants from object keyed by id to a list of participant objects.
 *
 * Input:  { "6": { "participant_end": "...", "participant_days": "...", ... }, "7": { ... }, ... }
 * Output: [ { "staff_id": 6, "name": "...", "participant_name": "...", "participant_start", "participant_end", "participant_days", "international_travel" }, ... ]
 *
 * @param array|object $internal_participants Object keyed by staff id (string or int)
 * @param callable|null $name_resolver Optional. Callable(staff_id) returning name for the participant; if null, name/participant_name are null
 * @return array List of participant objects
 */
if (!function_exists('format_internal_participants_list')) {
	function format_internal_participants_list($internal_participants, $name_resolver = null) {
		if (empty($internal_participants)) {
			return array();
		}
		$list = array();
		foreach ((array) $internal_participants as $id => $row) {
			$row = (array) $row;
			$staff_id = is_numeric($id) ? (int) $id : $id;
			$name = null;
			if (is_callable($name_resolver)) {
				$name = $name_resolver($staff_id);
			}
			$list[] = array(
				'staff_id'           => $staff_id,
				'name'               => $name,
				'participant_name'   => $name,
				'participant_start'  => isset($row['participant_start']) ? $row['participant_start'] : null,
				'participant_end'    => isset($row['participant_end']) ? $row['participant_end'] : null,
				'participant_days'   => isset($row['participant_days']) ? $row['participant_days'] : null,
				'international_travel'=> isset($row['international_travel']) ? (int) $row['international_travel'] : 0,
			);
		}
		return $list;
	}
}
