$( document ).ready(function() {

    $('#customerDetailClose').click(function () {
        $('#uploadPath').val('/kundenindex');
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
        $('#actionDP').val('');

        $.ajax( {
            method: 'POST',
            url: '/customer/saveDeliveryPlace/',
            dataType: 'json',
            data: result,
            complete: function (data){
                //console.log(JSON.parse(data.responseText));
                $('#actionDP').val('');
                toastr.success(JSON.parse(data.responseText));
            }});

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
        $('#action').val('');
        $.ajax( {
            method: 'POST',
            url: '/customer/saveCustomer/',
            dataType: 'json',
            data: result,
            complete: function (data){
                //console.log(JSON.parse(data.responseText));
                $('#action').val('');
                toastr.success(JSON.parse(data.responseText));
            }});
    });

    $('#customerDeliversPlaceClose').click(function () {
        $('#uploadPath').val($('#uploadPathOld').val());
        $('#uploadPathOld').val('');
        $('#uploadedFilesRow').hide();
        var tableulf = $('#uploadedFileTable').DataTable();

        tableulf
            .clear()
            .draw();
        $('#dpChecksRow').hide();
        var tablecheck = $('#dpCheckTable').DataTable();

        tablecheck
            .clear()
            .draw();
        $('#customerDeliversPlace').toggleClass( 'slidePanel-show lvl2-sidePanel-show', 1000 );
    });
}); // end Ready Function

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

            if (kdl[0].stab){

                var cd = new Date(kdl[0].stab.date);
                monat= cd.getMonth()+1;
                $('#stab').val(cd.getDate()+"."+monat+"."+cd.getFullYear());
            }else{
                $('#stab').val('');
            }
            //console.log($.datepicker.formatDate('yy-mm-dd', new Date()))
            //console.log(kdl[0]);
        }});
    $.ajax( {
        method: 'POST',
        //url: '/customer/getfm/'+175,
        url: '/customer/getfm/'+$('#customerId').val(),
        dataType: 'json',
        complete: function (data){
            var optionsadd="";
            if(data.responseText=='false'){
                $('#facUserSelect').empty();
                $("#facUserSelect").selectpicker("refresh");
                $('#kdlFacilityUserSelectWrap').hide();
                console.log('Nein')
            }else{

                var mitarbeiter = JSON.parse(data.responseText);
                $('#kdlFacilityUserSelectWrap').show();
                //console.log(mitarbeiter)
                if (mitarbeiter.length != 0){
                    $('#facUserSelect').empty();

                    mitarbeiter.forEach(function(obj) {
//                        console.log(obj);
                        //$("<option/>").val(option.id).text(option.title).appendTo('#facUserSelect');
                        $('#facUserSelect').append('<option value="'+obj.id+'">'+obj.displayName+'</option>');
                        $("#facUserSelect").val(obj.id);
                        $("#facUserSelect").selectpicker("refresh");
                        //optionsadd = optionsadd + '<option value="'+obj.id+'">'+obj.displayName+'</option>';
                    });
                };
                console.log(optionsadd);
                //$('#facUserSelect').innerHTML =optionsadd;

            }
        }});
    /* get uploaded Files */
    $.ajax( {
        method: 'POST',
        //url: '/customer/getfm/'+175,
        url: '/deliverplace/getUploadedFiles/'+id,
        dataType: 'json',
        complete: function (data){
            var dateien = JSON.parse(data.responseText);
            var d = $('#uploadedFileTable').DataTable();
            console.log(dateien.length)
            if (dateien.length != 0) {
                $('#uploadedFilesRow').show();
                dateien.forEach(function (obj) {
                    d.row.add( [
                        '<a href="'+obj.file+'" target="_blank">'+obj.file+'</a>',
                        obj.uploaded.date
                    ] ).draw( false );
                    console.log(obj.file)
                });
            }
            }
        });
    /* get deliveryPlaceChecks */
    $.ajax( {
        method: 'POST',
        //url: '/customer/getfm/'+175,
        url: '/deliverplace/getchecks/'+id,
        dataType: 'json',
        complete: function (data){
            var checks = JSON.parse(data.responseText);
            var checktable = $('#dpCheckTable').DataTable();
            console.log(checks.length)
            if (checks.length != 0) {
                $('#dpChecksRow').show();
                console.log(checks)

                checks.forEach(function (obj) {
                    var cd = new Date(obj.datum.date);
                    monat =cd.getMonth()+1;
                    checktable.row.add( [
                        cd.getDate()+"."+monat+"."+cd.getFullYear(),
                        obj.wert,
                        '<a href="#" class="btn btn-sm btn-icon btn-pure btn-default" onclick="editDPC('+obj.id+')" data-toggle="tooltip" data-original-title="Bearbeiten"><i class="icon md-edit danger text-success font-size-20" aria-hidden="true"></i></a><a href="#" class="btn btn-sm btn-icon btn-pure btn-default" onclick="deleteDeliveryPlaceCheck('+obj.id+')" data-toggle="tooltip" data-original-title="Löschen"><i class="icon md-delete danger text-danger font-size-20" aria-hidden="true"></i></a>'
                    ] ).draw( false );
                    console.log(obj.file)
                });
            }
            }
        });


    $('#customerDeliversPlace').toggleClass( 'slidePanel-show lvl2-sidePanel-show', 1000 );
    //console.log(id);
}
function editDPC(id,wert,datum){
    console.log('edit')
    $.ajax( {
        method: 'POST',
        //url: '/customer/getfm/'+175,
        url: '/deliverplace/getsinglecheck/'+id,
        dataType: 'json',
        complete: function (data){
            var checks = JSON.parse(data.responseText);
            if (checks.length != 0) {
                checks.forEach(function (obj) {
                    $("#edpcid").val(obj.id);
                    $("#echeckwert").val(obj.wert);
                    $("#echeckdate").val(obj.datum.date);
                    $("#editCheckModal").modal('show');
                });

            }
        }
    });
}
function deleteCustomer(id){

    var customerTable = $('#customerTable').DataTable();

    $('#customerTable').on( 'click', 'tbody tr', function () {
        var table = this;
//console.log(id)
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
                    //console.log(kdl);
                    customerTable.row($(table).closest("tr")) .remove().draw();
                    swal("Gelöscht", kdl, "success");
                }});
        });
    });



}

function deleteDeliveryPlace(id){
    var CustomerKdlTable = $('#CustomerKdlTable').DataTable();

    $('#CustomerKdlTable').on( 'click', 'tbody tr', function () {
        var table = this;
        var kdl = "";
        var test = swal({
            title: "Bist Du sicher?",
            text: "Das Du die Lieferstelle löschen willst",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-warning",
            confirmButtonText: 'Ja ich will!',
            closeOnConfirm: false //closeOnCancel: false

        }, function () {
            var kdl ="";
            $.ajax( {
                method: 'POST',
                url: '/customer/deleteDP/'+id,
                dataType: 'json',
                complete: function (data){
                    console.log('complete');
                    kdl = JSON.parse(data.responseText);
                    CustomerKdlTable.row($(table).closest("tr")) .remove().draw();
                    swal("Gelöscht", kdl, "success");
                }});

        });

    } );

}

function deleteDeliveryPlaceCheck(id){
    var dpCheckTable = $('#dpCheckTable').DataTable();
    $('#dpCheckTable').on( 'click', 'tbody tr', function () {
        var table = this;
        var test = swal({
            title: "Bist Du sicher?",
            text: "Das Du die Ablesung löschen willst",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-warning",
            confirmButtonText: 'Ja ich will!',
            closeOnConfirm: false //closeOnCancel: false

        }, function () {
            var kdl ="";
            $.ajax( {
                method: 'POST',
                url: '/customer/deleteDPCheck/'+id,
                dataType: 'json',
                complete: function (data){
                    console.log('complete');
                    kdl = JSON.parse(data.responseText);
                    dpCheckTable.row($(table).closest("tr")) .remove().draw();
                    swal("Gelöscht", kdl, "success");
                }});

        });
    });

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
            //console.log(kdls);
            var t = $('#CustomerKdlTable').DataTable();
            rows = t
                .rows()
                .remove()
                .draw();
            if (kdls.length != 0){
                console.log(testrole);

                kdls.forEach(function(obj) {
                    if (obj.checkdate){
                        console.log("*****"+obj.checkdate)
                        var cd = new Date(obj.checkdate.date);
                        monat = cd.getMonth()+1;
                        checkcell = cd.getDate()+"."+monat+"."+cd.getFullYear();
                        cdday = cd.getDate();
                    }else{
                        checkcell='';
                    }

                    if (test){
                        aktionCell = '<a href="#" class="btn btn-sm btn-icon btn-pure btn-default" onclick="getCustomerKdl('+obj.id+')" data-toggle="tooltip" data-original-title="Edit"><i class="icon md-edit success text-success font-size-20" aria-hidden="true"></i></a>' +
                            '<a href="#" class="btn btn-sm btn-icon btn-pure btn-default" onclick="deleteDeliveryPlace('+obj.id+')" data-toggle="tooltip" data-original-title="Edit"><i class="icon md-delete danger text-danger font-size-20" aria-hidden="true"></i></a>';
                    }else{
                        aktionCell = '<a href="#" class="btn btn-sm btn-icon btn-pure btn-default" onclick="getCustomerKdl('+obj.id+')" data-toggle="tooltip" data-original-title="Edit"><i class="icon md-edit success text-success font-size-20" aria-hidden="true"></i></a>' +
                            '<a href="#" class="btn btn-sm btn-icon btn-pure btn-default" onclick="deleteDeliveryPlace('+obj.id+')" data-toggle="tooltip" data-original-title="Edit"><i class="icon md-delete danger text-danger font-size-20" aria-hidden="true"></i></a>';
                    }
                    if (testrole=="ROLE_ADMIN")
                        aktionCell = aktionCell + '<a href="#" class="btn btn-sm btn-icon btn-pure btn-default open-movedelivery"  data-toggle="modal" data-target="#moveDeliveryPlaceModal" data-id="'+obj.id+'" data-toggle="tooltip" data-original-title="Verschieben"><i class="icon wb-move text-warning font-size-20" aria-hidden="true"></i></a>';

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
                        checkcell,
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

function createFCTodo(){
    //console.log("todoerstellen");
    var toUser = $('#facUserSelect').val();
    var adresse = $('#ReFirma').val()+"<br>"+$('#ReStrasse').val()+" "+$('#ReFirma').val()+"<br>"+$('#RePLZ').val()+" "+$('#ReOrt').val();
    var zaehler = $('#Zaehlernummer').val();
    var medium = $('#Medium').val();
    var doneUntil = $('#doneUntil').val();

    if (doneUntil==""){
        swal(
            'Kein Datum',
            'Ein Datum ist für das anlegen eines Todos erforderlich!',
            'error'
        )
        $('#doneUntil').focus();
        return false;
    }
    doneUntil = doneUntil.replace("/","-");
    doneUntil = doneUntil.replace("/","-");
    console.log(doneUntil);
    var DPCustomerID = $('#DPCustomerID').val();
    var dpId = $('#dpId').val();
    if (medium=="fa-flash"){
        medium = "Strom";
    }
    if (medium=="fa-fire"){
        medium = "Gas";
    }

    var jsonstring = '{"toUser":"'+toUser+'","Name" : "'+adresse+'","Zaehler":"'+zaehler+'","Art":"'+medium+'","Customer":"'+DPCustomerID+'","DP":"'+dpId+'","doneUntil":"'+doneUntil+'"}';
    var myForm = document.getElementById('deliveryPlaceForm');
    var formData = new FormData(myForm),
        result = {};

    for (var entry of formData.entries())
    {
        result[entry[0]] = entry[1];
    }
    result = JSON.stringify(result)


    $.ajax({
        url: "/customer/sendTodo",
        type: "POST",
        data:result,
        contentType: "application/json",
        complete: function (data){
            toastr['success'](JSON.parse(data.responseText));
        }
    });
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