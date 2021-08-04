'use strict';

exports.up = function(db, callback) {
  db.runSql("update bs_language_string set value = 'Vegpuf Admin Panel' where value = 'Flutter Buy & Sell Admin Panel'", callback);
};

exports.down = function(db, callback) {
  db.runSql("update bs_language_string set value = 'Flutter Buy & Sell Admin Panel' where value = 'Vegpuf Admin Panel'", callback);
};
