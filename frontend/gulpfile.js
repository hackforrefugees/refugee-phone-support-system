"use strict";

var path          = require("path"),
	gulp          = require("gulp"),
	browserify    = require("browserify"),
	watchify      = require("watchify"),
	source        = require("vinyl-source-stream"),
	buffer        = require("vinyl-buffer"),
	newer         = require("gulp-newer"),
	rename        = require("gulp-rename"),
	rev           = require("gulp-rev"),
	revReplace    = require("gulp-rev-replace"),
	imagemin      = require("gulp-imagemin"),
	sass          = require("gulp-sass"),
	please        = require("gulp-pleeease"),
	uglify        = require("gulp-uglify"),
	sourcemaps    = require("gulp-sourcemaps"),
	htmlmin       = require("gulp-htmlmin"),
	templateCache = require("gulp-angular-templatecache"),
	livereload    = require("gulp-livereload"),
	through       = require("through2");



var prefixStream = function (prefixText) {
	var stream = through();
	stream.write(prefixText);
	return stream;
};

// More or sass copied directly from https://github.com/gulpjs/gulp/blob/master/docs/writing-a-plugin/
var wrap = function (prefix, suffix) {
	prefix = new Buffer(prefix);
	suffix = new Buffer(suffix);

	return through.obj(function (file, encoding, callback) {
		if (file.isBuffer()) {
			file.contents = Buffer.concat([prefix, file.contents, suffix]);
		}

		if (file.isStream()) {
			var prefixStream = prefixStream(prefix);
			prefixStream.on("error", this.emit.bind(this, "error"));
			file.contents = file.contents.pipe(prefixStream);
			file.contents.write(suffix);
		};

		this.push(file);
		callback();
	});
};



var forProduction    = false,
	sassSource       = "./app/scss/*.scss",
	mainScss         = "./app/scss/app.scss",
	fontsSrc         = "./app/scss/fonts/*",
	imgSrc           = "./app/img/*",
	partialsSrc      = "./app/views/partials/*.html",
	viewsSrc         = ["./app/views/**/*.html", "!" + partialsSrc],
	vendorSrc        = "./app/js/vendor/*.js",
	jsSource         = ["./app/js/**/*.js", "!./app/js/templates.js", "!" + vendorSrc],
	mainJs           = "./app/js/app.js",
	imageminConfig   = { progressive: true };

var browserifyBundle = function (b, callback) {
	var stream = b.bundle()
		.pipe(source(mainJs))
		.pipe(buffer())
		.pipe(sourcemaps.init());

	if (forProduction)
		stream.pipe(uglify());

	stream.pipe(rename("app.js"))
		.pipe(sourcemaps.write("./"))
		.pipe(gulp.dest("./public/js"))
		.pipe(livereload())
		.on("end", callback || function () {});

	return stream;
};

// Why do we use a callback here, you ask? Well, because gulp seems to be
// ending this task before all the contents of app.js has been written to disk
// if we just return a stream, or something. Anyway, the problem manifests
// itself in that the generateRev task doesn't find app.js in that case
gulp.task("js", ["partials"], function (callback) {
	var jsCompiler = browserify();
	jsCompiler.add(mainJs);
	browserifyBundle(jsCompiler, callback);
});

gulp.task("watchify", ["partials"], function () {
	var jsCompiler = browserify(watchify.args),
		watcher = watchify(jsCompiler);

	watcher.on("update", function () {
		browserifyBundle(watcher);
	});

	watcher.add(mainJs);
	browserifyBundle(watcher);
});

gulp.task("vendorJs", function () {
	return gulp.src(vendorSrc)
		.pipe(uglify())
		.pipe(gulp.dest("./public/js/vendor"));
});



gulp.task("sass", function () {
	return gulp.src(sassSource)
		.pipe(sass())
		.pipe(please({
			autoprefixer: {
				browsers: ["last 2 versions"],
				cascade: false
			},
			minifier: forProduction,
			mqpacker: true
		}))
		.pipe(gulp.dest("./public/css"))
		.pipe(livereload());
});

gulp.task("fonts", function () {
	return gulp.src(fontsSrc)
		.pipe(gulp.dest("./public/css/fonts"));
});

gulp.task("views", function () {
	var dest = "./public";

	return gulp.src(viewsSrc)
		.pipe(newer(dest))
		.pipe(htmlmin({
			collapseWhitespace: true,
			conservativeCollapse: true
		}))
		.pipe(gulp.dest(dest))
		.pipe(livereload());
});

gulp.task("partials", function () {
	return gulp.src(partialsSrc)
		.pipe(templateCache({
			root: "partials",
			standalone: true,
			templateHeader: 'module.exports = "templates"; angular.module("<%= module %>"<%= standalone %>).run(["$templateCache", function($templateCache) {'
		}))
		.pipe(gulp.dest("./app/js"));
});

gulp.task("img", function () {
	return gulp.src(imgSrc)
		.pipe(imagemin(imageminConfig))
		.pipe(gulp.dest("./public/img"))
		.pipe(livereload());
});

gulp.task("clean", function () {
	var del = require("del");

	return del("./public");
});

gulp.task("revRename", function () {
	return gulp.src("./public/{css,js,img}{/,/vendor/}*!(.ico)")
		.pipe(rev())
		.pipe(gulp.dest("./public"))
		.pipe(rev.manifest())
		.pipe(gulp.dest("./public"));
});

gulp.task("revReplace", ["revRename"], function () {
	var prependSlash = filename => `/${filename}`.replace(/\/{2}/, "/");

	return gulp.src("./public/*.html")
		.pipe(revReplace({
			manifest: gulp.src("./public/rev-manifest.json"),
			modifyUnreved: prependSlash,
			modifyReved: prependSlash
		}))
		.pipe(gulp.dest("./public"));
});



gulp.task("jshint", function () {
	var jshint = require("gulp-jshint");

	return gulp.src(jsSource)
		.pipe(jshint())
		.pipe(jshint.reporter("jshint-stylish"));
});

gulp.task("server", function () {
	var superstatic = require("superstatic").server,
		open = require("open");

	var app = superstatic({
		port: 8000
	});

	app.listen(open.bind(open, "http://localhost:8000"));
});



gulp.task("watch", ["server", "watchify", "sass", "fonts", "views", "partials", "img", "vendorJs"], function () {
	livereload.listen();

	gulp.watch([vendorSrc], ["vendorJs"]);
	gulp.watch([imgSrc], ["img"]);
	gulp.watch([sassSource], ["sass"]);
	gulp.watch([viewsSrc], ["views"]);
	gulp.watch([partialsSrc], ["partials"]);
});

gulp.task("build", function () {
	var runSequence = require("run-sequence");
	forProduction = true;

	return runSequence("clean", ["views", "img", "fonts", "sass", "js", "vendorJs"], "revReplace");
});



gulp.task("default", ["jshint", "watch"]);
