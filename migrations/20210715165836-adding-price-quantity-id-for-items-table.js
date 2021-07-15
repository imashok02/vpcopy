'use strict';

exports.up = function(db, callback) {
  db.runSql('ALTER TABLE bs_items add COLUMN price_qty_id varchar(255);', callback);
};

exports.down = function(db, callback) {
  db.runSql('ALTER TABLE bs_items DROP COLUMN price_qty_id;', callback);
};
