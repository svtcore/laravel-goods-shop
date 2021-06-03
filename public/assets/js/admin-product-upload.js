function readURL(input, id) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#image_'+id)
                .attr('src', e.target.result)
        };
        $("#default_"+id).val(1);
        reader.readAsDataURL(input.files[0]);
    }
}

function resetUploadButton(id){
    $("#file_image_"+id).val('');
    $("#default_"+id).val(0);
    $("#image_"+id).attr('src', '/storage/assets/img/products/no-image.jpg');
}