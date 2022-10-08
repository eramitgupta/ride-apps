// delete AccountsDelete user
$(document).ready(function () {
    $('.AccountsDelete').click(function (e) {
        e.preventDefault();
        var deleteid = $(this)
            .closest("tr").find(
                '.deletevalue')
            .val();
        // console.log(deleteid)
        swal({
            title: "Are you sure?",
            text: "Do you want to remove!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        type: "POST",
                        delay: 0,
                        url: base_url+"admin/deleteLogin",
                        data: {
                            "delete_id": deleteid,
                        },
                        success: function (dataResult) {
                            var dataResult = JSON.parse(dataResult);
                            if (dataResult.statusCode == 200) {
                                $("#" + deleteid).remove();
                                sAlert('success', dataResult.msg);
                            } else if (dataResult.statusCode == 201) {
                                sAlert('error', dataResult.msg);
                            }
                        }
                    });
                }
            });
    });
});

// Confirm Password admin
$(document).ready(function () {
    $("#PasswordUpdate").on("click", function () {
        let btn = document.querySelector('#PasswordUpdate').textContent;
        formData = new FormData(document.forms.namedItem("FormData"));
        let CurrentPassword = $("#CurrentPassword").val();
        let password = $("#password").val();
        let cpassword = $("#cpassword").val();
        if (password != "" && cpassword != "" && CurrentPassword != "") {
            $.ajax({
                url: base_url+"admin/passwordUpdateCode",
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
                        sAlert('success', dataResult.msg,dataResult.url);
                        document.getElementById("FormData").reset();
                    } else if (dataResult.statusCode == 201) {
                        sAlert('error', dataResult.msg);
                    }
                },
                complete: function () {
                    document.querySelector('#PasswordUpdate').textContent = btn;
                },
            });
        } else {
            sAlert('error', 'All Fields Required');
        }
    });
});

// user password update
$(document).ready(function () {
    $("#PasswordUpdate").on("click", function () {
        let btn = document.querySelector('#PasswordUpdate').textContent;
        formData = new FormData(document.forms.namedItem("FormData"));
        let CurrentPassword = $("#CurrentPassword").val();
        let password = $("#password").val();
        let cpassword = $("#cpassword").val();
        if (password != "" && cpassword != "" && CurrentPassword != "") {
            $.ajax({
                url: base_url+"admin/user/passwordUpdateCode",
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
                        sAlert('success', dataResult.msg,dataResult.url);
                        document.getElementById("FormData").reset();
                    } else if (dataResult.statusCode == 201) {
                        sAlert('error', dataResult.msg);
                    }
                },
                complete: function () {
                    document.querySelector('#PasswordUpdate').textContent = btn;
                },
            });
        } else {
            sAlert('error', 'All Fields Required');
        }
    });
});

// admin passwordUpdateCode
$(document).ready(function () {
    $("#PasswordUpdate").on("click", function () {
        let btn = document.querySelector('#PasswordUpdate').textContent;
        formData = new FormData(document.forms.namedItem("FormData"));
        let CurrentPassword = $("#CurrentPassword").val();
        let password = $("#password").val();
        let cpassword = $("#cpassword").val();
        if (password != "" && cpassword != "" && CurrentPassword != "") {
            $.ajax({
                url: base_url+"admin/authentication/passwordUpdateCode",
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
                        sAlert('success', dataResult.msg,dataResult.url);
                        document.getElementById("FormData").reset();
                    } else if (dataResult.statusCode == 201) {
                        sAlert('error', dataResult.msg);
                    }
                },
                complete: function () {
                    document.querySelector('#PasswordUpdate').textContent = btn;
                },
            });
        } else {
            sAlert('error', 'All Fields Required');
        }
    });
});
// delete Accounts admin
$(document).ready(function () {
    $('.AccountsDelete').click(function (e) {
        e.preventDefault();
        var deleteid = $(this)
            .closest("tr").find(
                '.deletevalue')
            .val();
        // console.log(deleteid)
        swal({
            title: "Are you sure?",
            text: "Do you want to remove!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        type: "POST",
                        delay: 0,
                        url: base_url+"admin/authentication/deleteLogin",
                        data: {
                            "delete_id": deleteid,
                        },
                        success: function (dataResult) {
                            var dataResult = JSON.parse(dataResult);
                            if (dataResult.statusCode == 200) {
                                $("#" + deleteid).remove();
                                sAlert('success', dataResult.msg);
                            } else if (dataResult.statusCode == 201) {
                                sAlert('error', dataResult.msg);
                            }
                        }
                    });
                }
            });
    });
});

// view data pops
$(document).on('click', '.view_Post', function() {
    let id = $(this).attr("id");
    if (id != '') {
        $.ajax({
            url: base_url+"admin/post/GetViewPost",
            method: "POST",
            data: {
                id: id,
            },
            success: function(data) {
                $('#dataSet').html(data);
                $('#detailsModal').modal('show');
            }
        });
    }
});

// delete bike
$(document).ready(function () {
    $('.BikeDelete').click(function (e) {
        e.preventDefault();
        var deleteid = $(this)
            .closest("tr").find(
                '.deletevalue')
            .val();
        // console.log(deleteid)
        swal({
            title: "Are you sure?",
            text: "Do you want to remove!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        type: "POST",
                        delay: 0,
                        url: base_url+"admin/bike/delete",
                        data: {
                            "delete_id": deleteid,
                        },
                        success: function (dataResult) {
                            var dataResult = JSON.parse(dataResult);
                            if (dataResult.statusCode == 200) {
                                $("#" + deleteid).remove();
                                sAlert('success', dataResult.msg);
                            } else if (dataResult.statusCode == 201) {
                                sAlert('error', dataResult.msg);
                            }
                        }
                    });
                }
            });
    });
});

//  Delete languages
$(document).ready(function () {
    $('.Deletelanguages').click(function (e) {
        e.preventDefault();
        var deleteid = $(this)
            .closest("tr").find(
                '.deletevalue')
            .val();
        // console.log(deleteid)
        swal({
            title: "Are you sure?",
            text: "Do you want to remove!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        type: "POST",
                        delay: 0,
                        url: base_url+"admin/language/delete",
                        data: {
                            "delete_id": deleteid,
                        },
                        success: function (dataResult) {
                            var dataResult = JSON.parse(dataResult);
                            if (dataResult.statusCode == 200) {
                                $("#" + deleteid).remove();
                                sAlert('success', dataResult.msg);
                            } else if (dataResult.statusCode == 201) {
                                sAlert('error', dataResult.msg);
                            }
                        }
                    });
                }
            });
    });
});
//  Delete Slider
$(document).ready(function () {
    $('.DeleteSlider').click(function (e) {
        e.preventDefault();
        var deleteid = $(this)
            .closest("tr").find(
                '.deletevalue')
            .val();
        // console.log(deleteid)
        swal({
            title: "Are you sure?",
            text: "Do you want to remove!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        type: "POST",
                        delay: 0,
                        url: base_url+"admin/slider/delete",
                        data: {
                            "delete_id": deleteid,
                        },
                        success: function (dataResult) {
                            var dataResult = JSON.parse(dataResult);
                            if (dataResult.statusCode == 200) {
                                $("#" + deleteid).remove();
                                sAlert('success', dataResult.msg);
                            } else if (dataResult.statusCode == 201) {
                                sAlert('error', dataResult.msg);
                            }
                        }
                    });
                }
            });
    });
});

//  Delete  Event
$(document).ready(function () {
    $('.deleteEvent').click(function (e) {
        e.preventDefault();
        var deleteid = $(this)
            .closest("tr").find(
                '.deletevalue')
            .val();
        // console.log(deleteid)
        swal({
            title: "Are you sure?",
            text: "Do you want to remove!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        type: "POST",
                        delay: 0,
                        url: base_url+"admin/event/delete",
                        data: {
                            "delete_id": deleteid,
                        },
                        success: function (dataResult) {
                            var dataResult = JSON.parse(dataResult);
                            if (dataResult.statusCode == 200) {
                                $("#" + deleteid).remove();
                                sAlert('success', dataResult.msg);
                            } else if (dataResult.statusCode == 201) {
                                sAlert('error', dataResult.msg);
                            }
                        }
                    });
                }
            });
    });
});