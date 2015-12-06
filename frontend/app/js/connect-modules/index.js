"use strict";

var angular = require("angular");

module.exports = angular.module("RefPhoneAuth.connectModules", [
	require("./facebook").name,
	require("./linkedin").name
]);
