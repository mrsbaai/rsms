$(document).ready(function() {
    $('#account-form').bootstrapValidator({
        container: '#accountmessages',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            name: {
                validators: {
                    notEmpty: {
                        message: 'The name is required and cannot be empty'
                    }
                }
            },
            email: {
                validators: {
                    notEmpty: {
                        message: 'The email address is required and cannot be empty'
                    },
                    emailAddress: {
                        message: 'The email address is not valid'
                    }
                }
            },
            callback: {
                validators: {
                    uri: {
                        allowLocal: true,
                        message: 'This Callback URL is not valid'
                    }
                }
            },
        }
    });

    $('#password-form').bootstrapValidator({
        container: '#passwordmessages',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            current_password: {
                validators: {
                    notEmpty: {
                        message: 'Enter your current password'
                    }
                }
            },
            new_password: {
                validators: {
                    notEmpty: {
                        message: 'Enter your new password'
                    },
                    stringLength: {
                        min: 6,
                        max: 30,
                        message: 'The password must be more than 6 and less than 30 characters long'
                    },
                }
            },
            repeat_password: {
                validators: {
                    notEmpty: {
                        message: 'Re-enter your new password'
                    },
                    identical: {
                        field: 'new_password',
                        message: 'The password and its confirm are not the same'
                    }
                }

            },

        }
    });
});