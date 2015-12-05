/* globals FB */

"use strict";

/* @ngInject */
module.exports = function ($window, getFacebookSDK) {
	getFacebookSDK();

	var statusChangeCallback = response => {
		console.log("statusChangeCallback", response);

		this.connStatus = response.status;

		if (this.connStatus === "connected") {
			FB.api("/me", function (response) {
				console.log(response);
			});
		}
	};

	this.login = () => {
		if (this.fbInitted) return false;

		FB.login(statusChangeCallback, {scope: "public_profile,email"});
	};

	$window.fbAsyncInit = function () {
		this.fbInitted = true;

		FB.init({
			appId: location.hostname === "localhost" ? "1648848668702713" : "1648848162036097",
			status: true,
			cookie: true,
			xfbml: true
		});

		FB.getLoginStatus(statusChangeCallback);
	};
};
