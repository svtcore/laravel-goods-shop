$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(function(){

$('.search_result').blur(function(){
    $(".search_result").fadeOut();
});
//Живой поиск
$('.who').bind("change keyup input click", function() {
    if (this.value.length == 0) $(".search_result").fadeOut();
    if(this.value.length >= 5){
        $.ajax({
            url: "../search", // указываем URL
            method: "GET",            // HTTP метод, по умолчанию GET
            data: {query: this.value},         // данные, которые отправляем на сервер
            dataType: "html",         // тип данных загружаемых с сервера
            success: function (data) {
                var arr = $.parseJSON(data);
                var text = "";
                console.log(arr);
                for(var i = 0; i < arr.length; i++) {
                    text = text + "<li><a href='../"+arr[i]['order_id']+"/show'>"+arr[i]['order_phone']+" / "+arr[i]['created_at']+"</a></li>";
                 }
                $(".search_result").html(text).fadeIn(); //Выводим полученые данные в списке
            }
        });
    }
})
    
$(".search_result").hover(function(){
    $(".who").blur(); //Убираем фокус с input
})
    
//При выборе результата поиска, прячем список и заносим выбранный результат в input
$(".search_result").on("click", "li", function(){
    s_user = $(this).text();
    //$(".who").val(s_user).attr('disabled', 'disabled'); //деактивируем input, если нужно
    $(".search_result").fadeOut();
})
})