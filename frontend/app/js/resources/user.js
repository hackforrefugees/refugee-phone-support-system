"use strict";

/* @ngInject */
module.exports = function ($resource, apiUrl) {
	return $resource(apiUrl + "/users", undefined, {
		authenticate: {
			method: "POST"
		},
		register: {
			method: "POST"
		}
	});
};
