# Tag Manager

Provides a convenient interface for bulk editing tag list fields.

With Tag Manager, you can:
- Edit the value of a tag and have this change reflected across every entry in the section with that tag.
- Delete any tag from all entries in a section.
- Merge two or more tags.

- Version: 1.1
- Author: Ben Babcock <ben@tachyondecay.net>
- Updated: August 14, 2014
- GitHub Repository: https://github.com/tachyondecay/tag_manager

## Installation & Use

You can always install the latest version through git: `git clone git://github.com/tachyondecay/tag_manager.git`

- Make sure that the extension is in a folder named `tag_manager`. Upload this to your Symphony `extensions` folder.
- Enable the extension from the **Extensions** page in the Symphony backend.
- You can access Tag Manager in two ways:
  * From the Blueprints navigation group, select the Tag Manager link.
  * From the entries list of any section that has taglist fields. Note that at least one taglist field must be visible as a column in the table in order for Tag Manager to add a button to this section.
- From the list of taglist fields, select the field whose tags you want to manage.
- Click on a tag to edit its value. To merge or delete one or more tags, select the appropriate rows and use the "With Selected" menu to select the appropriate action.

## Changelog

### 1.1 (August 14, 2014)

- Fixes #5 to ensure actual compatibility with 2.4. No longer compatible with 2.3.

### 1.0.2 (August 13, 2014)

- Selectable table fix for 2.4 (Fixes #4)

### 1.0.1 (December 31, 2012)

- Added Russian translation, courtesy [alexbirukov](https://github.com/alexbirukov)

### 1.0 (July 8, 2012)

- Initial release.