$(document).ready(function () {

    $('#container-to-share').change(function () {
        console.log('change');
        var idMachine = $('#container-to-share').val();
        console.log(idMachine);

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
                        groupsSelect.append($("<option />").val(this.id).text(this.nom));
                    });
                });

    });
    
    $('#submit-build-container').on('click', function () {
        console.log('click');
        var nameContainer = $('#nameContainer').val();
        var dist = $('#dist').val();
        var cpu = $('#cpu').val();
        var ram = $('#ram').val();
        var stock = $('#stock').val();
        var descriptionContainer = $('#descriptionContainer').val();

        $.ajax({
            method: 'POST',
            url: "./controller/ajax/Clone_Container.php",
            dataType: 'json',
            timeout: '5000',
            data: {
                nameContainer: nameContainer,
                dist: dist,       
                cpu: cpu,
                ram: ram,
                stock: stock,
                descriptionContainer: descriptionContainer
            },
        })
                .complete(function (data) { 
                    console.log('done')
                });
    });
    
    $('#buildContainer').on('hidden.bs.modal', function(){
        location.reload();
    });

});
