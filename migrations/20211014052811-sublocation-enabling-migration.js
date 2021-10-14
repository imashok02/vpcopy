'use strict';

exports.up = function(db, callback) {
  db.runSql("INSERT INTO `core_modules` (`module_id`, `module_name`, `module_desc`, `module_lang_key`, `module_icon`, `ordering`, `is_show_on_menu`, `group_id`) "+
  	"VALUES (50, 'item_location_townships', 'Location Townships', 'location_townships', '', 15, 1, 1)", callback);
};

exports.down = function(db, callback) {
  db.runSql('DELETE FROM core_modules WHERE module_name = "item_location_townships";', callback);
};

