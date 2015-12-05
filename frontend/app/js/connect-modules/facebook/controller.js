/* globals FB */

"use strict";

/* @ngInject */
module.exports = function ($scope, loadFacebookSDK) {
	loadFacebookSDK(() => {
		this.fbInitted = true;

		FB.init({
			appId: location.hostname === "localhost" ? "1648848668702713" : "1648848162036097",
			status: true,
			cookie: true,
			xfbml: true
		});

		FB.getLoginStatus(statusChangeCallback);
	});

	var statusChangeCallback = response => {
		console.log("statusChangeCallback", response);

		this.connStatus = response.status;
		$scope.$apply();

		if (this.connStatus === "connected") {
			FB.api("/me", response => {
				this.user = response;
			});
		}
	};

	this.login = () => {
		FB.login(statusChangeCallback, {scope: "public_profile,email,user_friends,user_likes,user_posts"});
	};
};
