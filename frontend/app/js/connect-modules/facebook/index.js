"use strict";

var angular = require("angular");

module.exports = angular.module("RefPhoneAuth.fbModule", [])
	.factory("getFacebookSDK", require("./get-facebook-sdk.js"))
	.controller("facebookCtrl", require("./controller.js"))
	.directive("facebookAuth", require("./directive.js"));
