"use strict";

/* @ngInject */
module.exports = function ($state, $stateParams, User) {
	this.form = {};

	this.register = details => {
		this.registering = true;

		User.register(details, result => {
			console.log(result);

			this.registering = false;
			$state.go("login");
		});
	};
};
