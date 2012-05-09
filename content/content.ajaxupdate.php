<?php

	require_once(TOOLKIT . '/class.ajaxpage.php');
	require_once(EXTENSIONS . '/tag_manager/lib/class.tagmanager.php');
	
	class contentExtensionTag_ManagerAjaxUpdate extends AjaxPage {

		public function view() {
			$status = new XMLElement('status', 'false');
			$result = TagManager::update($_POST['fieldId'], $_POST['oldHandle'], $_POST['newValue']);
			if($result) {
				$status->setValue('true');
			}
			$this->_Result->appendChild($status);
		}

	}