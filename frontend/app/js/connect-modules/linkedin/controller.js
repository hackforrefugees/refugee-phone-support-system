/* globals FB */

"use strict";

/* @ngInject */
module.exports = function ($rootScope, $scope, $http) {
	this.loggingIn = true;
	$rootScope.user = this.user = {};

	var asyncCallback = function (callback) {
		return function () { $scope.$apply(callback.apply(this, arguments)) };
	};

	var loginSuccessCallback = response => {
		console.log(response);
	};

	var errorCallback = response => {
		console.log(response);
	};

	this.login = () => {
    $http.get('https://www.linkedin.com/uas/oauth2/authorization?response_type=code&client_id=7711fiuwtab7nz&redirect_uri=http://localhost:8000/linkedinLogin')
    .then(loginSuccessCallback, errorCallback);
		
	};
};
