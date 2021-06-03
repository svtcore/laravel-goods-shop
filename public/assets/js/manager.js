function calculate_cart(){
    var total = 0;
    $(".edit-product-count").each(function(index) {
        total = total + (parseInt($(this).attr('data-each')) * parseInt($(this).val()));
    });
    $("#total_price").html(" "+total+" ₴");
}

$(".edit-product-count").on('change', function () {
    if ($(this).val() <= 0){
        var id = $(this).attr("data-id");
        $("#product_block_"+id).hide();
        deleted.push(id);
        calculate_cart();
    }
    else{
        var id = $(this).attr("data-id");
        $("#product_block_"+id).css("border", "1px solid lightgrey");
        calculate_cart();
    }
});

  $(".btn-outline-primary").on('click', function () {
    $(this).next().val("0");
    var id = $(this).next().attr("data-id");
    $("#product_block_"+id).hide();
    deleted.push(id);
    calculate_cart();
});

var deleted = [];
var k = 1;
function show_deleted(){
    if (k % 2 != 0){
        for (var i = 0;i < deleted.length; i++){
            $("#product_block_"+deleted[i]).show();
            $("#product_block_"+deleted[i]).css("border", "1px solid red");
        }
        k++;
    }else{
        for (var i = 0;i < deleted.length; i++){
            $("#product_block_"+deleted[i]).hide();
        }
        k++;
    }
    
}