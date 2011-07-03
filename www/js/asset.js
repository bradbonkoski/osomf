
$(function() {
    var ownerUrl = '';

    function disableBox(input, box) {
        $("#"+input).attr('disabled', true);
        $("#"+box).show();
    }

    $("#ciStatus").autocomplete({
        source: "/osomf/status/autocomplete",
        minLength: 2,
        select: function( event, ui ) {
            disableBox("ciStatus", "clearStatusBox");
        }
    });

    $("#ciType").autocomplete({
        source: "/osomf/type/autocomplete",
        minLength: 1,
        select: function( event, ui) {
            disableBox("ciType","clearTypeBox");
        }
    });

    $("#ciLoc").autocomplete({
        source: "/osomf/location/autocomplete",
        minLength: 2,
        select: function( event, ui) {
            disableBox("ciLoc", "clearLocBox");
        }
    });

    $("#ciProj").autocomplete({
        source: "/osomf/project/autocomplete",
        minLength: 1,
        select: function (event, ui) {
            disableBox("ciProj", "clearProjBox");
        }
    });

    $("#ciPhy").autocomplete({
        source: "/osomf/asset/autocomplete",
        minLength: 2,
        select: function (event, ui) {
            disableBox("ciPhy", "clearPhyBox");
        }
    });

    $("#ciNet").autocomplete({
        source: "/osomf/asset/autocomplete",
        minLength: 2,
        select: function (event, ui) {
            disableBox("ciNet", "clearNetBox");
        }
    });

    $('#ownerType').change(function() {
       //ownerUrl = getOwnerType();
       if ($("#ownerType").val() == 'GROUP') {
           $('#ciOwner').autocomplete("option", "source", "/osomf/group/autocomplete");
       } else {
           $('#ciOwner').autocomplete("option", "source", "/osomf/user/autocomplete");
       }
    });

    $("#ciOwner").autocomplete({
        source: "/osomf/user/autocomplete",
        minLength: 1,
        select: function (event, ui) {
            disableBox("ciOwner", "clearOwnerBox");
            $('#ownerType').attr('disabled', true);
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