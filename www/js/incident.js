/**
 * Created by JetBrains PhpStorm.
 * User: bradb
 * Date: 7/11/11
 * Time: 1:54 PM
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

    // Load dialog on click
	$('#impactAddModal').click(function (e) {
	    $('#AddImpactModal').modal();

		return false;
	});

    $('#worklogAddModal').click(function(e) {
        $('#AddWorklogModal').modal();
        return false;
    });

    $('#addWorklogButton').click(function(e) {
        //alert("Help");
        var incident = $("#incidentId").val();
        var txt = $("#AddWorkglogText").val();
        $.ajax({
            type: "GET",
            url: "/osomf/incident/addWorkLog",
            data: "id="+incident+"&text="+txt,
            success: function(html) {
                $('#incidentWorklogTable > tbody:last').append(html);
                $.modal.close();
            }
        });
    });
});