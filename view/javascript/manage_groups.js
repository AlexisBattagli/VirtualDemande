$(document).ready(function () {

    $('#submit-clone-container').on('click', function () {
        console.log('click');
        var idMachine = $('#idMachine').val();
        var cloneName = $('#nomMachineClone').val();

        $.ajax({
            method: 'POST',
            url: "./controller/ajax/Clone_Container.php",
            dataType: 'json',
            timeout: '5000',
            data: {
                idMachine: idMachine,
                nomMachineClone: cloneName       //rendre dynamique
            },
        })
                .complete(function (data) {  //remettre done
                    console.log('done')
                    var alert = $('#alert-clone');
                    $('#cloneContainer').modal('hide');

//                    faire if pour succes/echec
                    alert.removeClass('hidden');
                    //a utiliser si check erreur ou non
//                    var alertSpan = $('#alert-clone-span')  
//                    alertSpan.append('coucou');  //a rendre dynamique en fct retour controller
                });
    });
});

