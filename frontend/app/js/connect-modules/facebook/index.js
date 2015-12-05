"use strict";

var angular = require("angular");

module.exports = angular.module("RefPhoneAuth.facebook", [])
	.factory("loadFacebookSDK", require("./load-facebook-sdk.js"))
	.controller("facebookCtrl", require("./controller.js"))
	.directive("facebookAuth", require("./directive.js"));
