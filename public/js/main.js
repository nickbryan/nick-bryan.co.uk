(function($) {

    window.TreehouseProfileHandler = function(profile, badgeWrapper, languageWrapper) {
        if (profile == '' || typeof profile === "undefined") {
            console.error('TreehouseProfileHandler', 'Profile not set');
        }

        if (badgeWrapper == '' || typeof badgeWrapper === "undefined") {
            console.error('TreehouseProfileHandler', 'Badge wrapper not set');
        }

        if (languageWrapper == '' || typeof languageWrapper === "undefined") {
            console.error('TreehouseProfileHandler', 'Language wrapper not set');
        }

        this.profile = "https://teamtreehouse.com/" + profile + ".json";
        this.profileData = null;

        this.badWrapper = badgeWrapper;
        this.languageWrapper = languageWrapper;

        this.numberOfBadges = 4;

        this.loadProfileData();
    };

    TreehouseProfileHandler.prototype = {

        constructor: TreehouseProfileHandler,

        loadProfileData: function() {
            $.ajax({
                url: this.profile,
                dataType: 'json',
                type: 'GET',
                success: (function (json) {
                    this.profileData = json;

                    this.storeUserData(json);
                    this.drawBadges();
                    this.drawActivity();
                }).bind(this),
                error: function () {
                    console.error('There was an error receiving profile data');
                }
            });
        },

        drawBadges: function() {
            $('#' + this.badWrapper).empty();

            this.profileData.badges.reverse();

            for (var i = 0; i < this.numberOfBadges; i++) {
                var badge = this.profileData.badges[i];

                $('#' + this.badWrapper).append(
                    '<div class="treehouse-badge">' +
                        '<a href="' + badge.url + '">' +
                            '<img src="' + badge.icon_url + '" alt="Icon for ' + badge.name + '">' +
                        '</a>' +
                        '<p>' + badge.name + '</p>' +
                    '</div>'
                );
            }
        },

        drawActivity: function() {
            $('#' + this.languageWrapper).parent().empty().append('<ul id="language-list" class="language-list"></ul>');

            var sorted = [];
            for (var language in this.profileData.points) {
                sorted.push([language, this.profileData.points[language]]);
            }

            sorted.sort(function(a, b) {
                return b[1] - a[1];
            });

            var languages = {};
            for (var i = 0; i < sorted.length; i++) {
                if (sorted[i][1] == 0) {
                    continue;
                }
                languages[sorted[i][0]] = sorted[i][1];
            }

            for (var language in languages) {
                var points = languages[language];

                if (language == "total") {
                    $('<p class="total-points">' + points + ' <span id="total-points">Total Points</span></p>').insertBefore('#' + this.languageWrapper);
                    continue;
                }

                $('#' + this.languageWrapper).append(
                    '<li>' +
                        '<strong class="title">' + language + '</strong>' +
                        '<span class="content">' + points + '</span>' +
                    '</li>'
                );
            }
        },

        storeUserData: function(data) {
            var badges = [];
            for (var i = data.badges.length - this.numberOfBadges; i < data.badges.length; i++) {
                badges.push(data.badges[i]);
            }

            data.badges = badges;

            $.ajax({
                url: '/treehouse',
                data: data,
                dataType: 'json',
                type: 'POST',
                success: (function (json) {

                }).bind(this),
                error: function (xhr, ajaxOptions, thrownError) {
                    console.error(xhr, ajaxOptions, thrownError);
                }
            });
        }

    };

}(jQuery));

// Pass $ into jquery ready so we know its linked to jquery
jQuery(document).ready(function($) {

    $(document).foundation();

    // Push the body and the nav over by 285px
    $('#toggle-navigation').click(function() {
        $('.nav').animate({
            left: "0px"
        }, 200);

        $('#page').animate({
            left: "285px"
        }, 200);

        $('.modal-body').animate({
            left: "285px"
        }, 200);

        $('.modal-body').toggleClass('modal-active');

        $('body').css('overflow', 'hidden');
    });

    // Then push them back
    $('#close-navigation').click(function() {
        $('.nav').animate({
            left: "-285px"
        }, 200);

        $('#page').animate({
            left: "0px"
        }, 200);

        $('.modal-body').animate({
            left: "0px"
        }, 200);

        $('.modal-body').toggleClass('modal-active');

        $('body').css('overflow', 'auto');
    });

    $('.modal-body').click(function() {
        $('#close-navigation').click();
    });

    (new TreehouseProfileHandler("nickbryan", "treehouse-badge-wrapper", "language-list"));

});
