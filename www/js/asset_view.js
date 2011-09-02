$(function() {

    $('#AddAssetAttributes').hide();
    
    $('#attributeModal').click(function(e) {
        $('#AddAssetAttributes').modal({
                onShow: function (dialog) {
                    $('#AssetAttribute', dialog.data[0]).autocomplete({
                        source: function (request, response) {
                            $.getJSON("/osomf/attributes/autocomplete", {term: request.term}, response)
                        },
                        minLength: 1,
                        select: function (event, ui) {
                            $('#AssetAttribute').attr('disabled', true);
                            $('#attrId').val(ui.item.id);
                        }
                    });
                }
        });
    });

//    $("#AssetAttribute").autocomplete({
//        source: function (request, response) {
//            $.getJSON("/osomf/attributes/autocomplete", {term: request.term}, response);
//        }
//        //  minLength: 1,
//        //multiple: true,
//        /*
//        select: function (event, ui) {
//            $('#AssetAttribute').attr('disabled', true);
//            $('#attrId').val(ui.item.id);
//        }
//        */
//    });

     $('#subAddAssetAttribute').click(function(e) {
        var asset =  $("#ciid").val();
        var attrId = $("#attrId").val();
        var attrVal = $('#AssetAttrValue').val();
        var data = "asset="+asset+"&attrId="+attrId+"&attrVal="+attrVal;
        //alert(data);
        $.ajax({
            type: "GET",
            url: "/osomf/asset/addAttribute",
            data: data,
            success: function(html) {
                $('#assetAttributes').append(html);
                $('#AssetAttribute').attr('disabled', false);
                $.modal.close();
            }
        });
    });
});