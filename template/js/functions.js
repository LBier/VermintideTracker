function get_map_options(dlc) {
    var map_dropdown = $('#map_dropdown');

    $.ajax({
        type: "POST",
        url: "ajax/get_map_options.php",
        data: {"dlc": dlc},
        success: function(data) {
            map_dropdown.html(data);

            var first_map = map_dropdown.find("option:first-child").val();
            console.log(first_map);
            get_book_options(first_map, "grimoires");
            get_book_options(first_map, "tomes");
        }
    });
}

function get_book_options(map, book_type) {
    $.ajax({
        type: "POST",
        url: "ajax/get_book_options.php",
        data: {"map": map, "book_type": book_type},
        success: function(data) {
            $('#' + book_type + '_dropdown').html(data);
        }
    });
}

function check_deathwish() {
    var difficulty = $('#dif_dropdown :selected').text();
    if (difficulty === 'Cataclysm') {
        $('#mod_deathwish').removeAttr('disabled');
    } else {
        $('#mod_deathwish').attr('disabled', 'disabled');
    }
}