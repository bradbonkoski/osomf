/**
 * Created by JetBrains PhpStorm.
 * User: bradb
 * Date: 7/12/11
 * Time: 10:41 AM
 * To change this template use File | Settings | File Templates.
 */

$(function() {

    function disableBox(input, box) {
        $("#"+input).attr('disabled', true);
        $("#"+box).show();
    }

    if ($('#respProj').val().length > 0 ) {
        disableBox('respProj', 'clearProjBox');
    }

    $("#clearProjBox").click(function() {
        $("#respProj").attr('disabled', false);
        $("#respProj").val('');
        $("#clearProjBox").hide();
    });

    $("#respProj").autocomplete({
        source: function (request, response) {
            $.getJSON("/osomf/project/autocomplete", {term: request.term}, response);
        },
        minLength: 1,
        select: function (event, ui) {
            disableBox("respProj", "clearProjBox");
            $('#projId').val(ui.item.id);
        }
    });

    $('#incidentEditRevert').click(function(e){
        var incident = $("#incidentId").val();
        window.location = "/osomf/incident/view/"+incident;
    });
});