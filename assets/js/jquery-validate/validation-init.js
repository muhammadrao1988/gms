var Script = function () {

    $.validator.setDefaults({
        submitHandler: function() {
            var action = $(this).attr('action').toLowerCase();
            var form = $(this).parents('form');
            form.attr('action', form.attr('action') + action + '/');
            form.submit();
        }
    });

    $().ready(function() {
        // validate the comment form when it is submitted
        $("#commentForm").validate();

        // validate signup form on keyup and submit
        $("#answer_form").validate({
            rules: {
                firstname: "required",
                lastname: "required",
                answer: {
                    required: true,
                    minlength: 5
                },
                confirm_answer: {
                    required: true,
                    minlength: 5,
                    equalTo: "#answer"
                }
            },
            messages: {
                answer: {
                    required: "Please provide you answer",
                    minlength: "Your answer must be at least 5 characters long"
                },
                confirm_answer: {
                    required: "Please provide you answer",
                    minlength: "Your answer must be at least 5 characters long",
                    equalTo: "Please enter the same answer as above"
                }
            }
        });
		 $("#step3").validate({
            rules: {
               
                password: {
                    required: true,
                    minlength: 6
                },
                confirm_password: {
                    required: true,
                    minlength: 6,
                    equalTo: "#password"
                },
               
            },
            messages: {
               
                password: {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 6 characters long"
                },
                confirm_password: {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 6 characters long",
                    equalTo: "Please enter the same password as above"
                },
               
            }
        });
        /**** general validation form ****/
        $("#validateForm").validate({
            rules: {
                firstname: "required",
                lastname: "required",
                username: {
                    required: true,
                    minlength: 2
                },
                password: {
                    required: true,
                    minlength: 5
                },
                confirm_password: {
                    required: true,
                    minlength: 5,
                    equalTo: "#password"
                },
                answer: {
                    required: true,
                    minlength: 5
                },
                confirm_answer: {
                    required: true,
                    minlength: 5,
                    equalTo: "#answer"
                },
                email: {
                    required: true,
                    email: true
                },
                topic: {
                    required: "#newsletter:checked",
                    minlength: 2
                },
                agree: "required"
            },
            messages: {
                firstname: "Please enter your firstname",
                lastname: "Please enter your lastname",
                username: {
                    required: "Please enter a username",
                    minlength: "Your username must consist of at least 2 characters"
                },
                password: {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 5 characters long"
                },
                confirm_password: {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 5 characters long",
                    equalTo: "Please enter the same password as above"
                },
                answer: {
                    required: "Please provide you answer",
                    minlength: "Your answer must be at least 5 characters long"
                },
                confirm_answer: {
                    required: "Please provide you answer",
                    minlength: "Your answer must be at least 5 characters long",
                    equalTo: "Please enter the same answer as above"
                },
                email: "Please enter a valid email address",
                agree: "Please accept our policy"
            }
        });
        /**** end general validation forms ****/
        $("#signupForm").validate({
            rules: {
                firstname: "required",
                lastname: "required",
                username: {
                    required: true,
                    minlength: 2
                },
                password: {
                    required: true,
                    minlength: 5
                },
                confirm_password: {
                    required: true,
                    minlength: 5,
                    equalTo: "#password"
                },
                answer: {
                    required: true,
                    minlength: 5
                },
                confirm_answer: {
                    required: true,
                    minlength: 5,
                    equalTo: "#answer"
                },
                email: {
                    required: true,
                    email: true
                },
                topic: {
                    required: "#newsletter:checked",
                    minlength: 2
                },
                agree: "required"
            },
            messages: {
                firstname: "Please enter your firstname",
                lastname: "Please enter your lastname",
                username: {
                    required: "Please enter a username",
                    minlength: "Your username must consist of at least 2 characters"
                },
                password: {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 5 characters long"
                },
                confirm_password: {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 5 characters long",
                    equalTo: "Please enter the same password as above"
                },
                email: "Please enter a valid email address",
                agree: "Please accept our policy"
            }
        });

        // propose username by combining first- and lastname
        $("#username").focus(function() {
            var firstname = $("#firstname").val();
            var lastname = $("#lastname").val();
            if(firstname && lastname && !this.value) {
                this.value = firstname + "." + lastname;
            }
        });

        //code to hide topic selection, disable for demo
        var newsletter = $("#newsletter");
        // newsletter topics are optional, hide at first
        var inital = newsletter.is(":checked");
        var topics = $("#newsletter_topics")[inital ? "removeClass" : "addClass"]("gray");
        var topicInputs = topics.find("input").attr("disabled", !inital);
        // show when newsletter is checked
        newsletter.click(function() {
            topics[this.checked ? "removeClass" : "addClass"]("gray");
            topicInputs.attr("disabled", !this.checked);
        });
    });


}();