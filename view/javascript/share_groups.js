$(document).ready(function () {

    $('#container-to-share').change(function () {
        console.log('change')

        $.ajax({
            method: 'POST',
            url: "./controller/ajax/Return_Groups.php",
            dataType : 'json',
            data: {
                idMachine: '1'
            },
        })
                .done(function (data) {
                    var groupsSelect = $('#shareable-groups');
                    groupsSelect.html('');
                    $.each(data, function () {
                        // ici, this est "un group"
                        groupsSelect.append($("<option />").val(this.id).text(this.nom));
                    });
                });

    });

});

