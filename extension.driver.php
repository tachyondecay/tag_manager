<?php

	Class Extension_Tag_Manager extends Extension {

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

		public function fetchNavigation() {
			return array(
				array(
					'name' => 'Tag Manager',
					'location' => __('Blueprints'),
					'link' => '',
				)
			);
		}
	}