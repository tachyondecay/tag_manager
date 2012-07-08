<?php
	require_once(TOOLKIT . '/class.administrationpage.php');
	require_once(TOOLKIT . '/class.sectionmanager.php');
	require_once(EXTENSIONS . '/tag_manager/lib/class.tagmanager.php');

	class contentExtensionTag_ManagerEdit extends AdministrationPage {
		public $_context;
		protected $_field = null;
		protected $_fieldList = null;
		protected $_section = null;
		protected $_subheading = null;
		protected $_url = null;

		public function build($context) {
			$this->_context = $context;
			if(empty($context[0])) { 
				Administration::instance()->errorPageNotFound();
			}
			$handle = $context[0];
			if(!$id = SectionManager::fetchIDFromHandle($handle)) {
				Administration::instance()->customError(__('Unknown Section'), __('The Section you are looking for, <code>%s</code>, could not be found.', array($handle)));
			}

			$this->_section = SectionManager::fetch($id);

			$this->_url = SYMPHONY_URL . '/extension/tag_manager/edit/' . $handle . '/';

			if(!empty($this->_context[1]) && is_numeric($this->_context[1])) {
				$this->_url .= $this->_context[1] . '/';

				$this->_field = FieldManager::fetch($this->_context[1]);
				if(empty($this->_field) || $this->_field->get('type') != 'taglist') {
					Administration::instance()->customError(__('No Tag Field Found'), __('No taglist field with the specified ID was found.'));
				}
			}

			parent::build($context);
		}

		public function action() {
			if(isset($_POST['action']['apply'])) {
				if($_POST['with-selected'] == 'delete') {
					$this->__actionDelete(array_keys($_POST['items']));
				}
				elseif($_POST['with-selected'] == 'merge') {
					$this->__actionMerge(array_keys($_POST['items']));
				}
			}

			if(isset($_POST['action']['save'])) {
				$interested_tag = key($_POST['action']['save']);
				$this->__actionUpdate($interested_tag, $_POST['edit'][$interested_tag]);
			}

		}

		protected function __actionDelete($tags) {
			TagManager::delete($this->_field->get('id'), $tags);
			redirect($this->_url . 'deleted/');
		}

		protected function __actionMerge($tags) {
			TagManager::merge($this->_field->get('id'),$tags);
			redirect($this->_url . 'merged/');
		}

		protected function __actionUpdate($old_handle, $new_value) {
			TagManager::update($this->_field->get('id'), $old_handle, $new_value);
			redirect($this->_url . 'updated/');
		}

		public function view() {
			$this->insertBreadcrumbs(array(Widget::Anchor(__('Tag Manager'), SYMPHONY_URL . '/extension/tag_manager/')));
			if(isset($this->_context[2])) {
				$message = __('Tags successfully ' . $this->_context[2] . '.');
				if(!empty($message)) { 
					$this->pageAlert($message, Alert::SUCCESS);
				}
			}

			if(!empty($this->_context[1]) && is_numeric($this->_context[1])) {
				$this->__viewEdit();
			}
			else{
				$this->__viewSection($this->_section->get('id'));
			}
		}

		protected function __viewEdit() {
			$this->setPageType('table');
			$this->setTitle(__('Tag Manager') . ' &ndash; ' . $this->_section->get('name') . ' &ndash; ' . __('Tag Field') . ': ' . $this->_field->get('label') . ' &ndash; ' . __('Symphony'));
			$this->insertBreadcrumbs(array(Widget::Anchor(__('Section') . ': ' . $this->_section->get('name'), SYMPHONY_URL . '/extension/tag_manager/edit/' . $this->_section->get('handle'))));
			$this->appendSubheading(__('Tag Field') . ': ' . $this->_field->get('label'));

			$tags = Symphony::Database()->fetch(sprintf("SELECT COUNT(id) AS freq, `value`, `handle` FROM tbl_entries_data_%d GROUP BY `value` ORDER BY %s %s", $this->_field->get('id'), 'value', 'ASC'));

			$table_rows = array();
			foreach($tags as $tag) {
				$tag_value = new XMLElement('div');
				$tag_value->addClass('tag-manager-edit');

				$tag_value->appendChild(Widget::Input('edit[' . $tag['handle'] . ']', $tag['value'], 'text', array('class' => 'tag-manager tag-manager-name')));
				$tag_value->appendChild(Widget::Input('action[save][' . $tag['handle'] . ']', 'Save', 'submit', array('class' => 'tag-manager', 'data-handle' => $tag['handle'])));

				$tag_value = Widget::TableData($tag_value);
				$tag_value->appendChild(Widget::Input('items[' . $tag['handle'] . ']', null, 'checkbox'));

				$table_rows[] = Widget::TableRow(array(
							$tag_value,
							Widget::TableData($tag['freq']),
						));
			}

			$table_head = Widget::TableHead(array(array(__('Tags'), 'col'), array(__('Frequency'), 'col')));
			$table_body = Widget::TableBody($table_rows);

			$this->Form->appendChild(Widget::Table($table_head, null, $table_body, 'selectable'));

			$options = array(
				array(null, false, 'With Selected...'),
				array('delete', false, 'Delete', 'confirm'),
			);

			if(count($tags) > 1) {
				$options[] = array('merge', false, 'Merge');
			}

			$actions = new XMLElement('div');
			$actions->setAttribute('class', 'actions');
			$actions->appendChild(Widget::Apply($options));
			
			$this->Form->appendChild($actions);
		}

		protected function __viewSection($id) {
			// Check if there are appropriate taglist fields
			$this->_fieldList = FieldManager::fetch(null, $id, 'ASC', 'sortorder', 'taglist', null);

			if(empty($this->_fieldList)) {
				Administration::instance()->customError(__('No Appropriate Tag Fields'), __('The Section you specified, <code>%s</code>, does not have any taglist fields.', array($handle)));
			}

			$this->setPageType('table');
			$this->setTitle(__('Tag Manager') . ' &ndash; ' . $this->_section->get('name') . ' &ndash; ' . __('Symphony'));
			$this->appendSubheading(__('Section') . ': ' . $this->_section->get('name'));

			$table_rows = array();
			foreach($this->_fieldList as $field) {
				$result = Symphony::Database()->fetchCol('COUNT(id)', sprintf("SELECT COUNT(id) FROM tbl_entries_data_%d", $field->get('id')));

				$table_rows[] = Widget::TableRow(array(
							Widget::TableData(Widget::Anchor($field->get('label'), $field->get('id') . '/'), 'field-taglist', 'field-' . $field->get('id')),
							Widget::TableData($result[0]),
						));
			}

			$table_head = Widget::TableHead(array(array(__('Fields'), 'col'), array(__('Count'), 'col')));
			$table_body = Widget::TableBody($table_rows);

			$this->Form->appendChild(Widget::Table($table_head, null, $table_body));
		}
	}