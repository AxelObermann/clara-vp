$( document ).ready(function() {

    $('#customerDetailClose').click(function () {
        $('#customerDetail').toggleClass( 'slidePanel-show lvl1-sidePanel-show', 1000 );
        $('#customerIndexPanel').toggleClass( 'is-loading');
    });



    $('#customerDeliversPlaceSave').click(function () {
        var myForm = document.getElementById('deliveryPlaceForm');
        var formData = new FormData(myForm),
            result = {};

        for (var entry of formData.entries())
        {
            result[entry[0]] = entry[1];
        }
        result = JSON.stringify(result)
        $.ajax( {
            method: 'POST',
            url: '/customer/saveDeliveryPlace/',
            dataType: 'json',
            data: result,
            complete: function (data){
                //console.log(JSON.parse(data.responseText));
                toastr.success(JSON.parse(data.responseText));
            }});
    });

    $('#customerSave').click(function () {
        //console.log("klick save Customer");
        var myForm = document.getElementById('customerForm');
        var formData = new FormData(myForm),
            result = {};

        for (var entry of formData.entries())
        {
            result[entry[0]] = entry[1];
        }
        result = JSON.stringify(result)

        $.ajax( {
            method: 'POST',
            url: '/customer/saveCustomer/',
            dataType: 'json',
            data: result,
            complete: function (data){
                //console.log(JSON.parse(data.responseText));
                toastr.success(JSON.parse(data.responseText));
            }});
    });

    $('#customerDeliversPlaceClose').click(function () {
        $('#customerDeliversPlace').toggleClass( 'slidePanel-show lvl2-sidePanel-show', 1000 );
    });
});

function getCustomerKdl(id){
    $.ajax( {
        method: 'POST',
        url: '/customer/get_kdl/'+id,
        dataType: 'json',
        complete: function (data){
            //console.log('complete');
            var kdl = JSON.parse(data.responseText);
            $('#deliveryPlaceTitle').text(kdl[0].Firmenname);
            $('#dpId').val(kdl[0].id);
            $('#Firmenname').val(kdl[0].Firmenname);
            $('#Anrede').val(kdl[0].Anrede);
            $('#Vorname').val(kdl[0].Vorname);
            $('#Nachname').val(kdl[0].Nachname);
            $('#Strasse').val(kdl[0].Strasse);
            $('#Hausnummer').val(kdl[0].Hausnummer);
            $('#PLZ').val(kdl[0].PLZ);
            $('#Ort').val(kdl[0].Ort);
            $('#ReFirma').val(kdl[0].ReFirma);
            $('#ReAnrede').val(kdl[0].ReAnrede);
            $('#ReVorname').val(kdl[0].ReVorname);
            $('#ReNachname').val(kdl[0].ReNachname);
            $('#ReStrasse').val(kdl[0].ReStrasse);
            $('#ReHausnummer').val(kdl[0].ReHausnummer);
            $('#RePLZ').val(kdl[0].RePLZ);
            $('#ReOrt').val(kdl[0].ReOrt);
            $('#IBAN').val(kdl[0].Iban);
            $('#BIC').val(kdl[0].Bic);
            $('#Vorversorger').val(kdl[0].Vorversorger);
            $('#Kundennummer').val(kdl[0].Kundennummer);
            $('#Kundenart').val(kdl[0].Kundenart);
            $('#Verbrauch').val(kdl[0].Verbrauch);
            $('#MaloID').val(kdl[0].MaloID);
            $('#Zaehlernummer').val(kdl[0].Zaehlernummer);
            $('#MeloID').val(kdl[0].MeloID);
            $('#Medium').val(kdl[0].Medium);
            $('#Versorger').val(kdl[0].Versorger);
            $('#Tarifname').val(kdl[0].Tarifname);
            $('#Tarifnummer').val(kdl[0].Tarifnummer);
            $('#VersKdNr').val(kdl[0].VersKdNr);
            $('#Abschlussprovision').val(kdl[0].Abschlussprovision);
            $('#FolgeprovM').val(kdl[0].FolgeprovM);
            $('#SpannePKwH').val(kdl[0].SpannePKwH);
            $('#AP').val(kdl[0].AP);
            $('#GP').val(kdl[0].GP);
            $('#Vertragsbeginn').val(kdl[0].Vertragsbeginn.date);
            $('#Dauer').val(kdl[0].Dauer);
            //console.log($.datepicker.formatDate('yy-mm-dd', new Date()))
            //console.log(kdl[0]);

        }});

    $('#customerDeliversPlace').toggleClass( 'slidePanel-show lvl2-sidePanel-show', 1000 );
    //console.log(id);
}
function getCustomerWithAdress(id){
    var inner="";
    $('#customerIndexPanel').toggleClass( 'is-loading');
    //$('#customerPanel').toggleClass( 'is-loading');


    $.ajax( {
        method: 'POST',
        url: '/customer/edit/'+id,
        dataType: 'json',
        complete: function (data){
            //console.log('complete');
            var custo = JSON.parse(data.responseText);
            $('#CustomerTitle').text(custo[0].fullName)
            $('#customerId').val(custo[0].id)
            $('#CustomerTitle').val(custo[0].fullName)
            $('#customerFullName').val(custo[0].fullName)
            $('#customerContactPerson').val(custo[0].contactPerson)
            //console.log(custo[0]);
        }});

    $.ajax( {
        method: 'POST',
        url: '/customer/getAdress/'+id,
        dataType: 'json',
        complete: function (data){
            //console.log('complete');
            var adress = JSON.parse(data.responseText);
            $('#customerAdressId').val(adress[0].id)
            $('#adressStreet').val(adress[0].street)
            $('#adressStreetNumber').val(adress[0].streetNumber)
            $('#adressPLZ').val(adress[0].zip)
            $('#adressTown').val(adress[0].town)
            $('#adressPhone').val(adress[0].phone)
            $('#adressFax').val(adress[0].fax)
            $('#adressMail').val(adress[0].mail)
            console.log(adress);
        }});
    $.ajax( {
        method: 'POST',
        url: '/customer/getkdls/'+id,
        dataType: 'json',
        complete: function (data){
            //console.log('complete');
            var kdls = JSON.parse(data.responseText);
            console.log(kdls);
            var t = $('#CustomerKdlTable').DataTable();
            rows = t
                .rows()
                .remove()
                .draw();
            if (kdls.length != 0){
                kdls.forEach(function(obj) {
                    t.row.add( [
                        obj.Tarifnummer,
                        obj.Firmenname,
                        obj.Vorname,
                        obj.Nachname,
                        '<i class="icon '+obj.Kundenart+'" aria-hidden="true" style="font-size: 25px;color: #f19408;"></i>',
                        obj.Strasse,
                        obj.Hausnummer,
                        obj.PLZ,
                        obj.Ort,
                        '<i class="icon '+obj.Medium+'" aria-hidden="true" style="font-size: 25px;color: #f19408;"></i>',
                        obj.Verbrauch,
                        obj.Versorger,
                        obj.Zaehlernummer,
                        '<a href="#" class="btn btn-sm btn-icon btn-pure btn-default" onclick="getCustomerKdl('+obj.id+')" data-toggle="tooltip" data-original-title="Edit"><i class="icon md-edit success text-success font-size-26" aria-hidden="true"></i></a>'
                    ] ).draw( false );


                    //console.log(obj);
                    $('#CustomerKdls').show();
                    });
            }
        }});
    $('#customerDetail').toggleClass( 'slidePanel-show lvl1-sidePanel-show', 1000 );

    //$('#customerIndexPanel').toggleClass( 'is-loading');
}