<?php

	Class Extension_Tag_Manager extends Extension {

		public function about() {
			return array(
				'name' => 'Tag Manager',
				'version' => '0.1',
				'release-date' => '',
				'author' => array(
					'name' => 'Ben Babcock',
					'website' => 'http://tachyondecay.net/',
					'email' => 'ben@tachyondecay.net',
				),
				'description' => 'Manage the tags associated with a section.'
			);
		}

		public function getSubscribedDelegates() {
			return array(
				array(
					'page' => '/backend/',
					'delegate' => 'AdminPagePreGenerate',
					'callback' => '__appendAssets'
				),
			);
		}

		public function __appendAssets(&$context) {
			if(class_exists('Administration')
				&& Administration::instance() instanceof Administration
				&& Administration::instance()->Page instanceof HTMLPage
			) {
				$callback = Administration::instance()->getPageCallback();
				Administration::instance()->Page->addStylesheetToHead(URL . '/extensions/tag_manager/assets/tag_manager.css', 'screen', 200, false);
				Administration::instance()->Page->addScriptToHead(URL . '/extensions/tag_manager/assets/tag_manager.js', 200, false);
			}
		}
	}