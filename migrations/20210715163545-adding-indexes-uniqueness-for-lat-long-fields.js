'use strict';

exports.up = function(db, callback) {
  db.runSql('ALTER TABLE `bs_item_locations` ADD UNIQUE(`lat`);', function () {
    db.runSql('ALTER TABLE `bs_item_locations` ADD UNIQUE(`lng`);', function () {
        db.runSql('ALTER TABLE `bs_item_locations` ADD INDEX(`name`);', callback)
            });
    });
};

exports.down = function(db, callback) {
  db.runSql('ALTER TABLE bs_item_locations DROP INDEX name;', function () {
    db.runSql('ALTER TABLE bs_item_locations DROP INDEX lng;', function () {
        db.runSql('ALTER TABLE bs_item_locations DROP INDEX lat;', callback);
    });
    });
};
