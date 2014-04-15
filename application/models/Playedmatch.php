<?php

class Application_Model_Playedmatch {
	
	public static function copyMatch($data) {
		$db = new Application_Model_DbTable_Playedmatch();
				
		$row = $db->createRow();
		
		$row->match_id = $data['id'];
		$row->opponent1 = $data['opponent1'];;
		$row->nativelang1 = $data['nativelang1'];
		$row->opponent2 = $data['opponent2'];
		$row->nativelang2 = $data['nativelang2'];
		$row->foreignlang = $data['foreignlang'];
		$row->result1 = $data['result1'];
		$row->result2 = $data['result2'];
		
		$row->active = $data['active'];

		if (!$row->save())
			$error ++;
		
		return $error ? false : true;
	}
}

?>