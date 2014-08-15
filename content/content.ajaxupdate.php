<?php

	require_once(TOOLKIT . '/class.xmlpage.php');
	require_once(EXTENSIONS . '/tag_manager/lib/class.tagmanager.php');
	
	class contentExtensionTag_ManagerAjaxUpdate extends XMLPage {

		public function view() {
			$status = new XMLElement('status', 'false');
			$result = TagManager::update($_POST['fieldId'], $_POST['oldHandle'], $_POST['newValue']);
			if($result) {
				$status->replaceValue('true');
			}
			$this->_Result->appendChild($status);
		}

	}