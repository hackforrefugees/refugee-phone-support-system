"use strict";

/* @ngInject */
module.exports = function ($rootScope, $timeout, $state, $stateParams, User) {
	this.form = {};

	this.login = details => {
		this.loggingIn = true;

		User.login(details, function (result) {
			$rootScope.currentUser = result.user;
			$rootScope.jwt = result.token;
			$state.go('user.list');
		}, function(error) {
			console.log('error logging in');
		});
	};
};
