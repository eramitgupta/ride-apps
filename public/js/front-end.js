// login admin
$(document).ready(function () {
    $("#login").on("click", function () {
        let btn = document.querySelector('#login').textContent;
        formData = new FormData(document.forms.namedItem("FormData"));
        let username = $("#username").val();
        let password = $("#password").val();
        if (username != "" && password != "") {
            $.ajax({
                url: base_url + "login/authenticate",
                type: "POST",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $("#login").html('Please Wait...');
                },
                success: function (dataResult) {
                    var dataResult = JSON.parse(dataResult);
                    if (dataResult.statusCode == 200) {
                        $("#login").attr("disabled", "disabled");
                        sAlert('success', dataResult.msg, dataResult.url);
                        document.getElementById("FormData").reset();
                    } else if (dataResult.statusCode == 201) {
                        sAlert('error', dataResult.msg);
                    }
                },
                complete: function () {
                    document.querySelector('#login').textContent = btn;
                },
            });
        } else {
            sAlert('error', 'All Fields Required');
        }
    });
});

$(document).ready(function () {
    $("#passwordReset").on("click", function () {
        let btn = document.querySelector('#passwordReset').textContent;
        formData = new FormData(document.forms.namedItem("FormData"));
        let username = $("#username").val();
        if (username != "") {
            $.ajax({
                url: base_url + "login/forgot_password_check",
                type: "POST",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $("#passwordReset").html('Please Wait...');
                },
                success: function (dataResult) {
                    var dataResult = JSON.parse(dataResult);
                    if (dataResult.statusCode == 200) {
                        $("#passwordReset").attr("disabled", "disabled");
                        sAlert('success', dataResult.msg, dataResult.url);
                        document.getElementById("FormData").reset();
                    } else if (dataResult.statusCode == 201) {
                        sAlert('error', dataResult.msg);
                    }
                },
                complete: function () {
                    document.querySelector('#passwordReset').textContent = btn;
                },
            });
        } else {
            sAlert('error', 'Username Required!');
        }
    });
});



$(document).ready(function () {
    $("#resent").on("click", function () {
        let btn = document.querySelector('#resent').textContent;
        let email = $("#email").val();
        let remaining = 'remaining';
        $.ajax({
            url: base_url + "login/resent_otp",
            type: "POST",
            delay: 0,
            data: {
                email: email,
            },
            cache: false,
            beforeSend: function () {
                $("#resent").html('Please Wait...');
            },
            success: function (dataResult) {
                var dataResult = JSON.parse(dataResult);
                if (dataResult.statusCode == 200) {
                    $("#resent").attr("disabled", "disabled");
                    timer(remaining);
                    timer(120);
                    sAlert('success', dataResult.msg);
                } else if (dataResult.statusCode == 201) {
                    sAlert('error', dataResult.msg);
                }
            },
            complete: function () {
                document.querySelector('#resent').textContent = btn;
            },
        });

    });
});


$(document).ready(function () {
    $("#otpVerification").on("click", function () {
        $("#resent").attr("disabled", "disabled");
        let btn = document.querySelector('#otpVerification').textContent;
        formData = new FormData(document.forms.namedItem("FormData"));
        $.ajax({
            url: base_url + "login/forgotVerification",
            type: "POST",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $("#otpVerification").html('Please Wait...');
            },
            success: function (dataResult) {
                var dataResult = JSON.parse(dataResult);
                if (dataResult.statusCode == 200) {
                    $("#otpVerification").attr("disabled", "disabled");
                    sAlert('success', dataResult.msg, dataResult.url);
                    document.getElementById("FormData").reset();
                } else if (dataResult.statusCode == 201) {
                    sAlert('error', dataResult.msg);
                }
            },
            complete: function () {
                document.querySelector('#otpVerification').textContent = btn;
            },
        });
    });
});


$(document).ready(function () {
    $("#Newpassword").on("click", function () {
        let btn = document.querySelector('#Newpassword').textContent;
        formData = new FormData(document.forms.namedItem("FormData"));
        let password = $("#password").val();
        let cPassword = $("#cPassword").val();
        if (password != "" && cPassword != "") {
            $.ajax({
                url: base_url + "login/NewPassword",
                type: "POST",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $("#Newpassword").html('Please Wait...');
                },
                success: function (dataResult) {
                    var dataResult = JSON.parse(dataResult);
                    if (dataResult.statusCode == 200) {
                        $("#Newpassword").attr("disabled", "disabled");
                        sAlert('success', dataResult.msg, dataResult.url);
                        document.getElementById("FormData").reset();
                    } else if (dataResult.statusCode == 201) {
                        sAlert('error', dataResult.msg);
                    }
                },
                complete: function () {
                    document.querySelector('#Newpassword').textContent = btn;
                },
            });
        } else {
            sAlert('error', 'Password Required!');
        }
    });
});