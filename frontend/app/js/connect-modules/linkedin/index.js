"use strict";

var angular = require("angular");

module.exports = angular.module("RefPhoneAuth.linkedin", [])
	.directive("linkedinAuth", require("./directive.js"));
