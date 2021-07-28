'use strict';

exports.up = function(db, callback) {
  db.runSql("INSERT INTO `core_modules` (`module_id`, `module_name`, `module_desc`, `module_lang_key`, `module_icon`, `ordering`, `is_show_on_menu`, `group_id`) "+
  	"VALUES (49, 'pricequantity', 'Price Quantity', 'price_qty_module', '', 20, 1, 1)", callback);
};

exports.down = function(db, callback) {
  db.runSql('DELETE FROM core_modules WHERE module_name = "pricequantity";', callback);
};
