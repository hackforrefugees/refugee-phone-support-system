"use strict";

/* @ngInject */
module.exports = function ($resource, baseUrl, apiUrl) {
	return $resource(apiUrl + "/users", undefined, {
		register: {
			url: baseUrl + "/register",
			method: "POST"
		},
		login: {
			url: baseUrl + "/login",
			method: "POST"
		},
	});
};
