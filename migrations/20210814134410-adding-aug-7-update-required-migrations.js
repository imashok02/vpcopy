'use strict';

exports.up = function(db, callback) {
  db.runSql('ALTER TABLE `bs_items` ADD `item_location_township_id` VARCHAR(255)' + 
  	'CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `item_location_id`;', function () {
  		db.runSql('CREATE TABLE `bs_item_location_townships` ' +
  			'( `id` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL , ' +
  			' `city_id` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL , '+ 
  			' `township_name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL , ' + 
  			' `ordering` INT(10) NOT NULL , `lat` FLOAT(10,6) NOT NULL , `lng` FLOAT(10,6) NOT NULL , ' +
  			' `status` TINYINT(1) NOT NULL , `added_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ) ' + 
  			' ENGINE = InnoDB;', function () {
  				db.runSql('ALTER TABLE `bs_item_location_townships` ADD PRIMARY KEY(`id`);', function () {
  					db.runSql('ALTER TABLE `bs_items_currency` ADD `is_default` TINYINT(1) NOT NULL AFTER `status`;', function() {
  						db.runSql('ALTER TABLE `core_images` ADD `ordering` INT(11) NOT NULL AFTER `updated_user_id`;', function () {
  							db.runSql('INSERT INTO `core_modules` (`module_id`, `module_name`, `module_desc`, ' +
  								 ' `module_lang_key`, `module_icon`, `ordering`, `is_show_on_menu`, `group_id`) ' + 
  								 " ' VALUES ('31', 'item_location_townships', 'Location Townships', 'location_townships', '', '15', '1', '1');", function () {
  								 	db.runSql("DELETE FROM `bs_order_by` WHERE `bs_order_by`.`id` = 'added_date_asc';", function () {
  								 		db.runSql("DELETE FROM `bs_order_by` WHERE `bs_order_by`.`id` = 'added_date_desc';", function () {
  								 			db.runSql("DELETE FROM `bs_order_by` WHERE `bs_order_by`.`id` = 'point_asc';", function () {
  								 				db.runSql("DELETE FROM `bs_order_by` WHERE `bs_order_by`.`id` = 'point_desc';", function () {
  								 					db.runSql("UPDATE `bs_order_by` SET `name` = 'Name Ascending' WHERE `bs_order_by`.`id` = 'name_asc';", function () {
  								 						db.runSql("UPDATE `bs_order_by` SET `name` = 'Name Descending' WHERE `bs_order_by`.`id` = 'name_desc';", function () {
  								 							db.runSql('ALTER TABLE `bs_chat_history` ADD `offer_status` TINYINT(1) NOT NULL AFTER `is_accept`;', function () {
  								 								db.runSql('ALTER TABLE `bs_app_settings` ADD `is_sub_location` TINYINT(1) NOT NULL AFTER `is_approval_enabled`;', callback)
  								 							})
  								 						})
  								 					})
  								 				})
  								 			})
  								 		})
  								 	})
  								 })
  						})
  					})
  				})
  			})
  	});
};

exports.down = function(db, callback) {
  db.runSql("ALTER TABLE `bs_items` DROP `item_location_township_id`", function () {
  	db.runSql('drop TABLE bs_item_location_townships', function () {
  		db.runSql('ALTER TABLE bs_item_location_townships DROP PRIMARY KEY;', function () {
  			db.runSql('ALTER TABLE `bs_items_currency` DROP `is_default`', function () {
  				db.runSql('ALTER TABLE `core_images` DROP `ordering`', function () {
  					db.runSql('DELETE FROM core_modules where module_id = 31', callback);
  				})
  			})
  		})
  	})
  });
};



















