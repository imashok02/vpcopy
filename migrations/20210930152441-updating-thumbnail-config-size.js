'use strict';

exports.up = function(db, callback) {
 db.runSql("UPDATE `core_backend_config` SET `landscape_thumb2x_width` = '200', `potrait_thumb2x_height` = '200', `square_thumb2x_height` = '200', `landscape_thumb3x_width` = '350',`potrait_thumb3x_height` = '350', `square_thumb3x_height` = '350' WHERE `core_backend_config`.`id` = 'be1'", callback)
};

exports.down = function(db, callback) {
  callback();
};
