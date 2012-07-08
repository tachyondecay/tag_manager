<?php

	class TagManager {

		public static function delete($field_id, $tags) {
			foreach($tags as $tag) {
				Symphony::Database()->delete(sprintf("tbl_entries_data_%d", $field_id), "`handle` = '" . Symphony::Database()->cleanValue($tag) . "'");
			}
		}

		public static function merge($field_id, $tags) {
			sort($tags);
			$primary_handle = Symphony::Database()->cleanValue($tags[0]);

			$primary_value = Symphony::Database()->fetchVar('value', 0, sprintf("SELECT `value` FROM tbl_entries_data_%d WHERE `handle` = '%s' LIMIT 1", $field_id, $primary_handle));
			foreach($tags as $tag) {
				Symphony::Database()->update(
					array('handle' => $primary_handle, 'value' => $primary_value),
					sprintf('tbl_entries_data_%d', $field_id),
					"`handle` = '" . Symphony::Database()->cleanValue($tag) . "'"
				);
			}

			// Clean up duplicate tags on an entry caused by the merge
			$duplicates = Symphony::Database()->fetch(sprintf(
					"SELECT id, entry_id, value FROM tbl_entries_data_%d a WHERE value = '%s' GROUP BY entry_id HAVING count(value) > 1", 
					$field_id, 
					$primary_value
				));
			
			foreach($duplicates as $dup) {
				Symphony::Database()->delete('tbl_entries_data_' . $field_id, '`id` = ' . $dup['id']);
			}
		}

		public static function update($field_id, $old_handle, $new_value) {
			$new_handle = Lang::createHandle($new_value);

			return Symphony::Database()->update(
				array('handle' => $new_handle, 'value' => $new_value),
				sprintf('tbl_entries_data_%d', $field_id),
				"`handle` = '" . Symphony::Database()->cleanValue($old_handle) . "'"
			);
		}
	}