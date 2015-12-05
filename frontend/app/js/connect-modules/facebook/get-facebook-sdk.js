"use strict";

module.exports = function ($document) {
	return function () {
		var scriptId = "facebook-jssdk";

		if (!$document.find("#" + scriptId).length) {
			var js = $document[0].createElement("script");

			js.id = scriptId;
			js.async = true;
			js.src = "//connect.facebook.net/en_US/all.js";

			$document.find("head").append(js);
		}
	};
};
