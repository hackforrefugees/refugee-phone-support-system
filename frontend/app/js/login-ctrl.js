"use strict";

/* @ngInject */
module.exports = function ($rootScope, $timeout, $state, $stateParams, User) {
	this.form = {};

	this.login = details => {
		this.loggingIn = true;

		User.authenticate(details, function (result) {
			$rootScope.currentUser = result;
		});
	};
};
