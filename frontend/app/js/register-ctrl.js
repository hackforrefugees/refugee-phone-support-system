"use strict";

/* @ngInject */
module.exports = function ($state, $stateParams, User) {
	this.form = {};

	this.register = details => {
		this.registering = true;

		var request = User.register(details);

		request.$promise.then(result => {
			console.log(result);
			$state.go("login");
		}).catch(error => {
			console.error(error);
		}).finally(() => {
			this.registering = false;
		})
	};
};
