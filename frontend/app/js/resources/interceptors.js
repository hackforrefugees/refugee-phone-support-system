"use strict";

/* @ngInject */
module.exports = function ($state) {
	return {
		responseError: function (rejection) {
			if (rejection.status > 400 && rejection.status !== 404) {
				$state.go("login", {
					toState: $state.current,
					toParams: $state.params
				});
			}

			return rejection;
		}
	};
};
