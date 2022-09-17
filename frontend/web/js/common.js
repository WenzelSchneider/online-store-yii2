window.addEventListener("scroll", function() {
    if (window.scrollY > 200) {
        document.getElementsByTagName("header")[0].classList.add("scrolled");
    } else {
        document.getElementsByTagName("header")[0].classList.remove("scrolled");
    }
});

$(function() {
    $("[data-visible]").hide();


    $(".js-menu").mouseenter(function() {
        var id = $(this).attr("data-menu"),
            content = $('.js-menu-content[data-menu="' + id + '"]');
        $(".js-menu-trigger.active").removeClass("active"); // 1
        $(this).addClass("active"); // 2

        $(".js-menu-content.active").removeClass("active"); // 3
        content.addClass("active"); // 4

        cw = content.width();
        pw = $("#mid-menu").width();

        if (cw >= pw) {
            content.css({ left: "-20px" });
        } else if (cw < pw) {
            pl = $(this).offset().left - $("#mid-menu").offset().left;

            if (cw + pl >= pw) {
                content.css({ right: "0" });
            } else if (cw + pl < pw) {
                content.css({ left: pl - 20 });
            }
        }
    });
    $(".lk-order-prod").on("click", function() {
        var id = $(this).attr("data-order");
        $(".lk-order-row").addClass("d-none-h");
        $('.lk-order-row[data-order="' + id + '"]').removeClass("d-none-h");
        console.log(id);
    });
    $(".mid-menu__clear").mouseenter(function() {
        $(".js-menu-trigger.active").removeClass("active");
        $(".js-menu-content.active").removeClass("active");
    });

    $(".product").css({ height: $(".product").height() });

    $(".add-to-basket").on("click", function() {
        var prod = $(this).attr("data-prod"),
            cnt = $(this).attr("data-cnt");
        var data = { id: prod, count: cnt };
        $.get("/frontend/web/basket/add", data, myCallback);
    });
    $(".count-panel__sub").on("click", function() {
        var pid = $(this).attr("data-id");
        if (parseInt($('.count-panel__digit[data-id="' + pid + '"]').val()) > 1) {
            $('.count-panel__digit[data-id="' + pid + '"]').val(
                parseInt($('.count-panel__digit[data-id="' + pid + '"]').val()) - 1
            );
        }
        $('.add-to-basket[data-prod="' + pid + '"]').attr(
            "data-cnt",
            parseInt($('.count-panel__digit[data-id="' + pid + '"]').val())
        );
        console.log(pid);
    });
    $(".count-panel__add").on("click", function() {
        var pid = $(this).attr("data-id");
        $('.count-panel__digit[data-id="' + pid + '"]').val(
            1 + parseInt($('.count-panel__digit[data-id="' + pid + '"]').val())
        );
        $('.add-to-basket[data-prod="' + pid + '"]').attr(
            "data-cnt",
            parseInt($('.count-panel__digit[data-id="' + pid + '"]').val())
        );
        console.log(pid);
    });
    $(".count-panel__digit").on("change", function() {
        var pid = $(this).attr("data-id");
        $('.add-to-basket[data-prod="' + pid + '"]').attr(
            "data-cnt",
            parseInt($('.count-panel__digit[data-id="' + pid + '"]').val())
        );
        console.log(pid);
    });

    $(".js-filter>span").on("click", function() {
        var id = $(this).attr("data-filter"),
            content = $('.js-filter-content[data-filter="' + id + '"]');
        if ($(this).hasClass("active")) {
            $(".js-filter-trigger.active").removeClass("active");
        } else {
            $(".js-filter-trigger.active").removeClass("active");
            $(this).addClass("active"); // 2
        }

        if (!$(this).hasClass("active")) {
            $(".js-filter-content.active").removeClass("active");
        } else {
            $(".js-filter-content.active").removeClass("active");
            content.addClass("active"); // 4
        }
    });
    $("input#searchline").on("keydown", function(e) {
        if (e.keyCode === 13) {
            searchGood(1);
        }
    });

    $("#confiz").validate({
        rules: {
            email: {
                required: true,
                email: true,
                remote: {
                    url: "/frontend/web/user/dublicate",
                },
            },
            contactname: {
                required: true,
                minlength: 3,
            },
            telephone: {
                required: true,
                minlength: 11,
                maxlength: 11,
                digits: true,
                remote: {
                    url: "/frontend/web/user/dublicate",
                },
            },
            password: {
                required: true,
                minlength: 6,
            },
        },
        messages: {
            email: {
                required: "Это поле обязательно для заполнения",
                email: "Введите Email example@domen.ru",
                remote: "Пользователь с таким электронным адресом уже существует.",
            },
            contactname: {
                required: "Это поле обязательно для заполнения",
                minlength: "Ваше имя должно быть минимум {0} символов",
            },
            telephone: {
                required: "Это поле обязательно для заполнения",
                minlength: "Номер должен быть минимум {0} символов",
                maxlength: "Номер должен быть максимум {0} символов",
                digits: "Только цифры",
                remote: "Пользователь с таким номером уже существует.",
            },
            password: {
                required: "Это поле обязательно для заполнения",
                minlength: "Пароль должен быть минимум {0} символов",
            },
        },
    });

    $("#conjur").validate({
        rules: {
            companyname: {
                required: true,
            },
            inn: {
                required: true,
                digits: true,
            },
            email: {
                required: true,
                email: true,
                remote: {
                    url: "/frontend/web/user/dublicate",
                },
            },
            contactname: {
                required: true,
                minlength: 3,
            },
            telephone: {
                required: true,
                minlength: 11,
                maxlength: 11,
                digits: true,
                remote: {
                    url: "/frontend/web/user/dublicate",
                },
            },
            password: {
                required: true,
                minlength: 6,
            },
        },
        messages: {
            companyname: {
                required: "Это поле обязательно для заполнения",
            },
            inn: {
                required: "Это поле обязательно для заполнения",
                digits: "Только цифры",
            },
            email: {
                required: "Это поле обязательно для заполнения",
                email: "Введите Email example@domen.ru",
                remote: "Пользователь с таким электронным адресом уже существует.",
            },
            contactname: {
                required: "Это поле обязательно для заполнения",
                minlength: "Ваше имя должно быть минимум {0} символ(а/ов)",
            },
            telephone: {
                required: "Это поле обязательно для заполнения",
                minlength: "Номер должен быть минимум {0} символ(а/ов)",
                maxlength: "Номер должен быть максимум {0} символ(а/ов)",
                digits: "Только цифры",
                remote: "Пользователь с таким номером уже существует.",
            },
            password: {
                required: "Это поле обязательно для заполнения",
                minlength: "Пароль должен быть минимум {0} символ(а/ов)",
            },
        },
    });

    $("#oconfiz").validate({
        rules: {
            email: {
                required: true,
                email: true,
                remote: {
                    url: "/frontend/web/user/dublicate",
                },
            },
            contactname: {
                required: true,
                minlength: 3,
            },
            telephone: {
                required: true,
                minlength: 11,
                maxlength: 12,
                digits: true,
                remote: {
                    url: "/frontend/web/user/dublicate",
                },
            },
            password: {
                required: true,
                minlength: 6,
            },
        },
        messages: {
            email: {
                required: "Это поле обязательно для заполнения",
                email: "Введите Email example@domen.ru",
                remote: "Пользователь с таким электронным адресом уже существует.",
            },
            contactname: {
                required: "Это поле обязательно для заполнения",
                minlength: "Ваше имя должно быть минимум {0} символов",
            },
            telephone: {
                required: "Это поле обязательно для заполнения",
                minlength: "Номер должен быть минимум {0} символов",
                maxlength: "Номер должен быть максимум {0} символов",
                digits: "Только цифры",
                remote: "Пользователь с таким номером уже существует.",
            },
            password: {
                required: "Это поле обязательно для заполнения",
                minlength: "Пароль должен быть минимум {0} символов",
            },
        },
    });

    $("#oconjur").validate({
        rules: {
            companyname: {
                required: true,
            },
            inn: {
                required: true,
                digits: true,
            },
            email: {
                required: true,
                email: true,
                remote: {
                    url: "/frontend/web/user/dublicate",
                },
            },
            contactname: {
                required: true,
                minlength: 3,
            },
            telephone: {
                required: true,
                minlength: 11,
                maxlength: 12,
                digits: true,
                remote: {
                    url: "/frontend/web/user/dublicate",
                },
            },
            password: {
                required: true,
                minlength: 6,
            },
        },
        messages: {
            companyname: {
                required: "Это поле обязательно для заполнения",
            },
            inn: {
                required: "Это поле обязательно для заполнения",
                digits: "Только цифры",
            },
            email: {
                required: "Это поле обязательно для заполнения",
                email: "Введите Email example@domen.ru",
                remote: "Пользователь с таким электронным адресом уже существует.",
            },
            contactname: {
                required: "Это поле обязательно для заполнения",
                minlength: "Ваше имя должно быть минимум {0} символ(а/ов)",
            },
            telephone: {
                required: "Это поле обязательно для заполнения",
                minlength: "Номер должен быть минимум {0} символ(а/ов)",
                maxlength: "Номер должен быть максимум {0} символ(а/ов)",
                digits: "Только цифры",
                remote: "Пользователь с таким номером уже существует.",
            },
            password: {
                required: "Это поле обязательно для заполнения",
                minlength: "Пароль должен быть минимум {0} символ(а/ов)",
            },
        },
    });

    $("#delivery").validate({
        rules: {
            city: {
                minlength: 3,
            },
            street: {
                minlength: 3,
            },
            numh: {
                minlength: 1,
            },
        },
        messages: {
            city: {
                required: "Это поле обязательно для заполнения",
                remote: "Пользователь с таким электронным адресом уже существует",
            },
            street: {
                required: "Это поле обязательно для заполнения",
                minlength: "Название улицы должно быть минимум {0} символ(а/ов)",
            },
            numh: {
                required: "Это поле обязательно для заполнения",
                minlength: "Номер должен быть минимум {0} символ(а/ов)",
                maxlength: "Номер должен быть максимум {0} символ(а/ов)",
                remote: "Пользователь с таким номером уже существует",
            },
        },
    });

    toggleForm();

    $('input:radio[name="contact"]').change(function() {
        toggleForm();
    });
});

function myCallback(returnedData) {
    console.log(returnedData);
    Toast.add({
        text: 'Товар добавлен в корзину <a href="/frontend/web/basket/index"> Перейти в корзину</a>',
        color: "#e2e0de",
        autohide: true,
        delay: 5000,
    });
    var i = 0;
    $.each(returnedData["products"], function(key, val) {
        i++;
    });
    $(".icon-top__bag_count").html(i);
}

function searchGood(n) {
    var valu = "";
    if (n == 1) {
        valu = $("#searchline").val();
    } else if (n == 2) {
        valu = $("#searchlinemob").val();
    }
    if (valu !== "") {
        window.location = "/frontend/web/product/?q=" + valu;
    } else if (valu === "") {
        alert("Введите наименование товара или его код для поиска");
    }
}

function sendAjaxForm(ajax_form, url) {
    if ($("#" + ajax_form).valid()) {
        $.ajax({
            url: url, //url страницы (action_ajax_form.php)
            type: "POST", //метод отправки
            dataType: "html", //формат данных
            data: $("#" + ajax_form).serialize(), // Сеарилизуем объект
            success: function(response) {
                //Данные отправлены успешно
                console.log(response);
                //result = $.parseJSON(response);
                return true;
            },
            error: function(response) {
                // Данные не отправлены
                console.log("Ошибка. Данные не отправлены.");
                return false;
            },
        });
    }
}

function toggleForm() {
    //   if ($("#trifiz").prop("checked")) {
    //     $("#confiz").show();
    //     $("#conjur").hide();
    //   } else if ($("#trijur").prop("checked")) {
    //     $("#confiz").hide();
    //     $("#conjur").show();
    //   } else {
    //     $("#confiz").show();
    //     $("#confiz").hide();
    //   }

    if ($("#deliveri-to").prop("checked")) {
        $(".deliveri-to").addClass("required");
    } else {
        $(".deliveri-to").removeClass("required");
    }

    //   if ($("#otrifiz").prop("checked")) {
    //     $("#oconfiz").show();
    //     $("#oconjur").hide();
    //   } else if ($("#otrijur").prop("checked")) {
    //     $("#oconfiz").hide();
    //     $("#oconjur").show();
    //   } else {
    //     $("#oconfiz").show();
    //     $("#oconfiz").hide();
    //   }
    if ($("#odeliveri-to").prop("checked")) {
        $(".deliveri-to").addClass("required");
    } else {
        $(".deliveri-to").removeClass("required");
    }
}

function completeOrder() {
    $.ajax({
        url: "/frontend/web/order/payment-method", //url страницы (action_ajax_form.php)
        type: "POST", //метод отправки
        dataType: "html", //формат данных
        data: $("#payment").serialize(), // Сеарилизуем объект
        success: function(response) {
            //Данные отправлены успешно
            if (response == "1") {
                $(location).attr("href", "/frontend/web/order/complete");
                console.log(response);
            } else {
                alert("Не все заполнено верно!!");
            }
        },
        error: function(response) {
            // Данные не отправлены
            alert("Проблема отправки!!");
        },
    });
}

function completeDeliveryOrder() {
    $.ajax({
        url: "/frontend/web/order/product-method", //url страницы (action_ajax_form.php)
        type: "POST", //метод отправки
        dataType: "html", //формат данных
        data: $("#delivery").serialize(), // Сеарилизуем объект
        success: function(response) {
            //Данные отправлены успешно
            if (response == "1") {
                $(location).attr("href", "/frontend/web/order/payment");
                //console.log(response);
            } else {
                alert("Не все заполнено верно!!");
            }
        },
        error: function(response) {
            // Данные не отправлены
            alert("Проблема отправки!!");
        },
    });
}

function completeContactOrder() {
    console.log('complete');
    var d = 0;
    if ($("#otrifiz").prop("checked")) {
        $.ajax({
            url: "/frontend/web/order/contact-information-private", //url страницы (action_ajax_form.php)
            type: "POST", //метод отправки
            dataType: "html", //формат данных
            data: $("#oconfiz").serialize(), // Сеарилизуем объект
            success: function(response) {
                //Данные отправлены успешно
                if (response == "1") {
                    $(location).attr("href", "/frontend/web/order/delivery");
                } else {
                    alert("Не все заполнено верно!!");
                }
            },
            error: function(response) {
                // Данные не отправлены
                alert("Проблема отправки!!");
            },
        });
    } else if ($("#otrijur").prop("checked")) {
        $.ajax({
            url: "/frontend/web/order/contact-information-legal", //url страницы (action_ajax_form.php)
            type: "POST", //метод отправки
            dataType: "html", //формат данных
            data: $("#oconjur").serialize(), // Сеарилизуем объект
            success: function(response) {
                //Данные отправлены успешно
                if (response == "1") {
                    $(location).attr("href", "/frontend/web/order/delivery");
                    //console.log(response);
                } else {
                    alert("Не все заполнено верно!!");
                }
            },
            error: function(response) {
                // Данные не отправлены
                alert("Проблема отправки!!");
            },
        });
    } else if ($("#qotrifiz").prop("checked")) {
        $.ajax({
            url: "/frontend/web/order/contact-information-private", //url страницы (action_ajax_form.php)
            type: "POST", //метод отправки
            dataType: "html", //формат данных
            data: $("#oconfiz").serialize(), // Сеарилизуем объект
            success: function(response) {
                //Данные отправлены успешно
                if (response == "1") {
                    $(location).attr("href", "/frontend/web/order/delivery");
                    //console.log(response);
                } else {
                    alert("Не все заполнено верно!!");
                }
            },
            error: function(response) {
                // Данные не отправлены
                alert("Проблема отправки!!");
            },
        });
    } else if ($("#qotrijur").prop("checked")) {
        $.ajax({
            url: "/frontend/web/order/contact-information-legal", //url страницы (action_ajax_form.php)
            type: "POST", //метод отправки
            dataType: "html", //формат данных
            data: $("#oconjur").serialize(), // Сеарилизуем объект
            success: function(response) {
                //Данные отправлены успешно
                if (response == "1") {
                    $(location).attr("href", "/frontend/web/order/delivery");
                    //console.log(response);
                } else {
                    alert("Не все заполнено верно!!");
                }
            },
            error: function(response) {
                // Данные не отправлены
                alert("Проблема отправки!!");
            },
        });
    }
}