<?php
	require_once(TOOLKIT . '/class.administrationpage.php');
	require_once(TOOLKIT . '/class.sectionmanager.php');
	require_once(EXTENSIONS . '/tag_manager/lib/class.tagmanager.php');

	class contentExtensionTag_ManagerIndex extends AdministrationPage {
		public $_context;
		protected $_field = null;
		protected $_fieldList = null;
		protected $_section = null;
		protected $_url = null;

		public function view() {
			$this->setPageType('table');
			$this->setTitle(__('Tag Manager'));
			$this->appendSubheading(__('Tag Manager') . ' &raquo; ' . __('Section List'));

			$sections = SectionManager::fetch();

			$table_rows = array();
			foreach($sections as $section) {
				// Check if this section has taglist fields associated with it
				$tag_fields = FieldManager::fetch(null, $section->get('id'), 'ASC', 'sortorder', 'taglist');
				if(empty($tag_fields)) {
					continue;
				}
			
				$section_info = Widget::TableData(Widget::Anchor($section->get('name'), SYMPHONY_URL . '/extension/tag_manager/edit/' . $section->get('handle')));
				$table_rows[] = Widget::TableRow(array(
							$section_info,
							Widget::TableData(count($tag_fields)),
						));
			}

			$table_head = Widget::TableHead(array(array(__('Sections'), 'col'), array(__('Taglist Fields'), 'col')));
			$table_body = Widget::TableBody($table_rows);

			$this->Form->appendChild(Widget::Table($table_head, null, $table_body));
		}
	}