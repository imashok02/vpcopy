'use strict';

exports.up = function(db, callback) {
  db.runSql("UPDATE `core_modules` SET `is_show_on_menu` = '0' WHERE `core_modules`.`module_id` = 16", function () {
  	db.runSql("'INSERT INTO core_modules' +  \
  		'(module_id, module_name, module_desc, module_lang_key, module_icon, ordering, is_show_on_menu, group_id) ' + \
  		' VALUES ('49', 'thumbnail_generators', 'Thumbnail Generator', 'thumbnail_generators_lng', '', '15', '1', '6')'", function () {
  			db.runSql("'INSERT INTO core_modules '+ \
  				' (module_id, module_name, module_desc, module_lang_key, module_icon, ordering, is_show_on_menu, group_id) '+ \
  				' VALUES ('50', 'image_lists', 'Image Lists', 'image_lists_lng', '', '10', '1', '6')'", function () {
  					db.runSql("ALTER TABLE `core_backend_config` ADD `landscape_thumb2x_width` INT(11) NOT NULL AFTER `ios_appstore_id`, ADD `potrait_thumb2x_height` INT(11) NOT NULL AFTER `landscape_thumb2x_width`, ADD `square_thumb2x_height` INT(11) NOT NULL AFTER `potrait_thumb2x_height`, ADD `landscape_thumb3x_width` INT(11) NOT NULL AFTER `square_thumb2x_height`, ADD `potrait_thumb3x_height` INT(11) NOT NULL AFTER `landscape_thumb3x_width`, ADD `square_thumb3x_height` INT(11) NOT NULL AFTER `potrait_thumb3x_height`", callback)
  				})
  		})
  });
};

exports.down = function(db, callback) {
  db.runSql("ALTER TABLE bs_user_bought DROP PRIMARY KEY", callback);
};

