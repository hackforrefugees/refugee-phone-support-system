"use strict";

/* @ngInject */
module.exports = function ($resource, apiUrl) {
	return $resource(apiUrl + "/users");
};
