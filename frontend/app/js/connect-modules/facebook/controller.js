/* globals FB */

"use strict";

/* @ngInject */
module.exports = function ($rootScope, $scope, loadFacebookSDK) {
	this.loggingIn = true;
	$rootScope.user = this.user = {};

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

	this.getFacebookProfileImage = () => {
		return `https://graph.facebook.com/${this.user.id}/picture?type=large&return_ssl_resources=1`;
	};

	var asyncCallback = function (callback) {
		return function () { $scope.$apply(callback.apply(this, arguments)) };
	};

	var statusChangeCallback = response => {
		console.log("statusChangeCallback", response);

		this.loggingIn = false;
		this.connStatus = response.status;
		$scope.$apply();

		if (this.connStatus === "connected") {
			FB.api("/me", asyncCallback(apiResponse => {
				Object.assign(this.user, apiResponse);
			}));

			FB.api("/me/friends", asyncCallback(apiResponse => {
				Object.assign(this.user, { friends: apiResponse });
			}));

			FB.api("/me/likes", asyncCallback(apiResponse => {
				Object.assign(this.user, { likes: apiResponse });
			}));

			FB.api("/me/posts", asyncCallback(apiResponse => {
				Object.assign(this.user, { posts: apiResponse });
			}));
		}
	};

	this.login = () => {
		FB.login(statusChangeCallback, {scope: "public_profile,email,user_friends,user_likes,user_posts"});
	};
};
