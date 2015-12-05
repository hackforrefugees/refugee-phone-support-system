"use strict";

var angular = require("angular");

module.exports = angular.module("RefPhoneAuth.linkedin", [])
	.controller("linkedinCtrl", require("./controller.js"))
	.directive("linkedinAuth", require("./directive.js"));

