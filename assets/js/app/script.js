
/* 
 * ------------------------------------
 *               BASE
 * ------------------------------------
 * 
 */

var App = {};

$(document).on('click', 'a[href="#"]', function (e) {
    e.preventDefault();
});

setTimeout(function () {

    $('.alert').slideUp();

}, 3000);

/* 
 * ------------------------------------
 *              CALENDAR
 * ------------------------------------
 * 
 */

App.calendar = {};

App.calendar.courseList = function (courses, absences, date) {

    var container = $('.course-list');
    var items = $('.view-course', container);

    // Hide courses items
    $('.item-choose-date').hide();
    $('.item-no-absences').hide();
    $('.item-no-courses').hide();

    var hasAbsences = false;
    var hasCourses = false;

    // Pour chaque lien
    items.each(function () {

        // On récupère le lien
        var $link = $(this);

        // On récupère l'ID du cours
        var courseId = ~~$link.data('id');

        // On regarde si il est dans la variable "courses"
        // Pour savoir si il y a cours ce jour ou pas
        var courseThisDay = courses.indexOf(courseId) > -1;

        // On le cache
        $link.hide();

        if (courseThisDay) {

            // Il y a eu cours
            hasCourses = true;

            // On regarde si il est dans la variable "absences"
            // Pour savoir si il était absent à ce cours
            $.each(absences, function () {
                var absenceId = ~~this[0],
                    absenceAt = ~~this[1];

                if (absenceAt === courseId) {

                    // On affiche le lien
                    $link.show();

                    // Il y a eu une absence
                    hasAbsences = true;

                    // Et on fait le lien
                    $link.attr('href', $link.data('src') + absenceId + '/');

                }

            });

        }

        if (hasCourses) {
            if (!hasAbsences) {
                $('.item-no-absences').show();
            }
        } else {
            $('.item-no-courses').show();            
        }
        
    });
    return false;
};

/* 
 * ------------------------------------
 *               PANEL
 * ------------------------------------
 * 
 */

App.panel = {};

App.panel.justify = function (entry) {
    $('.absence-list .item.missing').removeClass('missing');
    $(entry).parent('.item').addClass('missing');

    var $entry = $(entry),
        id = ~~$entry.data('id'),
        img = $entry.data('img'),
        name = $entry.find('.name').text(),
        reason = $entry.find('.reason').html();

    console.log(name);

    var $view = $('.absence-view');
    $view.find('.no-select').hide();
    $view.find('.details').show();

    $view.find('.name').text(name);
    $view.find('.reason').html(reason);

    $view.find('.img').empty();

    if (img.length > 10) {
        $view.find('.img').append(
            $('<p>').text("Cliquez sur l'image pour la voir en taille réelle")
        ).append(
            $('<a>').attr('href', img).attr('target', '_blank').append(
                $('<img>').attr('src', img)
            )
        );
    } else {
        $view.find('.img').text('Aucune image fournie.');
    }

    $view.find('.entry-id').val(id);

};

var toggle = $('.auto-toggle');
var check = function () {
    toggle.each(function (i, e) {
        if ($(e).hide().parent().height() === 0) {
            $(e).show();
        }
    });
};

var apply = function () {
    check();
    setTimeout(check, 1);
    setTimeout(check, 5);
};

$(document).on('click', apply);

apply();
