$("#inputRue").keyup(function(event) {
    // Stop la propagation par défaut
    event.preventDefault();
    event.stopPropagation();
    // Récupération de la value rue
    let rue = $("#inputRue").val();
    //La requête API demande 3 lettres minimum pour rechercher
    if (rue.length >= 3) {
        $.get('https://api-adresse.data.gouv.fr/search/', {
            q: rue,
            limit: 15,
            autocomplete: 1
        }, function (data) {
            let liste = "";
            $.each(data.features, function(i, obj) {
                // J'ajoute chaque élément dans une liste
                liste += '<li><a href="#" name="'+obj.properties.label+'" data-name="'+obj.properties.name+'" data-postcode="'+obj.properties.postcode+'" data-city="'+obj.properties.city+'" data-lat="'+obj.geometry.coordinates[1]+'" data-long="'+obj.geometry.coordinates[0]+'">'+obj.properties.label+'</a></li>';
            });
            $('.adress-feedback ul').html(liste);

            $('.adress-feedback ul>li').on("click","a", function(event) {
                // Stop la propagation par défaut
                event.preventDefault();
                event.stopPropagation();

                // let adresse = $(this).attr("name");
                $("#inputRue").val($(this).attr("data-name"));
                $("#inputCodePostal").val($(this).attr("data-postcode"));
                $("#inputVille").val($(this).attr("data-city"));
                $("#inputLat").val($(this).attr("data-lat"));
                $("#inputLong").val($(this).attr("data-long"));

                $('.adress-feedback ul').empty();
            });

        }).error(function () {
            alert( "error" );
        }).always(function () {
            alert( "finished" );
        }, 'json');
    }
});