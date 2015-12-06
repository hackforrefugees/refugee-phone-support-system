"use strict";

var angular = require("angular");

module.exports = angular.module("RefPhoneAuth.resources", [])
	.factory("User", require("./user.js"))
	.factory("Order", require("./order.js"));
