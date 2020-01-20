/*=============================================
=            CONFIG            =
=============================================*/
var config_ftp = {
    host: 'fotobudkaraspberry.pl',
    port: '22',
    user: 'wojtek',
    // pass: 'tomaszpilka',
    key: 'id_rsa',
    // key: '/Users/fotobudkadev4/Desktop/klucz/klucz',
    remotePath: '/home/wojtek/wordpress/wp-content/themes/fotobudka'
}

/*=====  End of CONFIG  ======*/




/*=============================================
=            PACKAGES            =
=============================================*/

const gulp = require('gulp');

const clean = require('gulp-clean'),
    changed = require('gulp-changed'),
    minifycss = require('gulp-minify-css'),
    imagemin = require('gulp-imagemin'),
    webp = require('gulp-webp'),
    soften = require('gulp-soften'),
    watch = require('gulp-watch'),
    uglify = require('gulp-uglify'),
    terser = require('gulp-terser'),
    sourcemaps = require('gulp-sourcemaps'),
    autoprefixer = require('gulp-autoprefixer'),
    rename = require('gulp-rename'),
    gutil = require('gulp-util'),
    sass = require('gulp-sass'),
    del = require('del'),
    path = require('path');

const ftp = (typeof config_ftp.pass != 'undefined') ? require('gulp-ftp') : require('gulp-sftp');



/*=====  End of PACKAGES  ======*/






/*======VARIABLES======*/


var src = './src/',
    dist = './dist/';


var srcStyles = src + '**/*.css',
    srcScss = src + '**/*.scss',
    srcScripts = src + '**/*.js',
    srcImages = src + '**/*.{gif,png,jpg,jpeg,webp}',
    srcPhpHTMLFiles = src + '**/*.{html,php,tpl}',
    srcFonts = src + '**/*.{eot,svg,ttf,woff,woff2}';

// var distStyles = dist + '**/*.css',
var distStyles = dist + 'css/',
    distScripts = dist + '**/*.js',
    distImages = dist + '**/*.{gif,png,jpg,jpeg,webp}',
    distPhpHTMLFiles = dist + '**/*.{html,php,tpl}',
    distFonts = dist + '**/*.{eot,svg,ttf,woff,woff2}';


var ignoredDirs = '{node_modules,src}';



/*=====  End of VARIABLES  ======*/

/*===TASKS===*/



/*----------  CLEAN FILES  ----------*/



gulp.task('cleancss', function () {
    return gulp.src([dist + '**/*.css', dist + '**/*.css.map'], {
            read: false
        })
        .pipe(clean());
});

gulp.task('cleanjs', function () {
    return gulp.src([dist + '**/*.js'], {
            read: false
        })
        .pipe(clean());
});

gulp.task('cleanimages', function () {
    return gulp.src([dist + '**/*.{gif,png,jpg,jpeg,webp}'], {
            read: false
        })
        .pipe(clean());
});

gulp.task('cleanfonts', function () {
    return gulp.src([dist + '**/*.{eot,svg,ttf,woff}'], {
            read: false
        })
        .pipe(clean());
});






gulp.task('tabsto4spaces', function () {
    return gulp.src(srcPhpHTMLFiles)
        .pipe(soften(4)) //4 spaces
        .pipe(gulp.dest(dist));
})



/*----------  MOVE OTHER FILES  ----------*/


gulp.task('moveotherfiles', function () {
    return gulp.src(['./src/**/*', '!./src/**/*.{css,js,gif,png,jpg,jpeg,webp,html,php,eot,svg,ttf,woff}', '!./' + ignoredDirs + '/**/*', '!./gulpfile.js'])
        .pipe(gulp.dest(dist));
});





/*----------  COMPILE IMAGES  ----------*/


gulp.task('images', function () {
    return gulp.src(srcImages)
        .pipe(imagemin([
            imagemin.gifsicle({
                interlaced: true
            }),
            imagemin.jpegtran({
                progressive: true
            }),
            imagemin.optipng({
                optimizationLevel: 5
            }),
            imagemin.svgo({
                plugins: [{
                        removeViewBox: true
                    },
                    {
                        cleanupIDs: false
                    }
                ]
            })
        ]))
        .pipe(gulp.dest(dist))
        .pipe(ftp(config_ftp))
        .pipe(webp())
        .pipe(rename({
            extname: '.webp'
        }))
        .pipe(gulp.dest(dist))
        .pipe(ftp(config_ftp));
});


/*----------  COMPILE CSS  ----------*/

gulp.task('css', function () {
    return gulp.src(srcStyles)
        .pipe(changed(dist)) //must be dist
        .pipe(gulp.dest(dist))
        .pipe(ftp(config_ftp))
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(sourcemaps.init())
        .pipe(autoprefixer({
            browsers: ['> 1%', 'last 2 versions', 'firefox >= 28', 'safari >= 8', 'IE 9', 'IE 10', 'IE 11']
        }))
        .pipe(minifycss())
        .pipe(sourcemaps.write('.', {
            includeContent: false,
            sourceRoot: '/wp-content/themes/wgu/assets/css/'
        }))
        .pipe(gulp.dest(dist))
        .pipe(ftp(config_ftp));
});


/*----------  COMPILE SASS  ----------*/
gulp.task('sass', function () {
    var compressed = gulp.src([
            srcScss,
            '!' + src + '/**/_*/',
            '!' + src + '/**/_*/**/*'
        ])
        .pipe(changed(srcScss))
        .pipe(sourcemaps.init())
        .pipe(sass({
            outputStyle: 'compressed'
        }).on('error', sass.logError))
        .pipe(autoprefixer({
            browsers: ['> 1%', 'last 2 versions', 'firefox >= 28', 'safari >= 8', 'IE 9', 'IE 10', 'IE 11']
        }))
        .pipe(rename(function (path) {
            path.dirname = path.dirname.replace(/sass/g, 'css');
            path.extname = '.min' + path.extname;
        }))
        .pipe(sourcemaps.write('.', {
            includeContent: false,
            sourceRoot: '/wp-content/themes/wgu/assets/css/'
        }))
        .pipe(gulp.dest(dist))
       .pipe(ftp(config_ftp));


    var uncompressed = gulp.src([
            srcScss,
            '!' + src + '/**/_*/',
            '!' + src + '/**/_*/**/*'
        ])
        .pipe(changed(srcScss))
        .pipe(sass().on('error', sass.logError))
        .pipe(rename(function (path) {
            path.dirname = path.dirname.replace(/sass/g, 'css');
        }))
        .pipe(gulp.dest(dist))
        .pipe(ftp(config_ftp));


    return (uncompressed && compressed);

})



/*----------  COMPILE JAVASCRIPT  ----------*/


gulp.task('js', function () {
    return gulp.src(srcScripts)
        .pipe(changed(dist)) //must be dist
        .pipe(gulp.dest(dist))
        .pipe(ftp(config_ftp))
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(sourcemaps.init())
        .pipe(terser())
        .on('error', function (err) {
            gutil.log(gutil.colors.red('[Error]'), err.toString());
        })
        .pipe(sourcemaps.write('.', {
            includeContent: false,
            sourceRoot: '/wp-content/themes/wgu/assets/js/'
        }))
        .pipe(gulp.dest(dist))
        .pipe(ftp(config_ftp));
});



/*----------  COMPILE PHP  ----------*/



gulp.task('php', function () {
    return gulp.src(srcPhpHTMLFiles)
        .pipe(changed(dist)) //must be dist
        .pipe(gulp.dest(dist))
        .pipe(ftp(config_ftp));
});




/*----------  COMPILE FONTS  ----------*/


gulp.task('fonts', function () {
    return gulp.src(srcFonts)
        .pipe(changed(dist)) //must be dist
        .pipe(gulp.dest(dist))
        .pipe(ftp(config_ftp));
});








/*----------  MAIN REMOTE  ----------*/



gulp.task('main', [
    'tabsto4spaces',
    'cleancss',
    'cleanjs',
    'cleanfonts',
    'cleanimages',

    'css',
    // 'sass',
    'js',
    'php',
    'fonts',
    'images',

    'moveotherfiles'
]);





/*----------  WATCH  ----------*/


gulp.task('watch', function () {


    /**
     *
     * WATCH CSS
     *
     */


    watch(srcStyles, function () {
        gulp.start('css');
    }).on('unlink', function (file_path) {
        var filePathFromSrc = path.relative(path.resolve('src'), file_path);
        var destFilePath = path.resolve('dist', filePathFromSrc);

        del.sync(destFilePath);
        del.sync(destFilePath + '.map');

        filePathFromSrcMin = filePathFromSrc.replace(".css", ".min.css");
        destFilePathMin = path.resolve('dist', filePathFromSrcMin);

        del.sync(destFilePathMin);
        del.sync(destFilePathMin + '.map');

    });



    /**
     *
     * WATCH SASS
     *
     */

    watch([srcScss, src + 'style.scss'], function () {
        gulp.start('sass');
    }).on('unlink', function (file_path) {


        var filePathFromSrc = path.relative(path.resolve('src'), file_path);
        var destFilePath = path.resolve('dist', filePathFromSrc);

        del.sync(destFilePath);
        del.sync(destFilePath + '.map');

        filePathFromSrcMin = filePathFromSrc.replace(".css", ".min.css");
        destFilePathMin = path.resolve('dist', filePathFromSrcMin);

        del.sync(destFilePathMin);
        del.sync(destFilePathMin + '.map');


    });


    /**
     *
     * WATCH JAVASCRIPT
     *
     */

    watch(srcScripts, function (event) {
        gulp.start('js');
    }).on('unlink', function (file_path) {
        var filePathFromSrc = path.relative(path.resolve('src'), file_path);
        var destFilePath = path.resolve('dist', filePathFromSrc);
        del.sync(destFilePath);

        filePathFromSrcMin = filePathFromSrc.replace(".js", ".min.js");
        destFilePathMin = path.resolve('dist', filePathFromSrcMin);

        del.sync(destFilePathMin);
    });



    /**
     *
     * WATCH PHP
     *
     */

    watch(srcPhpHTMLFiles, function (event) {
        gulp.start('php');
    }).on('unlink', function (file_path) {
        var filePathFromSrc = path.relative(path.resolve('src'), file_path);
        var destFilePath = path.resolve('dist', filePathFromSrc);
        del.sync(destFilePath);
    });






    /**
     *
     * WATCH FONTS
     *
     */


    watch(srcFonts, function (event) {
        gulp.start('cleanfonts', 'fonts');
    }).on('unlink', function (file_path) {
        var filePathFromSrc = path.relative(path.resolve('src'), file_path);
        var destFilePath = path.resolve('dist', filePathFromSrc);
        del.sync(destFilePath);
    });

});



/*----------  DEFAULT TASK  ----------*/


gulp.task('default', ['main', 'watch']);

/*=====  End of TASKS  ======*/