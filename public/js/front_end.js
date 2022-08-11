$('document').ready(function() {
    var params = (new URL(document.location)).searchParams;
    var pondId = params.get("pondId");
    var aquaId = params.get("aquaId");
    if (pondId == null){
        pondId = "";
    }
    if (aquaId == null){
        aquaId = "";
    }
    //console.log(pondId);
    $(".f1 input").click(function () {
        $('.f1 input').prop('checked', false);
        $(this).prop('checked', true);
        if (pondId || aquaId){
            if ($("#rdWater").is(":checked")) {
                location.href = "viewPond?pondId=" + pondId + '&aquaId=' + aquaId;
            }
            if ($("#rdShrimp").is(":checked")) {
                location.href = "viewShrimpMeasure?pondId=" + pondId + '&aquaId=' + aquaId;
            }
            if ($("#rdFeeding").is(":checked")) {
                location.href = "viewShrimpFeed?pondId=" + pondId + '&aquaId=' + aquaId;
            }
            if ($("#rdBalance").is(":checked")){
                location.href = "report_pond?&now=1&pond_id=" + pondId + '&pondId=' + pondId;
            }
        }
    });
    $('#btnShrimpMigration').click(function () {
        location.href = "shrimpMigration?pondId=" + pondId + '&aquaId=' + aquaId;
    });
    $('.ftDate p').click(function () {
        var aquaId = $(this).attr('data-id');
        if (aquaId){
            $("#aquaId").val(aquaId);
            $('form[name=ftDate]').submit();
        }
    });
});

function splitDecimalStr(numberStr, decimals){
    var ret = new Array(2);
    if (numberStr !== null && numberStr !== ""){
        var splittedItems = numberStr.toString().split(".");
        ret[0] = splittedItems[0];
        if (splittedItems.length > 1){
            if (splittedItems[1].length < decimals){
                ret[1] = splittedItems[1] + '0'.repeat(decimals - splittedItems[1].length);
            }else if (splittedItems[1].length == decimals){
                ret[1] = splittedItems[1];
            }else{
                ret[1] = splittedItems[1].substring(0, decimals);
            }
        }else{
            ret[1] = '';
        }
    }else{
        ret[0] = '-';
        ret[1] = '';
    }

    return ret;
}