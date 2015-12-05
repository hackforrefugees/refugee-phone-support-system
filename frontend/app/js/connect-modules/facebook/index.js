"use strict";

var angular = require("angular");

module.exports = angular.module("RefPhoneAuth.fbModule", [])
	.controller("facebookCtrl", require("./controller.js"))
	.directive("facebookAuth", require("./directive.js"));
