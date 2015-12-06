"use strict";

var angular = require("angular");

module.exports = angular.module("RefPhoneAuth.Users", [])
	.controller("UserCtrl", require("./controller.js"));
