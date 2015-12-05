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
	require("./users").name
]);

app.controller("MainCtrl", require("./main-ctrl.js"));

app.constant("apiUrl", "//172.20.10.5");

app.config(function ($stateProvider, $locationProvider, $urlRouterProvider, $httpProvider) {
	$locationProvider.html5Mode(true);
	$urlRouterProvider.otherwise("/");

	var queryModel = function (model, params) {
		return [model, function (model) {
			return model.query(params).$promise;
		}];
	};

	$httpProvider.interceptors.push(function ($q) {
		return {
			request: function(config) {
				console.log("Intercepting", config.url);
				return config;
			}
		};
	});

	$stateProvider
		.state("index", {
			controller: "MainCtrl",
			controllerAs: "main",
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

		.state("orderForm", {
			controller: "OrderFormCtrl",
			templateUrl: "/order-form.html",
			url: "/order"
		})

		.state("user", {
			abstract: true,
			controller: "UserCtrl",
			controllerAs: "ctrl",
			redirectTo: "user.list",
			template: "<ui-view></ui-view>",
			url: "/users"
		})
		.state("user.single", {
			templateUrl: "/user-single.html",
			url: "/:id"
		})
		.state("user.list", {
			templateUrl: "/user-list.html",
			url: "/"
		});
});


app.run(function ($rootScope, $state, $timeout) {
	$rootScope.$on("$stateChangeStart", function(event, toState, toParams) {
		if (toState.redirectTo) {
			event.preventDefault();
			$state.go(toState.redirectTo, toParams);
		}
	});



	$rootScope.getStateTitle = function (state) {
		if (state.title) {
			return state.title;
		} else {
			var parentState = $state.get("^", state.name);
			return parentState && parentState.name ? parentState.title || $rootScope.getStateTitle(parentState) : "";
		}
	};

	var stateChangeRetries = 0;

	$rootScope.$on("$stateChangeStart", function (event, toState, toParams) {
		$rootScope.loading = true;
	});

	$rootScope.$on("$stateChangeSuccess", function (event, toState) {
		stateChangeRetries = 0;
		$rootScope.loading = false;
		$rootScope.title = $rootScope.getStateTitle($state.current);
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
