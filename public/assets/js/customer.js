$( document ).ready(function() {

    $('#customerDetailClose').click(function () {
        $('#uploadPath').val('/customers');
        $('#uploadPathOld').val('');
        $('#customerDetail').toggleClass( 'slidePanel-show lvl1-sidePanel-show', 1000 );
        $('#customerIndexPanel').toggleClass( 'is-loading');
        $('#userSelector').hide();
    });

    $('#newCustomer').click(function () {
        $('#customerIndexPanel').toggleClass( 'is-loading');
        $('#userSelector').show();
        $('#action').val('new');
        var t = $('#CustomerKdlTable').DataTable();
        t
            .clear()
            .draw();
        $('#CustomerTitle').text('neuer Kunde')
        $('#customerId').val('')
        $('#CustomerTitle').val('')
        $('#customerFullName').val('')
        $('#customerContactPerson').val('')
        $('#customerAdressId').val('')
        $('#adressStreet').val('')
        $('#adressStreetNumber').val('')
        $('#adressPLZ').val('')
        $('#adressTown').val('')
        $('#adressPhone').val('')
        $('#adressFax').val('')
        $('#adressMail').val('')
        $('#customerDetail').toggleClass( 'slidePanel-show lvl1-sidePanel-show', 1000 );

    });

    $('#newDeliveryPlace').click(function () {
        $('#actionDP').val('new');
        $('#deliveryPlaceTitle').text('Neue Lieferstelle');
        $('#dpId').val('');
        $('#Firmenname').val('');
        $('#Anrede').val('');
        $('#Vorname').val('');
        $('#Nachname').val('');
        $('#Strasse').val('');
        $('#Hausnummer').val('');
        $('#PLZ').val('');
        $('#Ort').val('');
        $('#ReFirma').val('');
        $('#ReAnrede').val('');
        $('#ReVorname').val('');
        $('#ReNachname').val('');
        $('#ReStrasse').val('');
        $('#ReHausnummer').val('');
        $('#RePLZ').val('');
        $('#ReOrt').val('');
        $('#IBAN').val('');
        $('#BIC').val('');
        $('#Vorversorger').val('');
        $('#Kundennummer').val('');
        $('#Kundenart').val('');
        $('#Verbrauch').val('');
        $('#MaloID').val('');
        $('#Zaehlernummer').val('');
        $('#MeloID').val('');
        $('#Medium').val('');
        $('#Versorger').val('');
        $('#Tarifname').val('');
        $('#Tarifnummer').val('');
        $('#VersKdNr').val('');
        $('#Abschlussprovision').val('');
        $('#FolgeprovM').val('');
        $('#SpannePKwH').val('');
        $('#AP').val('');
        $('#GP').val('');
        $('#Vertragsbeginn').val('');
        $('#Dauer').val('');
        $('#stab').val('');
        $('#customerDeliversPlace').toggleClass( 'slidePanel-show lvl2-sidePanel-show', 1000 );
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
            $('#action').val('');
    });

    $('#customerSave').click(function () {

        if( !ValidateEmail($('#adressMail').val())) {
            $('#adressMail').focus();
            $('#adressMailError').show();
            return false;
        }
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
        $('#uploadPath').val($('#uploadPathOld').val());
        $('#uploadPathOld').val('');
        $('#customerDeliversPlace').toggleClass( 'slidePanel-show lvl2-sidePanel-show', 1000 );
    });



});

function getCustomerKdl(id){
    $('#action').val('');
    $('#uploadPathOld').val($('#uploadPath').val());
    $('#uploadPath').val('/deliverplace/edit/'+id);
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
            $('#stab').val(kdl[0].stab.date);
            //console.log($.datepicker.formatDate('yy-mm-dd', new Date()))
            //console.log(kdl[0]);

        }});

    $('#customerDeliversPlace').toggleClass( 'slidePanel-show lvl2-sidePanel-show', 1000 );
    //console.log(id);
}

function deleteCustomer(id){

    var test = swal({
        title: "Bist Du sicher?",
        text: "Das Du den Kunden löschen willst",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-warning",
        confirmButtonText: 'Ja ich will!',
        closeOnConfirm: false //closeOnCancel: false

    }, function () {
        var kdl ="";
        $.ajax( {
                method: 'POST',
                url: '/customer/delete/'+id,
                dataType: 'json',
                complete: function (data){
                    //console.log('complete');
                    kdl = JSON.parse(data.responseText);
                    console.log(kdl);
                    swal("Gelöscht", kdl, "success");
                }});

    })
    ;

}
function getCustomerWithAdress(id,test){
    var aktionCell="";
    var inner="";
    $('#customerIndexPanel').toggleClass( 'is-loading');
    $('#action').val('edit');
    $('#DPCustomerID').val(id);
    $('#uploadPathOld').val($('#uploadPath').val());
    $('#uploadPath').val('/customer/edit/'+id);
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
            //console.log(adress);
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
                    if (test){
                        aktionCell = '<a href="#" class="btn btn-sm btn-icon btn-pure btn-default" onclick="getCustomerKdl('+obj.id+')" data-toggle="tooltip" data-original-title="Edit"><i class="icon md-edit success text-success font-size-20" aria-hidden="true"></i></a>' +
                            '<a href="#" class="btn btn-sm btn-icon btn-pure btn-default" onclick="move('+obj.id+')" data-toggle="tooltip" data-original-title="Edit"><i class="icon icon md-accounts-list-alt font-size-20" aria-hidden="true"></i></a>';
                    }else{
                        aktionCell = '<a href="#" class="btn btn-sm btn-icon btn-pure btn-default" onclick="getCustomerKdl('+obj.id+')" data-toggle="tooltip" data-original-title="Edit"><i class="icon md-edit success text-success font-size-20" aria-hidden="true"></i></a>' +
                            '<a href="#" data-toggle="modal" data-target="#newCheck" data-id="'+obj.id+'" class="btn btn-sm btn-icon btn-pure btn-default" onclick="" data-toggle="tooltip" data-original-title="neuer Ablese Termin"><i class="icon icon md-collection-item-9-plus font-size-20" aria-hidden="true"></i></a>';

                    }

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
                        aktionCell

                ] ).draw( false );


                    //console.log(obj);
                    $('#CustomerKdls').show();
                    });
            }
        }});
    $('#customerDetail').toggleClass( 'slidePanel-show lvl1-sidePanel-show', 1000 );

    //$('#customerIndexPanel').toggleClass( 'is-loading');
}



function ValidateEmail(mail)
{
    if (/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(customerForm.mail.value))
    {
        return (true)
    }
    toastr.error('Bitte die Eingaben überprüfen');
    return (false)
}