
$(function() {

    if ($('#ciStatus').val().length > 0 ) {
        disableBox("ciStatus", "clearStatusBox");
    }

    if ($('#ciType').val().length > 0 ) {
        disableBox('ciType', 'clearTypeBox');
    }

    if ($("#ciLoc").val().length > 0 ) {
        disableBox('ciLoc', 'clearLocBox');
    }

    if ($('#ciProj').val().length > 0 ) {
        disableBox('ciProj', 'clearProjBox');
    }

    if ($('#ciPhy').val().length > 0 ) {
        disableBox('ciPhy','clearPhyBox');
    }

    if ($('#ciNet').val().length > 0 ) {
        disableBox('ciNet','clearNetBox');
    }

    if ($('#ciOwner').val().length > 0 ) {
        $('#ownerType').attr('disabled', true);
        disableBox('ciOwner','clearOwnerBox');
    }

    function disableBox(input, box) {
        $("#"+input).attr('disabled', true);
        $("#"+box).show();
    }

    $("#ciStatus").autocomplete({
        source: function (request, response) {
            $.getJSON("/osomf/status/autocomplete", {term: request.term}, response);
        },
        minLength: 2,
        select: function( event, ui ) {
            disableBox("ciStatus", "clearStatusBox");
            $("#statusId").val(ui.item.id);
        }
    });

    $("#ciType").autocomplete({
        source: function (request, response) {
            $.getJSON("/osomf/type/autocomplete", {term: request.term}, response);
        },
        minLength: 1,
        select: function( event, ui) {
            disableBox("ciType","clearTypeBox");
            $('#typeId').val(ui.item.id);
        }
    });

    $("#ciLoc").autocomplete({
        source: function (request, response) {
            $.getJSON("/osomf/location/autocomplete", {term: request.term}, response);
        },
        minLength: 2,
        select: function( event, ui) {
            disableBox("ciLoc", "clearLocBox");
            $('#locId').val(ui.item.id);
        }
    });

    $("#ciProj").autocomplete({
        source: function (request, response) {
            $.getJSON("/osomf/project/autocomplete", {term: request.term}, response);
        },
        minLength: 1,
        select: function (event, ui) {
            disableBox("ciProj", "clearProjBox");
            $('#projId').val(ui.item.id);
        }
    });

    $("#ciPhy").autocomplete({
        source: function (request, response) {
            $.getJSON("/osomf/asset/autocomplete", {term: request.term}, response);
        },
        minLength: 2,
        select: function (event, ui) {
            disableBox("ciPhy", "clearPhyBox");
            $('#phyId').val(ui.item.id);

        }
    });

    $("#ciNet").autocomplete({
        source: function (request, response) {
            $.getJSON("/osomf/asset/autocomplete", {term: request.term}, response);
        },
        minLength: 2,
        select: function (event, ui) {
            disableBox("ciNet", "clearNetBox");
            $('#netId').val(ui.item.id);
        }
    });

    $('#ownerType').change(function() {
       if ($("#ownerType").val() == 'GROUP') {
           $('#ciOwner').autocomplete("option", "source",
               function (request, response) { $.getJSON("/osomf/group/autocomplete", {term: request.term}, response)});
       } else {
           $('#ciOwner').autocomplete("option", "source",
               function (request, response) { $.getJSON("/osomf/user/autocomplete", {term: request.term}, response)});
       }
    });

    $("#ciOwner").autocomplete({
        source: function (request, response) {
            $.getJSON("/osomf/user/autocomplete", {term: request.term}, response);
        },
        minLength: 1,
        select: function (event, ui) {
            disableBox("ciOwner", "clearOwnerBox");
            $('#ownerType').attr('disabled', true);
            $('#ownerId').val(ui.item.id);
            $('#ownerTypeVal').val($('#ownerType').val());
        }
    });

    $("#clearOwnerBox").click(function() {
        $("#ownerType").attr("disabled", false);
        $("#ciOwner").attr('disabled',false).val('');
        $('#clearOwnerBox').hide();
    });

    $("#clearStatusBox").click(function() {
        $("#ciStatus").attr('disabled', false);
        $("#ciStatus").val('');
        $("#clearStatusBox").hide();
    });

    $("#clearTypeBox").click(function() {
        $("#ciType").attr('disabled', false);
        $('#ciType').val('');
        $("#clearTypeBox").hide();
    });

    $("#clearLocBox").click(function() {
        $("#ciLoc").attr('disabled', false);
        $('#ciLoc').val('');
        $("#clearLocBox").hide();
    });

    $("#clearProjBox").click(function() {
        $("#ciProj").attr('disabled', false);
        $("#ciProj").val('');
        $("#clearProjBox").hide();
    });

    $("#clearPhyBox").click(function() {
        $("#ciPhy").attr('disabled', false);
        $("#ciPhy").val('');
        $("#clearPhyBox").hide();
    });

    $("#clearNetBox").click(function() {
        $("#ciNet").attr('disabled',false);
        $("#ciNet").val('');
        $("#clearNetBox").hide();
    })
});