"use strict";

var angular = require("angular");



var app = angular.module("RefPhoneAuth", [
	require("angular-animate"),
	require("angular-aria"),
	require("angular-messages"),
	require("angular-ui-router"),
	require("angular-resource"),

	// templates.js is a generated file that contains inlined versions of view partials
	require("./templates.js"),

	require("./resources").name,
	require("./users").name,
	require("./connect-modules").name
]);

app.controller("MainCtrl", require("./main-ctrl.js"));
app.controller("RegisterCtrl", require("./register-ctrl.js"));
app.controller("LoginCtrl", require("./login-ctrl.js"));

app.constant("baseUrl", "//172.30.159.158:9000");
app.constant("apiUrl", "//172.30.159.158:9000/v1");

app.config(function ($stateProvider, $locationProvider, $urlRouterProvider, $httpProvider) {
	$locationProvider.html5Mode(true);
	$urlRouterProvider.otherwise("/");

	var queryModel = function (model, params) {
		return [model, function (model) {
			return model.query(params).$promise;
		}];
	};

	$stateProvider
		.state("register", {
			controller: "RegisterCtrl",
			controllerAs: "ctrl",
			templateUrl: "/register.html",
			url: "/register"
		})
		.state("login", {
			controller: "LoginCtrl",
			controllerAs: "ctrl",
			templateUrl: "/login.html",
			params: {
				toState: "index",
				toParams: {}
			},
			url: "/login"
		})

		.state("index", {
			controller: "MainCtrl",
			controllerAs: "ctrl",
			templateUrl: "/main.html",
			url: "/"
		})

		.state("order", {
			abstract: true,
			controller: "OrderCtrl",
			redirectTo: "order.list",
			template: "<ui-view></ui-view>",
			url: "/orders"
		})
		.state("order.single", {
			templateUrl: "/order-single.html",
			url: "/:id"
		})
		.state("order.list", {
			templateUrl: "/order-list.html",
			url: "/"
		})

		.state("order.create", {
			controller: "OrderFormCtrl",
			templateUrl: "/order-form.html",
			url: "/order"
		})

		.state("user", {
			abstract: true,
			controller: "UserCtrl",
			controllerAs: "ctrl",
			redirectTo: "user.list",
			template: "<div ui-view='body'></div>",
			url: "/users"
		})
		.state("user.single", {
			views: {
				body: { templateUrl: "/user-single.html" }
			}
		})
		.state("user.single.me", {
			views: {
				"header@": {
					controller: function ($rootScope, $scope) {
						$scope.user = $rootScope.currentUser;
					},
					templateUrl: "partials/user-single-header.html"
				},
			},
			url: "/me"
		})
		.state("user.single.other", {
			views: {
				"header@": {
					controller: function ($scope) {
						$scope.user = {};
					},
					templateUrl: "partials/user-single-header.html"
				},
			},
			url: "/:id"
		})
		.state("user.list", {
			templateUrl: "/user-list.html",
			url: "/"
		});
});


app.run(function ($rootScope, $state, $timeout) {
	$rootScope.$state = $state;
	var stateChangeRetries = 0;

	$rootScope.$on("$stateChangeStart", function(event, toState, toParams) {
		$rootScope.loading = true;

		if (!$rootScope.currentUser && ["register", "login"].indexOf(toState.name) === -1) {
			event.preventDefault();
			$state.go("login", {toState, toParams});
			return false;
		}

		if (toState.redirectTo) {
			event.preventDefault();
			$state.go(toState.redirectTo, toParams);
		}
	});

	$rootScope.$on("$stateChangeSuccess", function (event, toState) {
		stateChangeRetries = 0;
		$rootScope.loading = false;
	});

	$rootScope.$on("$stateChangeError", function (event, toState, toParams, fromState, fromParams, error) {
		console.error(error);

		if (stateChangeRetries++ < 5) {
			$timeout(function () {
				$state.go(toState, toParams, {reload: true});
			}, stateChangeRetries * 400);
		} else {
			$rootScope.loading = false;
		}
	});
});
