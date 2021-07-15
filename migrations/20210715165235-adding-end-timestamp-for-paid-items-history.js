'use strict';

exports.up = function(db, callback) {
  db.runSql('ALTER TABLE bs_paid_items_history ADD end_timestamp DOUBLE NOT NULL AFTER start_timestamp;', callback);
};

exports.down = function(db, callback) {
  db.runSql('ALTER TABLE bs_paid_items_history DROP COLUMN end_timestamp;', callback);
};
