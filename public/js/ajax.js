$("#reg").submit( function (event) {
    event.preventDefault();
    $.ajax({
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        url: "/up",
        data: new FormData(this),
        processData: false,
        contentType: false,
    }).done(function (data) {
        console.log(data);
        let user = data.user,
            msg = data.msg;
        console.log(data);
        let html = "";
        if (msg == "Вы зарегистрированы") {
            html = '<div class="not btn btn-success">' + msg + "</div>";
        } else {
            html = '<div class="not btn btn-danger">' + msg + "</div>";
        }
        $("#reg .btn-primary").after(html);
        setTimeout(() => {
            $("#reg .not").css("transition", "1s");
            $("#reg .not").css("opacity", "0");
        }, 3000);
        setTimeout(() => {
            $("#reg .not").remove();
        }, 4000);

        $(".nav-link.active").html(user.name);
        $(".log.d-flex").html(
            '<div id="logout" class="btn btn-outline-danger ms-3">Выход</div>'
        );
    });
});

$("#auth").submit( function (event) {
    event.preventDefault();
    // console.log(new FormData(this));
    $.ajax({
        method: "POST",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: "/in",
        data: new FormData(this),
        processData: false,
        contentType: false,
    }).done(function (data) {
        location.reload();

        // let user = data.user

        // $(".nav-link.active").html(user.name);
        // $(".log.d-flex").html(
        //     '<div id="logout" class="btn btn-outline-danger ms-3">Выход</div>'
        // );

        // $(".list").load(document.URL + " .list");
    });
});

// $("nav").on('click', '#logout', function() {
//     $.ajax({
//         method: "GET",
//         headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
//         url: "/logout",
//     })
//     .done(function (user) {
//         $('.nav-link.active').html('Аноним');
//         let html = `
//             <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#upModal">Регистрация</button>
//             <button class="btn btn-outline-primary ms-3" data-bs-toggle="modal" data-bs-target="#inModal">Вход</button>`
//         $(".log.d-flex").html(html);
//         $(".options").remove();
//     });
// });

$("#msg").on('submit', function(event) {
    event.preventDefault();
    $.ajax({
        method: "POST",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: "/msg",
        data: new FormData(this),
        processData: false,
        contentType: false,
    })
    .done(function (data) {
        let html = ''
        if (data.role == 'auth') {
            html = `
            <div data-id="${data.id}" class="list_item bg-dark row mb-3 p-1">
                <div class="info text-warning">${data.time} <span class="status"></span></div>
                    <div class="options d-flex text-light">
                        <div class="delete">удалить</div>
                    </div>
                <div contenteditable="true" class="text text-light">${data.text}</div>
            </div>`
        } else {
            html = `
            <div data-id="${data.id}" class="list_item bg-dark row mb-3 p-1">
                <div class="info text-warning">${data.time}</div>
                <div class="text text-light">${data.text}</div>
            </div>`
        }
        
        $('.list .list_item:first').before(html);
        $(".list .list_item:last").remove();
        // $("#pagination").load(document.URL + " #pagination");
        $.ajax({
            method: "POST",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "/email",
            data: `author=${data.author}&text=${data.text}&time=${data.time}`
        })
    });
});

$(".list").on('click', '.delete', function () {
    let id = $(this).parents('.list_item').attr('data-id'),
        list_item = $(this).parents('.list_item')
    $.ajax({
        method: "delete",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: "/delete",
        data: `id=${id}`
    })
    .done(function (result) {
        console.log(result);
        if (result == 'ok') {
            list_item.remove();
            // $("#list").load(document.URL + " #list");
            // $("#pagination").load(document.URL + " #pagination");
        }
    });
})


$(".list").on('keyup', '.text', function () {
    let id = $(this).parents('.list_item').attr('data-id'),
        text = $(this).text(),
        block = $(this).siblings('.info').find('span');
    $.ajax({
        method: "put",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: "/update",
        data: `id=${id}&text=${text}`
    })
    .done(function (result) {
        console.log(result);
        if (result == 'save') {
            block.text(' (Ред.)');
        }
    });
})
