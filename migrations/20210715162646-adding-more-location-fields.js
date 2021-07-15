'use strict';

exports.up = function(db, callback) {
  db.runSql('ALTER TABLE bs_item_locations ' +
  	'add COLUMN `addressLine` varchar(255), ' + 
  	'add COLUMN `countryName` varchar(255),' +
  	'add COLUMN `countryCode` varchar(255), ' +
  	'add COLUMN `featureName` varchar(255), ' +
  	'add COLUMN `postalCode` varchar(255), ' +
  	'add COLUMN `city` varchar(255), ' +
  	'add COLUMN `subLocality` varchar(255), ' +
  	'add COLUMN `adminArea` varchar(255),' +
  	'add COLUMN `subAdminArea` varchar(255), ' +
  	'add COLUMN `thoroughfare` varchar(255),' +
  	'add COLUMN `subThoroughfare` varchar(255);', callback);
};

exports.down = function(db, callback) {
  db.runSql('ALTER TABLE bs_item_locations ' +
  	'DROP `addressLine`, ' + 
  	'DROP `countryName`,' +
  	'DROP `countryCode`, ' +
  	'DROP `featureName`, ' +
  	'DROP `postalCode`, ' +
  	'DROP `city`, ' +
  	'DROP `subLocality`, ' +
  	'DROP `adminArea`,' +
  	'DROP `subAdminArea`, ' +
  	'DROP `thoroughfare`,' +
  	'DROP `subThoroughfare`;', callback);
};
