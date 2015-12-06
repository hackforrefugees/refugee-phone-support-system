"use strict";

/* @ngInject */
module.exports = function ($rootScope, $timeout, $state, $stateParams, User) {
	this.form = {};

	this.login = details => {
		this.loggingIn = true;

		$timeout(() => {
			this.loggingIn = false;

			$rootScope.currentUser = {
				id: 1,
				name: "Fredrik Ekelund"
			};

			$state.go($stateParams.toState, $stateParams.stateParams);
		}, 1000);

		// User.authenticate(details, function (result) {
		// 	$rootScope.currentUser = result;
		// });
	};
};
