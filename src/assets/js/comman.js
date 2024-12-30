page = $('#wrapper').data('page');
toastr.options.positionClass = 'toast-bottom-right';
function smap(url) {
    const script = document.createElement('script');
    script.textContent = `//# sourceMappingURL=${url}?v=${Date.now()}`;
    document.head.appendChild(script);
    script.remove();
}
function toggleAnimeName() {
    $('.dynamic-name').each(function() {
        var currentName = $(this).text()
          , jName = $(this).data('jname')
          , _this = $(this);
        _this.animate({
            'opacity': 0
        }, 200, function() {
            if (jName.length > 0) {
                _this.text(jName).animate({
                    'opacity': 1
                }, 200);
                _this.data('jname', currentName);
            }
        });
    })
}
function watchListSubmit(data) {
    if (!loading) {
        loading = true;
        $.post('/src/pages/ajax/watch-list/add', data, function(res) {
            if (res.redirectTo) {
                window.location.href = res.redirectTo;
            }
            if (res.status) {
                toastr.success(res.msg, 'Success', {
                    timeOut: 5000
                });
                if (res.html) {
                    $('#watch-list-content').html(res.html);
                } else {
                    window.location.reload();
                }
            } else {
                toastr.error(res.msg, '', {
                    timeOut: 5000
                });
            }
            loading = false;
        });
    }
}
$(document).ready(function() {
    // Initialize dropdown functionality
    $('.btn-user-avatar').click(function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).parent().find('.dropdown-menu').toggleClass('show');
    });

    // Close dropdown when clicking outside
    $(document).click(function(e) {
        if (!$(e.target).closest('.dropdown').length) {
            $('.dropdown-menu').removeClass('show');
        }
    });

    // Prevent dropdown from closing when clicking inside it
    $('.dropdown-menu').click(function(e) {
        e.stopPropagation();
    });

    // Add this CSS dynamically
    $('<style>')
        .prop('type', 'text/css')
        .html(`
            .dropdown-menu.show {
                display: block;
                position: absolute;
                transform: translate3d(0px, 38px, 0px);
                top: 0px;
                left: auto;
                right: 0px;
                will-change: transform;
            }
            
            .header_right-user .dropdown-menu {
                min-width: 250px;
                padding: 10px;
                border-radius: 10px;
                border: none;
                box-shadow: 0 0 15px rgba(0,0,0,0.3);
                background: #1e1e1e;
            }

            .dropdown-item {
                color: #ffffff;
                padding: 12px 20px;
                transition: all 0.3s;
                border-radius: 5px;
                margin-bottom: 5px;
            }

            .dropdown-item:hover {
                background: #333333;
                color: #ffffff;
            }

            .dropdown-item i {
                margin-right: 10px;
            }
        `)
        .appendTo('head');

    new Swiper('#slider',{
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        pagination: {
            el: '#slider .swiper-pagination',
            clickable: true,
        },
        loop: true,
        autoplay: {
            delay: 3000,
        },
    });
    new Swiper('#trending-home .swiper-container',{
        slidesPerView: 6,
        spaceBetween: 30,
        navigation: {
            nextEl: '.trending-navi .navi-next',
            prevEl: '.trending-navi .navi-prev',
        },
        breakpoints: {
            320: {
                slidesPerView: 3,
                spaceBetween: 2,
            },
            480: {
                slidesPerView: 3,
                spaceBetween: 15,
            },
            900: {
                slidesPerView: 4,
                spaceBetween: 20,
            },
            1320: {
                slidesPerView: 6,
                spaceBetween: 20,
            },
            1880: {
                slidesPerView: 8,
                spaceBetween: 20,
            },
        },
        autoplay: 2000,
    });
    $(".btn-more-desc").click(function(e) {
        $(".film-description .text").toggleClass("text-full");
        $(this).toggleClass("active");
    });
    $("#mobile_search").click(function() {
        $("#mobile_search, #search").toggleClass("active");
    });
    $(".cbox-collapse .btn-showmore").click(function(e) {
        $(this).parent().find(".anif-block-ul").toggleClass("no-limit");
        $(this).toggleClass("active");
    });
    $("#text-home-expand").click(function(e) {
        $(".text-home").toggleClass("thm-expand");
    });
    $('[data-toggle="tooltip"]').tooltip();
    $(".toggle-basic").click(function(e) {
        $(this).toggleClass("off");
    });
    var hidden_results = true;
    $('#search-suggest').mouseover(function() {
        hidden_results = false;
    });
    $('#search-suggest').mouseout(function() {
        hidden_results = true;
    });
    var timeout = null;
    $('.search-input').keyup(function() {
        if (timeout != null) {
            clearTimeout(timeout);
        }
        timeout = setTimeout(function() {
            timeout = null;
            var keyword = $('.search-input').val().trim();
            if (keyword.length > 1) {
                $('#search-suggest').show();
                $('#search-loading').show();
                $.get("/src/pages/ajax/search/suggest?keyword=" + keyword, function(res) {
                    $('#search-suggest .result').html(res.html);
                    $('#search-suggest .result').slideUp('fast');
                    $('#search-suggest .result').slideDown('fast');
                    $('#search-loading').hide();
                });
            } else {
                $('#search-suggest').hide();
            }
        }, 500);
    });
    $('.search-input').blur(function() {
        if (hidden_results) {
            $('#search-suggest').slideUp('fast');
        }
    });
    $(document).on("click", ".wl-item", function() {
        if (checkLogin()) {
            var type = $(this).data('type');
            if (typeof grecaptcha !== 'undefined') {
                grecaptcha.execute(recaptchaSiteKey, {
                    action: 'wish_list'
                }).then(function(_token) {
                    watchListSubmit({
                        movieId,
                        type,
                        page,
                        _token
                    });
                })
            } else {
                watchListSubmit({
                    movieId,
                    type,
                    page,
                    _token: ''
                });
            }
        }
    });
    $(document).on("click", ".wl-item-wl", function() {
        if (checkLogin()) {
            var type = $(this).data('type')
              , movieId = $(this).data('movieid');
            if (typeof grecaptcha !== 'undefined') {
                grecaptcha.execute(recaptchaSiteKey, {
                    action: 'wish_list'
                }).then(function(_token) {
                    watchListSubmit({
                        movieId,
                        type,
                        page,
                        _token
                    });
                })
            } else {
                watchListSubmit({
                    movieId,
                    type,
                    page,
                    _token: ''
                });
            }
        }
    });
    $(document).on("click", ".list-wl-item", function() {
        if (checkLogin()) {
            var id = $(this).data('id')
            if (typeof grecaptcha !== 'undefined') {
                grecaptcha.execute(recaptchaSiteKey, {
                    action: 'wish_list'
                }).then(function(_token) {
                    watchListSubmit({
                        movieId: id,
                        _token
                    });
                })
            } else {
                watchListSubmit({
                    movieId: id,
                    _token: ''
                });
            }
        }
    });
    $('#profile-form').submit(function(e) {
        e.preventDefault();
        $('#profile-loading').show();
        var formData = $(this).serialize();
        $.post('/src/ajax/update_profile.php', formData, function(res) {
            $('#profile-loading').hide();
            if (res.status) {
                toastr.success(res.msg, '', {
                    timeout: 5000
                });
            } else {
                toastr.error(res.msg, '', {
                    timeout: 5000
                });
            }
        });
    });
    $('#contact-form').submit(function(e) {
        e.preventDefault();
        if (!loading) {
            loading = true;
            $('#contact-loading').show();
            $('#contact-error').hide();
            $('#contact-loading').hide();
            loading = false;
            $('#contact-form')[0].reset();
            toastr.success('Thank you! Your message has been submitted and we will get in touch as soon as possible.', '', {
                timeout: 5000
            });
        }
    });
});
$(document).on('click', '.dropdown-menu-noti,.dropdown-menu-right', function(e) {
    e.stopPropagation();
});
$(document).on('keyup', '#search-ep', function(e) {
    e.preventDefault();
    var value = e.target.value;
    $('.ep-item').removeClass('highlight');
    if (value) {
        var epEl = $('.ep-item[data-number=' + value + ']');
        if (epEl.length > 0) {
            var parent = epEl.parent();
            $('.ep-page-item[data-page=' + parent.data('page') + ']').click();
            if (e.keyCode === 13) {
                $(e.target).val("");
                epEl.click();
            } else {
                epEl.addClass('highlight');
            }
        }
    } else {
        var currPage = $('.ep-item.active').parent().data('page');
        $('.ep-page-item[data-page=' + currPage + ']').click();
    }
});
$('.f-genre-item').click(function() {
    $(this).toggleClass('active');
    var genreIds = [];
    $('.f-genre-item').each(function() {
        $(this).hasClass('active') && genreIds.push($(this).data('id'));
    })
    $('#f-genre-ids').val(genreIds.join(','));
});
if (Cookies.get('DevTools'))
    Cookies.remove('DevTools');
if ($('.film-description .text').length > 0) {
    var fullDes = $('.film-description .text').html();
    if (fullDes.length > 300) {
        var desShow = fullDes.substring(0, 300) + '...<span class="btn-more-desc more"><i class="fas fa-plus"></i> More</span>'
          , desMore = fullDes.substring(301, fullDes.length);
        $('.film-description .text').html(desShow);
    }
    $(document).on('click', '.btn-more-desc', function() {
        if ($(this).hasClass('more')) {
            $('.film-description .text').html(fullDes + '<span class="btn-more-desc less"><i class="fas fa-minus"></i> Less</span>');
        } else {
            $('.film-description .text').html(desShow);
        }
    });
}
