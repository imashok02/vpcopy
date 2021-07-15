'use strict';

exports.up = function(db, callback) {
  db.runSql('CREATE TABLE `bs_price_quantities` ( '+
  ' `id` varchar(255) NOT NULL, ' +
  ' `main_cat_id` varchar(255) NOT NULL,'+
  ' `cat_id` varchar(255) NOT NULL,'+
  ' `sub_cat_id` varchar(255) NOT NULL,'+
  ' `name` varchar(255) NOT NULL, '+
  ' `status` varchar(255) NOT NULL, '+
  ' `added_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ' +
  ') ENGINE=InnoDB DEFAULT CHARSET=utf8;', callback);
};

exports.down = function(db, callback) {
  db.runSql('DROP TABLE bs_price_quantities', callback);
};
