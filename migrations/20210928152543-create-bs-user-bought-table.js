'use strict';

exports.up = function(db, callback) {
  db.runSql("CREATE TABLE `bs_user_bought` ( `id` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL , `item_id` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL , `buyer_user_id` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL , `seller_user_id` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL , `added_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ) ENGINE = InnoDB", function () {
  		db.runSql("ALTER TABLE `bs_user_bought` ADD PRIMARY KEY(`id`)", callback);
  	});
};

exports.down = function(db, callback) {
  db.runSql("ALTER TABLE bs_user_bought DROP PRIMARY KEY", function () {
  	db.runSql("DROP table bs_user_bought", callback);
  });
};
