"use strict";

/* @ngInject */
module.exports = function ($resource, apiUrl, resourceInterceptors) {
	return $resource(apiUrl + "/orders", {
		interceptor: resourceInterceptors
	});
};
