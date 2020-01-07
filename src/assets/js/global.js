lazaLoadingImages = false;
window_loaded = false;

function initMap(){
    var map;
    var map_loaded = false;
    var barkam_styles = [
        {
            "featureType": "administrative",
            "elementType": "labels.text.fill",
            "stylers": [
                {
                    "color": "#444444"
                }
            ]
        },
        {
            "featureType": "landscape",
            "elementType": "all",
            "stylers": [
                {
                    "color": "#f2f2f2"
                }
            ]
        },
        {
            "featureType": "landscape",
            "elementType": "geometry.fill",
            "stylers": [
                {
                    "visibility": "on"
                },
                {
                    "hue": "#ff0000"
                }
            ]
        },
        {
            "featureType": "landscape.man_made",
            "elementType": "geometry",
            "stylers": [
                {
                    "lightness": "100"
                }
            ]
        },
        {
            "featureType": "landscape.man_made",
            "elementType": "labels",
            "stylers": [
                {
                    "visibility": "off"
                }
            ]
        },
        {
            "featureType": "landscape.natural",
            "elementType": "geometry.fill",
            "stylers": [
                {
                    "lightness": "100"
                }
            ]
        },
        {
            "featureType": "landscape.natural",
            "elementType": "labels",
            "stylers": [
                {
                    "visibility": "off"
                }
            ]
        },
        {
            "featureType": "landscape.natural.landcover",
            "elementType": "geometry.fill",
            "stylers": [
                {
                    "visibility": "on"
                }
            ]
        },
        {
            "featureType": "landscape.natural.terrain",
            "elementType": "geometry",
            "stylers": [
                {
                    "lightness": "100"
                }
            ]
        },
        {
            "featureType": "landscape.natural.terrain",
            "elementType": "geometry.fill",
            "stylers": [
                {
                    "visibility": "off"
                },
                {
                    "lightness": "23"
                }
            ]
        },
        {
            "featureType": "poi",
            "elementType": "all",
            "stylers": [
                {
                    "visibility": "off"
                }
            ]
        },
        {
            "featureType": "road",
            "elementType": "all",
            "stylers": [
                {
                    "saturation": -100
                },
                {
                    "lightness": 45
                }
            ]
        },
        {
            "featureType": "road.highway",
            "elementType": "all",
            "stylers": [
                {
                    "visibility": "simplified"
                }
            ]
        },
        {
            "featureType": "road.highway",
            "elementType": "geometry.fill",
            "stylers": [
                {
                    "color": "#eeb52b"
                }
            ]
        },
        {
            "featureType": "road.arterial",
            "elementType": "labels.icon",
            "stylers": [
                {
                    "visibility": "off"
                }
            ]
        },
        {
            "featureType": "transit",
            "elementType": "all",
            "stylers": [
                {
                    "visibility": "off"
                }
            ]
        },
        {
            "featureType": "water",
            "elementType": "all",
            "stylers": [
                {
                    "color": "#ffd900"
                },
                {
                    "visibility": "on"
                }
            ]
        },
        {
            "featureType": "water",
            "elementType": "geometry.fill",
            "stylers": [
                {
                    "visibility": "on"
                },
                {
                    "color": "#cccccc"
                }
            ]
        },
        {
            "featureType": "water",
            "elementType": "labels",
            "stylers": [
                {
                    "visibility": "off"
                }
            ]
        }
    ];
    
    
    function initMap() {
        //console.log('window '+ window_loaded);
        if (!window_loaded)
            return true;
        //console.log('map '+ map_loaded);
        if (map_loaded)
            return true;
    
        var el = document.getElementById('map');
            //console.log(el)
        if (!el || !$(el).is(":visible")){
            //console.log('visible')
            return true;
        }
    
        var $window = $(window),
            windowHeight = $window.height(),
            windowScrollTop = $window.scrollTop();
    
        if ($(el).offset().top - windowScrollTop > windowHeight * 1)
            return true;
    
        map_loaded = true;
        var map_lat = (parseFloat(document.getElementById('map_lat').dataset.value));

        var map_lng = (parseFloat(document.getElementById('map_lng').dataset.value));
       
        var pin_url = (document.getElementById('pin_url').dataset.value);

        var address_url = (document.getElementById('pin_url').dataset.value);
        
        var position = {
            lat: map_lat,
            lng: map_lng
        };
    
        map = new google.maps.Map(el, {
            zoom: window.innerWidth < 575 ? 9 : 11,
            gestureHandling: window.innerWidth < 992 ? 'cooperative' : 'cooperative',
            center: window.innerWidth < 992 ? {
                lat: position.lat,
                lng: position.lng
            } : position,
            zoomControl: true,
            mapTypeControl: false,
            streetViewControl: false,
            fullscreenControl: false,
            styles: barkam_styles
        });
        
       
        var marker = new google.maps.Marker({
            position: {
                lat: position.lat,
                lng: position.lng
            },
            map: map,
            icon: pin_url,
            url: address_url,
            title: "Cookie Travel",
    
        });
    
        var myoverlay = new google.maps.OverlayView();
      myoverlay.draw = function () {
        //this assigns an id to the markerlayer Pane, so it can be referenced by CSS
        this.getPanes().markerLayer.id='markerLayer'; 
      };
      myoverlay.setMap(map);
      marker.addListener('click', function () {
            window.open(this.url)
        }, {passive: true})
    };
    
    
    window_loaded = false;
    $(document).ready(function () {
        $('body').show();
    
        window_loaded = true;
 
        initMap();
    
        $( window ).resize(function() {
          initMap();
       });
    
       $( window ).scroll(function() {
        initMap();
     });
    });
   
}
function getScrollbarWidth() {

    // Creating invisible container
    const outer = document.createElement('div');
    outer.style.visibility = 'hidden';
    outer.style.overflow = 'scroll'; // forcing scrollbar to appear
    outer.style.msOverflowStyle = 'scrollbar'; // needed for WinJS apps
    document.body.appendChild(outer);

    // Creating inner element and placing it in the container
    const inner = document.createElement('div');
    outer.appendChild(inner);

    // Calculating difference between container's full width and the child width
    const scrollbarWidth = (outer.offsetWidth - inner.offsetWidth);

    // Removing temporary elements from the DOM
    outer.parentNode.removeChild(outer);

    return scrollbarWidth;

}
function setCookie(key, value, location, days) {
    var expires = '',
        path = '';
    if (typeof days != 'undefined' && days != '') {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = '; expires=' + date.toGMTString();
    }
    if (typeof location != 'undefined' && location != '') {
        path = '; path=' + location;
    }
    document.cookie = key + "=" + escape(value) + expires + path;
}
function getCookie(key) {
    var keyValue = document.cookie.match('(^|;) ?' + key + '=([^;]*)(;|$)');
    return keyValue ? keyValue[2] : null;
}
function deleteCookie(key) {
    return document.cookie = "" + key + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
}
function lazyLoadImages() {
    lazaLoadingImages = new LazyLoad({
        elements_selector: "img[data-src], .lazy, [data-bg]",
        to_webp: true,
        callback_loaded: function (item) {
            var $item = $(item);

            if ($item.closest('.lazy-container').length) {
                var $container = $item.closest('.lazy-container');
                $container.addClass('lazy-loaded');
                setTimeout(function ($image) {
                    $image.siblings('.lazy-spacer').remove();
                    $image.unwrap();
                }, 450, $item);
            }
        },

    });
}
function swipeDetect(el, callback) {

    var touchsurface = el,
        swipedir,
        startX,
        startY,
        distX,
        distY,
        threshold = 60, //required min distance traveled to be considered swipe
        restraint = 40, // maximum distance allowed at the same time in perpendicular direction
        allowedTime = 300, // maximum time allowed to travel that distance
        elapsedTime,
        startTime,
        handleswipe = callback || function (swipedir) {}

    touchsurface.addEventListener('touchstart', function (e) {
        var touchobj = e.changedTouches[0];
        swipedir = 'none';
        dist = 0;
        startX = touchobj.pageX;
        startY = touchobj.pageY;
        startTime = new Date().getTime(); // record time when finger first makes contact with surface
        //     // e.preventDefault()
    }, false)

    touchsurface.addEventListener('touchmove', function (e) {
        //     // e.preventDefault() // prevent scrolling when inside DIV
    }, false)

    touchsurface.addEventListener('touchend', function (e) {
        var touchobj = e.changedTouches[0]
        distX = touchobj.pageX - startX // get horizontal dist traveled by finger while in contact with surface
        distY = touchobj.pageY - startY // get vertical dist traveled by finger while in contact with surface
        elapsedTime = new Date().getTime() - startTime // get time elapsed
        if (elapsedTime <= allowedTime) { // first condition for awipe met
            if (Math.abs(distX) >= threshold && Math.abs(distY) <= restraint) { // 2nd condition for horizontal swipe met
                swipedir = (distX < 0) ? 'left' : 'right' // if dist traveled is negative, it indicates left swipe
            } else if (Math.abs(distY) >= threshold && Math.abs(distX) <= restraint) { // 2nd condition for vertical swipe met
                swipedir = (distY < 0) ? 'up' : 'down' // if dist traveled is negative, it indicates up swipe
            }
        }
        handleswipe(swipedir);

        // e.preventDefault()
    }, false)
}
$(document).on('click', '#accept-cookie', function (e) {
    e.preventDefault();

    $('.cookie-popup').fadeOut(500, function () {
        $(this).remove();
        setCookie('ALLOW_COOKIE', '1', '/', 9999);
    })
});
function currentMenuPosition(element) {
    if (element.length > 0) {
        var parent_pos_left = element.parent().offset().left,
            element_width = element.width(),
            element_pos_left = element.offset().left + (element_width / 2);
        if (!element.closest('.boxes-column-slider').length)
            element.parent().find('.box-circle').css({
                'transform': 'translateX(' + (element_pos_left - parent_pos_left) + 'px)'
            });
    }
}
function initSliders() {
    $('.boxes-column-slider').on('scroll.flickity', function (event, progress) {

        var $this = $(this);

        progress = Math.max(0, Math.min(1, progress)) * 100;
        // $progressBar.width(progress * 100 + '%');
        $this.siblings('.box-circle-wrap').children('.box-circle').css({
            'left': progress + '%'
        })
    }).flickity({
        autoPlay: true,
        pauseAutoPlayOnHover: false,
        draggable: true,
        pageDots: false,
        prevNextButtons: false,
        freeScroll: true,
        freeScrollFriction: 0.03,
        cellAlign: 'left',
        contain: true,
    })
}

$(window).on('load', function () {
    window_loaded = true;

    $(this).trigger('resize');
    $(this).trigger('scroll');

    setTimeout(function () {
        $('#modal-promotion').modal('show');
    }, 3000);

}).on('resize', function (e) {

}).on('scroll', function (e) {

    var scroll = $(this).scrollTop();

    if (scroll > 0) {
        $('#header').addClass('sticky');
    } else {
        $('#header').removeClass('sticky');
    }
});


$(document).on('click', '#nav-menu-btn', function (e) {

    var target = $(this).data('target');

    $(target).toggleClass('open');
    $(target + '-overlay').toggleClass('open');
    $('#main').toggleClass('open');


    if ($(target).hasClass('open')) {

        $('body').css({
            'overflow': 'hidden',
            'padding-right': getScrollbarWidth() + 'px',
        })
        $('.fixed-top, .fixed-bottom, .is-fixed, .sticky-top').css({
            'padding-right': getScrollbarWidth() + 'px',
        })

        var delay = 100;
        $('#nav_menu > li > a').each(function (i, el) {
            setTimeout(function () {
                $(el).css('padding-left', '0vw');
            }, delay);

            delay += (delay * 0.45 / (i + 1));
        })
    } else {
        $('#nav_menu > li > a, body, .fixed-top, .fixed-bottom, .is-fixed, .sticky-top').removeAttr('style');
    }
})

$(document).on('click', '.navigation-overlay, #nav-menu-close-btn', function (e) {
    $('#nav-menu-btn').trigger('click');
})


$(document).on('keyup', function (e) {
    if (e.keyCode == 27 && $('.navigation').hasClass('open')) {
        $('#nav-menu-btn').trigger('click');
    }
})

$(document).on('click', '#nav_menu li a', function (e) {
    var href = $(this).attr('href');
    if (href[0] == '#') {
        e.preventDefault();
        window.location.hash = '#_';
        window.location.hash = href;
    }
})

$(document).on('click', '#back-to-top', function (e) {
    e.preventDefault();
    $('html,body').stop().animate({
        'scrollTop': 0
    }, 600);
})

$(document).on('change', '.form-control', function (e) {
    var value = $(this).val();

    if (value) {
        $(this).siblings('.control-label').addClass('active')
    } else {
        $(this).siblings('.control-label').removeClass('active')
    }

});

$(document).on('click', '#gallery-load-more', function (e) {
    e.preventDefault();
    var $btn = $(this),
        gallery_show = parseInt($('.gallery').data('show'));

    $('.gallery-item.d-none').each(function (i, el) {
        if (i < gallery_show) {
            $(this).removeClass('d-none');
        }
    })

    if ($('.gallery-item.d-none').length < 1) {
        $btn.prop('disabled', true);
    }
})

$.fn.preventKeyboard = function() {
    return this
      .filter('input')
      .on('focus', function() {
        $(this)
          .attr('readonly', 'readonly')
          .blur()
          .removeAttr('readonly');
      });
  };

var images = document.querySelectorAll('img');
new simpleParallax(images);

var user = $('#user_version').text();
var user_id = $('#user_version_id').text();
var current_session_selected = null;

function getImageSet(id) {
    var settings = {
        "async": true,
        "crossDomain": true,
        "url": "https://fotobudkaraspberry.pl/getPhoto2.php?session_id="+id,
        "method": "GET",
    }

    $('#image-list').text('');
    $.ajax(settings).done(function (response) {

        if (response.length==0){
            $('#image-list').html('<span class="text-danger">Brak zdjęć w wybranej sesji...</span>')
        }else{
            document.getElementById("console_panel").innerHTML = JSON.stringify(response, undefined, 2);
            for (var i = 0, len = response.length; i<len;i++ ){
                $('#image-list').append('<a href="' + response[i].url + '" class="image-container ari-fancybox" title="image"  rel="commune" data-fancybox-group="fb_gallery_0_0" data-fancybox="fb_gallery_0_0" style="background-image: url(' + response[i].url +')"></a>')
            }
        }
        
    });

}



function uploadPhoto(){
    var form = new FormData();
    var image = $("#upload_image").prop('files');

    form.append("photo",image);
    form.append("session_id", "38");
    form.append("series_code", "afg1234");

    var settings = {
        "async": true,
        "crossDomain": true,
        "url": "https://fotobudkaraspberry.pl/uploadPhoto2.php",
        "method": "POST",
        "processData": false,
        "contentType": false,
        "mimeType": "multipart/form-data",
        "data": form
    }

    $.ajax(settings).done(function (response) {
        $('#console_panel').append(response)
    });
}


function setSessionAuto(id) {
    current_session_selected = id;
    var form = new FormData();
    form.append("session_id", id);

    var settings = {
        "async": true,
        "crossDomain": true,
        "url": "https://fotobudkaraspberry.pl/setActiveSession.php",
        "method": "POST",
        "processData": false,
        "contentType": false,
        "mimeType": "multipart/form-data",
        "data": form
    }

    $.ajax(settings).done(function (response) {
        document.getElementById("console_panel").innerHTML = JSON.stringify(response, undefined, 2);
      
        if (user == 'admin') {
            getSessionList();
        } else if (user == 'user') {
            getUserSessions(user_id);
        }
    });
    
}
function getSession() {
    var settings = {
        "async": true,
        "crossDomain": false,
        "url": "https://fotobudkaraspberry.pl/getActiveSession.php",
        "method": "GET",
    }
    $.ajax(settings).done(function (response) {
        current_session_selected = response[0].session_id;
        document.getElementById("console_panel").innerHTML = JSON.stringify(response, undefined, 2);
    });
}
function createSession() {
    var form = new FormData();
    var session_name = document.getElementById("new-session-name").value;
    var session_user = document.getElementById("new-session-user").value;
    form.append("session", session_name);
    form.append("user_id", session_user);
    var settings = {
        "async": true,
        "crossDomain": true,
        "url": "https://fotobudkaraspberry.pl/createSession.php",
        "method": "POST",
        "processData": false,
        "contentType": false,
        "mimeType": "multipart/form-data",
        "data": form
    }

    $.ajax(settings).done(function (response) {
        $('#console_panel').text(response)
        $('#new-session-name').attr('value', null)
        $('#new-session-user').attr('value', null)
        $('.close-popup-session').click();
    });
}
function getSessionList() {


    var settings = {

        "async": true,
        "crossDomain": true,
        "url": "https://fotobudkaraspberry.pl/getSessions.php",
        "method": "GET"
    }

    $.ajax(settings).done(function (response) {
        console.log(response.length);

        document.getElementById("console_panel").innerHTML = JSON.stringify(response, undefined, 2);
 

        console.log(response)
        $('#session-list').text('')
        for (var i = 0, len = response.length; i<len;i++ ){
            var lcase = response[i].session_name.toLowerCase()
            if (current_session_selected == response[i].session_id){
                $('#current-session-box').html('<tr class="session-box active" id="' + response[i].session_id + '" ><span class="delete-session" onclick="deleteSession(' + response[i].session_id + ')">&times</span><td> <span class="session-name"  data-search-term="' + lcase + '">' + response[i].session_name + ' </span></td><td><span id="session-user">' + response[i].session_user_id + '</span></td><td class="status"> <span class="session-status">Aktywna</span></td><td class="activate-session"><i class="fas fa-check"></i></td><td class="load-images" onclick="getImageSet(' + response[i].session_id + ')">Załaduj zdjęcia<i class="fas fa-arrow-down"></i></td></tr>');
            }else{
                $('#session-list').append('<tr class="session-box " id="' + response[i].session_id + '" ><span class="delete-session" onclick="deleteSession(' + response[i].session_id + ')">&times</span><td> <span class="session-name"  data-search-term="' + lcase + '">' + response[i].session_name + ' </span></td><td><span id="session-user">' + response[i].session_user_id + '</span></td><td class="status"> <span class="session-status">Nieaktywna</span></td><td class="activate-session" onclick="setSessionAuto(' + response[i].session_id + ')"><i class="fas fa-check-circle"></i>Aktywuj sesję</td><td class="load-images" onclick="getImageSet(' + response[i].session_id + ')">Załaduj zdjęcia<i class="fas fa-arrow-down"></i></td></tr>');
            } 
        }
    });
}
function getUserSessions(id){
    var settings = {
        "async": true,
        "crossDomain": true,
        "url": "https://fotobudkaraspberry.pl/getUserSessions.php?user_id="+id,
        "method": "GET",

    }

    $.ajax(settings).done(function (response) {
        console.log(response.length);

        document.getElementById("console_panel").innerHTML = JSON.stringify(response, undefined, 2);


        console.log(response)
        $('#session-list').text('')
        for (var i = 0, len = response.length; i < len; i++) {
            var lcase = response[i].session_name.toLowerCase()
            if (current_session_selected == response[i].session_id) {
                $('#current-session-box').html('<div class="session-box active" id="' + response[i].session_id + '" ><span class="delete-session" onclick="deleteSession(' + response[i].session_id + ')">&times</span><p><i class="fas fa-file-image"></i> <span class="session-name"  data-search-term="' + lcase + '">Sesja: ' + response[i].session_name + ' </span></p></p><p class="status">Aktualny status: <span class="session-status">Aktywne</span></p><p class="activate-session"><i class="fas fa-check"></i></p><p class="load-images" onclick="getImageSet(' + response[i].session_id + ')">Załaduj zdjęcia<i class="fas fa-arrow-down"></i></p></div >');
            } else {
                $('#session-list').append('<div class="session-box " id="' + response[i].session_id + '" ><span class="delete-session" onclick="deleteSession(' + response[i].session_id + ')">&times</span><p><i class="fas fa-file-image"></i> <span class="session-name"  data-search-term="' + lcase + '">Sesja: ' + response[i].session_name + ' </span></p></p><p class="status">Aktualny status: <span class="session-status">Nieaktywne</span></p><p class="activate-session" onclick="setSessionAuto(' + response[i].session_id + ')"><i class="fas fa-check-circle"></i>Aktywuj sesję</p><p class="load-images" onclick="getImageSet(' + response[i].session_id + ')">Załaduj zdjęcia<i class="fas fa-arrow-down"></i></p></div >');
            }
        }
    });
}




function refreshList(){
    $('#image-list').text('');

    if (user == 'admin') {
        $.when(getSession()).done(function () {
            getSessionList();
        });
    } else if (user == 'user') {
        $.when(getSession()).done(function () {
            getUserSessions(user_id);
        });
    }
   
}

function deleteSession(id){

    if (confirm("Czy napewno chcesz usunąć?")) {
        var form = new FormData();
        form.append("session_id", id);
        var settings = {
            "async": true,
            "crossDomain": true,
            "url": "https://fotobudkaraspberry.pl/deleteSession.php",
            "method": "POST",
            "processData": false,
            "contentType": false,
            "mimeType": "multipart/form-data",
            "data": form
        }

        $.ajax(settings).done(function (response) {
            $('#console_panel').text('Usunięto')
            refreshList()
        });
        
    } else {
        $('#console_panel').text('Anulowano')
    } 
  
}

$(document).ready(function (e) {

    e(".live-search-list .session-box span").each(function () {
        e(this).attr("data-search-term", e(this).text().toLowerCase())
    }), 
    
    e(".live-search-box").on("keyup touchstart", function () {
        var o = e(this).val().toLowerCase();
        console.log(o)
        e(".live-search-list .session-box").each(function () {
            o.length < 1 ? e(this).fadeIn("fast") : e(this).find(".session-name").filter("[data-search-term *= " + o + "]").length > 0 || o.length < 1 ? e(this).fadeIn("fast") : e(this).fadeOut("fast")
        })
    })
}),



$(document).ready(function (e) {

    swipeDetect($('body')[0], function (direction) {
        if (direction == 'right' && $('.navigation').hasClass('open')) {
            $('#nav-menu-btn').trigger('click');
        }
    });
  
     var user = $('#user_version').text();
     var user_id = $('#user_version_id').text();
     console.log(user)
     console.log(user_id)
     if (user == 'admin'){
         $.when(getSession()).done(function () {
             getSessionList();
         });
     }else if(user == 'user'){
         $.when(getSession()).done(function () {
             getUserSessions(user_id);
         });
     }

   
});


$('.popup-wrap').on('click', function (e) {
    e.preventDefault();
    e.stopPropagation()
})

$('.open-session-popup').on('click', function (e) {
    $('.popup-overlay').toggleClass('d-none')
})

$('.close-popup-session').on('click', function (e) {
    $('.popup-overlay').toggleClass('d-none')
})
$('.popup-overlay').on('click', function(e){
    $(this).toggleClass('d-none')
})







//////////asasasa


// function disableScrolling() {
//     var e = window.scrollX,
//         o = window.scrollY;
//     window.scrollTo(e, o), window.onscroll = function () {
//         window.scrollTo(e, o)
//     }
// }

// function enableScrolling() {
//     window.onscroll = function () { }
// }
// var clicked = !1;

// function modal_notification(e, o) {
//     $("#modal-notification-" + e).toggleClass("hide"), $("#modal-notification-" + e).toggleClass("show"), "" == o && null == o || $(".msg-list").text(o), $(".check-icon").hide(), $(".cross-icon").hide(), setTimeout(function () {
//         $(".check-icon").show()
//     }, 10), setTimeout(function () {
//         $(".cross-icon").show()
//     }, 10)
// }

// function close_notification(e, o) {
//     $("#modal-notification-" + e).toggleClass("hide"), $("#modal-notification-" + e).toggleClass("show"), "success" == o && window.location.reload()
// }
// $(".notification-overlay").on("click", function () {
//     close_X = $(this).find(".modal-close"), close_X.click()
// }), $(".notification-overlay > *").on("click", function (e) {
//     e.stopPropagation()
// }), $(".navbar-wrap").on("click", function (e) {
//     e.stopPropagation()
// }), $("#menu-toggle").click(function () {
//     0 == clicked ? (disableScrolling(), clicked = !0, $(".overlay-menu").fadeIn(), $(".navbar-wrap").slideDown("fast")) : ($(".navbar-wrap").slideUp("fast"), enableScrolling(), clicked = !1, $(".overlay-menu").fadeOut())
// }), $(".overlay-menu").on("click", function (e) {
//     $("#menu-toggle").click()
// });
// var dropdown_clicked = !1;

// function collapse_other() {
//     $(".dropdown").each(function () {
//         $(this).hasClass("rolled-down") || ($(this).find(".mega-menu").stop().slideUp(200), dropdown_clicked = !1)
//     })
// }

// function collapse_menu() {
//     $(".dropdown").each(function () {
//         $(this).find(".mega-menu").stop().slideUp(200), dropdown_clicked = !1, $(this).removeClass("rolled-down")
//     })
// }

// function getDocHeight() {
//     var e = document;
//     return Math.max(e.body.scrollHeight, e.documentElement.scrollHeight, e.body.offsetHeight, e.documentElement.offsetHeight, e.body.clientHeight, e.documentElement.clientHeight)
// }

// function amountscrolled() {
//     var e = window.innerHeight || (document.documentElement || document.body).clientHeight,
//         o = getDocHeight(),
//         t = window.pageYOffset || (document.documentElement || document.body.parentNode || document.body).scrollTop,
//         n = o - e;
//     Math.floor(t / n * 100);
//     return t
// }
// $(".dropdown-menu").on("click", function (e) {
//     e.stopPropagation()
// }), $(".dropdown").mouseenter(function (e) {
//     $(window).width() > 991 && $(this).click()
// }), $(".dropdown").mouseleave(function (e) {
//     $(window).width() > 991 && collapse_menu()
// }), $(".dropdown").on("click", function (e) {
//     $(this).hasClass("rolled-down") ? ($(this).removeClass("rolled-down"), $(this).find(".mega-menu").stop().slideDown("fast"), dropdown_clicked = !1, collapse_menu()) : (collapse_menu(), $(this).addClass("rolled-down"), dropdown_clicked = !1, $(this).find(".mega-menu").stop().slideDown("fast"))
// }), document.addEventListener("scroll", function (e) {
//     amountscrolled();
//     $(window).width()
// }, !0), $(".login-form-open").on("click", function () {
//     disableScrolling(), $(".login-overlay").toggleClass("hide"), $(".login-overlay").toggleClass("show")
// }), $(".login-overlay").on("click", function () {
//     enableScrolling(), $(this).toggleClass("hide"), $(this).toggleClass("show")
// }), $("#login_close").on("click", function () {
//     enableScrolling(), $(".login-overlay").toggleClass("show"), $(".login-overlay").toggleClass("hide")
// }), $(".login-window").on("click", function (e) {
//     e.stopPropagation()
// }), $("#open-modal").on("click", function () {
//     disableScrolling(), $(".modal-overlay").toggleClass("hide"), $(".modal-overlay").toggleClass("show")
// }), $("#footer-form").on("click", function () {
//     enableScrolling(), $("#footer-form").toggleClass("show"), $("#footer-form").toggleClass("hide")
// }), $("#modal_close").on("click", function () {
//     enableScrolling(), $(".modal-overlay").toggleClass("show"), $(".modal-overlay").toggleClass("hide")
// }), $(".modal-window").on("click", function (e) {
//     e.stopPropagation()
// }), $(".list-item").on("click", function (e) {
//     $(this).last().addClass("current-select")
// }), $(window).resize(function () {
//     if ($(window).width() > 1199) {
//         var e = $("#categories-list");
//         e.removeClass("slide-down"), e.removeClass("slide-up"), $(".close-overlay").removeClass("H-100")
//     } else $(window).width() > 991 ? ($("#menu-toggle").prop("checked", !1), 1 != dropdown_clicked && 1 != clicked || (dropdown_clicked = !1, clicked = !1, enableScrolling()), $(".overlay-menu").hide(), $(".navbar-wrap").show()) : $(window).width()
// }), $("#mobile-cat-open").on("click", function () {
//     if ($(window).width() <= 1199) {
//         0 == clicked ? (disableScrolling(), clicked = !0) : (enableScrolling(), clicked = !1);
//         var e = $("#categories-list");
//         e.hasClass("slide-down") ? (e.removeClass("slide-down"), e.addClass("slide-up")) : e.hasClass("slide-up") ? (e.addClass("slide-down"), e.removeClass("slide-up")) : e.addClass("slide-up"), $(".close-overlay").toggleClass("H-100")
//     }
// }), $(".close-overlay").on("click", function () {
//     $("#mobile-cat-open").click()
// });
// var $carousel = $(".council-members");

// function isDesktop() {
//     return window.innerWidth > 991 && $("body").hasClass("device-desktop")
// }

// function setCookie(e, o, t, n) {
//     var i = "",
//         l = "";
//     if (void 0 !== n && "" != n) {
//         var s = new Date;
//         s.setTime(s.getTime() + 24 * n * 60 * 60 * 1e3), i = "; expires=" + s.toGMTString()
//     }
//     void 0 !== t && "" != t && (l = "; path=" + t), document.cookie = e + "=" + escape(o) + i + l
// }

// function getCookie(e) {
//     var o = document.cookie.match("(^|;) ?" + e + "=([^;]*)(;|$)");
//     return o ? o[2] : null
// }

// function deleteCookie(e) {
//     return document.cookie = e + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;"
// }
// $carousel.on("ready.flickity", function () { }), $carousel.flickity({
//     cellAlign: "left",
//     contain: !0,
//     wrapAround: !0,
//     groupCells: !1,
//     selectedAttraction: .01,
//     friction: .15
// }), $carousel.on("change.flickity", function (e, o) {
//     $carousel.find(".is-slide-selected").removeClass("is-slide-selected")
// }), $carousel.on("select.flickity", function (e, o) { }), $carousel.on("settle.flickity", function (e, o) { }), $carousel.on("staticClick.flickity", function (e, o, t, n) {
//     t && ($carousel.find(".is-slide-selected").removeClass("is-slide-selected"), $(t).addClass("is-slide-selected"))
// }),

// $(document).ready(function (e) {
//     e(".live-search-list .candidate-information p").each(function () {
//         e(this).attr("data-search-term", e(this).text().toLowerCase())
//     }), e(".live-search-box").on("keyup touchstart", function () {
//         var o = e(this).val().toLowerCase();
//         e(".live-search-list .candidate-wrap").each(function () {
//             o.length < 1 ? e(this).fadeIn("fast") : e(this).find(".candidate-cred").filter("[data-search-term *= " + o + "]").length > 0 || o.length < 1 ? e(this).fadeIn("fast") : e(this).fadeOut("fast")
//         })
//     })

// }),

// $(document).ready(function () {
//     if ($("#search-term").length) {
//         var e = $("#search-term").text();
//         console.log(e), $(".result p").each(function () {
//             var o = $(this).text(),
//                 t = new RegExp("(" + e + ")", "gi");
//             o = (o = o.replace(t, '<mark class="highlighted">$1</mark>')).replace(/(<mark>[^<>]*)((<[^>]+>)+)([^<>]*<\/mark>)/, "$1</mark>$2<mark>$4"), $(this).html(o)
//         })
//     }
// });
// var window_loaded = !1,
//     window_title = document.title;
// $(window).on("load", function (e) {
//     lazyLoadImages(), window_loaded = !0, $(this).trigger("resize")
// }).on("resize", function () {
//     $(this).trigger("scroll"), window.matchMedia("(max-width: 991px)") && $(".overlay").remove()
// }).on("scroll", function () {
//     $(window).scrollTop() > 1 ? ($("#header").addClass("sticky"), $("#header").hasClass("in") && $("#header").removeClass("in").addClass("out")) : ($("#header").hasClass("out") && $("#header").removeClass("out").addClass("in"), $("#header").removeClass("sticky")), $(window).scrollTop() > 74 ? $(".static").addClass("bar") : $(".static").removeClass("bar")
// }).on("blur", function () { }).on("focus", function () {
//     document.title = window_title
// });
// var lazaLoadingImages = !1;

// function lazyLoadImages() {
//     if (!e) var e = new LazyLoad({
//         elements_selector: "img[data-src], .lazy",
//         to_webp: !1,
//         callback_load: function (e) {
//             ($item = $(e), $item.closest(".lazy-container").length) && ($item.closest(".lazy-container").addClass("lazy-loaded"), setTimeout(function (e) {
//                 e.siblings(".lazy-spacer").remove(), e.unwrap()
//             }, 450, $item))
//         }
//     })
// }
// $(document).on("click", ".cookie-close", function (e) {
//     e.preventDefault(), $(".cookie").fadeOut(500, function () {
//         $(this).remove(), setCookie("ALLOW_COOKIE", "1", "/", 9999)
//     })
// }), $(document).on("click", ".hamburger", function (e) {
//     $(".navbar, .mainMenu").toggleClass("act"), $(this).toggleClass("is-active")
// }), $(document).ready(function () {
//     $(".open-promo").on("click touchstart", function () {
//         $("#promo").removeClass().addClass("is-playing"), $("#promo")[0].play()
//     }), $(".close-promo").on("click touchstart", function () {
//         $("#promo").removeClass().addClass("is-paused"), $("#promo")[0].pause()
//     }), $(".offer-item, .case-study-item").hover(function () {
//         $(".btn-more", this).addClass("hovered")
//     }, function () {
//         $(".btn-more", this).removeClass("hovered")
//     });
//     var e = $('input[name="dodatkowe-wersje-jezykowe[]"]');
//     $(".click-open").on("click", function () {
//         e.is(":checked") ? $(".hidden-box").slideDown(500) : $(".hidden-box").slideUp(500)
//     })
// }), $(function () {
//     $(".www-items");
//     var e = $("button.plus"),
//         o = $("button.minus");
//     e.on("click", function () {
//         var e = $(".www-items label.open").length;
//         return e < 4 && $(".www-items label").each(function (o) {
//             o == e && $(this).addClass("open"), o++
//         }), !1
//     }), o.on("click", function () {
//         return $(".www-items label.open").length > 1 && $(".www-items label.open").last().removeClass("open"), !1
//     })
// }),
//     function (e, o, t) {
//         "use strict";
//         var n = e.matchMedia("(min-width: 1200px)");

//         function i(o) {
//             if (o.matches) {
//                 var t = $(e).scrollTop(),
//                     n = $(".header1").outerHeight(),
//                     i = !1;
//                 $(e).scroll(function () {
//                     if (o.matches) {
//                         if (!i) {
//                             var l = $(this).scrollTop();
//                             Math.abs(t - l) >= 10 && (l <= n && l >= t ? (i = !0, $("html,body").animate({
//                                 scrollTop: $(".section1").offset().top
//                             }, 800, function () {
//                                 i = !1, t = $(e).scrollTop()
//                             })) : l <= n && l < t && (i = !0, $("html,body").animate({
//                                 scrollTop: 0
//                             }, 800, function () {
//                                 i = !1, t = $(e).scrollTop()
//                             })), t = l)
//                         }
//                     } else i = !1
//                 })
//             }
//         }
//         n.addListener(i), i(n)
//     }(window, document), $(function (e) {
//         e(".post-ajax").submit(function (o) {
//             e.ajax({
//                 type: e(this).attr("method"),
//                 url: e(this).attr("action"),
//                 data: e(this).serialize(),
//                 timeout: 1e4,
//                 dataType: "json",
//                 beforeSend: function (o) {
//                     e(':input[type="submit"]').prop("disabled", !0), e("span.error-block").remove(), e("#login_response").text("").hide(), e(".ajax-login-loader").show()
//                 }
//             }).done(function (o) {
//                 e(".ajax-login-loader").hide(), location.reload()
//             }).fail(function (o, t, n) {
//                 var i = JSON.parse(o.responseText);
//                 e(".ajax-login-loader").hide(), 400 == o.status ? "invalid_input_data" == i.error ? e.each(i.errors, function (o, t) {
//                     e('<span class="error-block">' + t + "</span>").insertAfter("input[name='" + o + "']")
//                 }) : "invalid_data" == i.error ? e("#login_response").text(i.message).show() : "invalid_data_status" == i.error && e("#login_response").text(i.message).show() : e("#login_response").text(i.message).show(), setTimeout(function () {
//                     e(':input[type="submit"]').prop("disabled", !1)
//                 }, 2e3)
//             }).always(function (e, o, t) { }), o.preventDefault()
//         })
//     }), $('input[name="voter_login"]').on("keypress keyup blur", function (e) {
//         var o = e.which || e.keyCode,
//             t = e.key;
//         if (!o || 229 == o) {
//             var n = this.value.length - 1 || 0;
//             o = (t = this.value.substr(n, 1)).charCodeAt(0)
//         }
//         var i = $(this).val().split("-").length - 1;
//         switch (o) {
//             case 32:
//                 if ("" == $(this).val() || " " == $(this).val() || $(this).val().indexOf(" ") > 0) return (1 == $(this).val().length || $(this).val().split(" ").length - 1 > 1) && $(this).val($(this).val().substr(0, $(this).val().length - 1)), e.preventDefault(), !1;
//                 break;
//             case 45:
//             case 189:
//                 (1 == $(this).val().length || $(this).val().indexOf("-") > 0 || $(this).val().split("-").length - 1 > 1 || -1 == $(this).val().indexOf(" ") || " " == $(this).val().slice(-1)) && (e.preventDefault(), e.stopPropagation());
//                 break;
//             default:
//                 var l = t;
//                 if (!new RegExp("^[-A-Za-zĘęÓóĄąŚśŁłŻżŹźĆćŃń ]+$").test(l)) return e.preventDefault(), !1
//         }
//         $("#login_response").text("count: " + i + ' char: "' + t + '" charKeyCode: "' + o + '"')
//     }), $('input[name="voter_login"]').bind("copy paste cut", function (e) {
//         e.preventDefault()
//     });
// clicked = !1;
// $(".request-ajax").on("click", function (e) {
//     e.preventDefault(), clicked || (clicked = !0, $.ajax({
//         type: "GET",
//         url: $(this).attr("href"),
//         timeout: 1e4,
//         beforeSend: function (e) {
//             $("#error-list").text("")
//         }
//     }).done(function (e) {
//         modal_notification(1)
//     }).fail(function (e, o, t) {
//         var n = JSON.parse(e.responseText);
//         $(".ajax-login-loader").hide(), 400 == e.status ? "invalid_input_data" == n.error ? $.each(n.errors, function (e, o) {
//             $('<span class="error-block">' + o + "</span>").insertAfter("input[name='" + e + "']")
//         }) : "invalid_data" == n.error ? $("#login_response").text(n.message).show() : "invalid_data_status" == n.error && modal_notification(2, n.message) : 401 == e.status ? modal_notification(2, n.message) : $("#login_response").text(n.message).show(), setTimeout(function () {
//             clicked = !1, $(':input[type="submit"]').prop("disabled", !1)
//         }, 2e3)
//     }).always(function (e, o, t) { }))
// });
// //# sourceMappingURL=global.min.js.map