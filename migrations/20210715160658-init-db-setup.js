'use strict';

var fs = require('fs'),
  path = require('path'),
  executeQueries = function (db, callback, filename) {
    var filePath = path.join(__dirname + '/DB/' + filename);

    console.log("filePath ==> ",filePath);

    fs.readFile(filePath, {encoding: 'utf-8'}, function (err, data) {
      if (err) {
        return console.log(err);
      }
      db.runSql(data, function (err) {
        if (err) {
          return console.log(err);
        }
        callback();
      });
    });
  };

  exports.up = function (db, callback) {
    executeQueries (db, callback, 'init-db.sql');
  };

  exports.down = function (db, callback) {
    executeQueries (db, callback, 'drop-init-tables.sql');
  };