$(document).ready(function () {

    $('#container-to-share').change(function () {
        console.log('change');
        var idMachine = $('#container-to-share').val();

        $.ajax({
            method: 'POST',
            url: "./controller/ajax/Return_Groups.php",
            dataType : 'json',
            data: {
                idMachine: idMachine
            },
        })
                .done(function (data) {
                    var groupsSelect = $('#shareable-groups');
                    groupsSelect.html('');
                    $.each(data, function () {
                        // ici, this est "un group"
                        groupsSelect.append($("<option />").val(this.id).text(this.nom).attr('name','idGroupe'));
                    });
                });

    });

});

