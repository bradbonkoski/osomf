/**
 * Created by JetBrains PhpStorm.
 * User: bradb
 * Date: 7/11/11
 * Time: 1:54 PM
 * To change this template use File | Settings | File Templates.
 */

function removeImpact(impactId) {
    var incident =  $("#incidentId").val();
    //alert(incident);
    var data = "incidentId="+incident+"&impactId="+impactId
    $.ajax({
        type: "GET",
        url: "/osomf/incident/removeImpact",
        data: data,
        success: function(html) {
            $('#impactRow'+impactId).remove();
        }
    });
}

$(function() {
    // Load dialog on click
	$('#impactAddModal').click(function (e) {
	    $('#AddImpactModal').modal();
		return false;
	});

    $('#worklogAddModal').click(function(e) {
        $('#AddWorklogModal').modal();
        return false;
    });

    $('#changeIncidentStatus').click(function(e) {
        $('#StatusChangeModal').modal();
        return false;   
    });

    $('#btnChangeStatus').click(function (e) {
        var incident =  $("#incidentId").val();
        var newStatus = $('#stausChangeVal').val();
        var reason = $('#reason').val();
        var params = "id="+incident+"&newStatus="+newStatus+"&reason="+reason;
        //alert(params);
        $.ajax({
            type: "GET",
            url: "/osomf/incident/statusChange",
            data: params,
            success: function(html) {
                $('#incidentStatus').text(html);
                $.modal.close();
            }
        })
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

    $('#newImpact').change(function() {
        $("#impactedEntity").val('');
        if ($("#newImpact").val() == 'asset') {
            $('#impactedEntity').autocomplete("option", "source",
                function (request, response) { $.getJSON("/osomf/asset/autocomplete", {term: request.term}, response)});
        } else {
            //alert('project');
            $('#impactedEntity').autocomplete("option", "source",
                function (request, response) { $.getJSON("/osomf/project/autocomplete", {term: request.term}, response)});
        }
    });

    $("#impactedEntity").autocomplete({
        source: function (request, response) {
            $.getJSON("/osomf/asset/autocomplete", {term: request.term}, response);
        },
        minLength: 1,
        select: function (event, ui) {
            //disableBox("ciOwner", "clearOwnerBox");
            $('#impactedEntity').attr('disabled', true);
            $('#newImpact').attr('disabled', true);
            $('#entityId').val(ui.item.id);
        }
    });

    $('#btnImpactAdd').click(function(e) {
        var incident =  $("#incidentId").val();
        var impactType = $("#newImpact").val();
        var id = $('#entityId').val();
        var desc = $('#impactDesc').val();
        var sev = $('#impactSev').val();
        var data = "incident="+incident+"&type="+impactType+"&entity="+id+"&desc="+desc+"&sev="+sev;
        //alert(data);
        $.ajax({
            type: "GET",
            url: "/osomf/incident/addImpact",
            data: data,
            success: function(html) {
                $('#incidentImpactsTable > tbody:last').append(html);
                $.modal.close();
            }
        });
    });
});