/**
 * Created by JetBrains PhpStorm.
 * User: bradb
 * Date: 7/11/11
 * Time: 1:54 PM
 * To change this template use File | Settings | File Templates.
 */

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
});