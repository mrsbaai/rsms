$(document).ready(function() {
    $('#contact-form').bootstrapValidator({
        container: '#messages',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            fullName: {
                validators: {
                    notEmpty: {
                        message: 'The full name is required and cannot be empty'
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
            subject: {
                validators: {
                    notEmpty: {
                        message: 'The subject is required and cannot be empty'
                    },
                    stringLength: {
                        max: 100,
                        message: 'The subject must be less than 100 characters long'
                    }
                }
            },
            message: {
                validators: {
                    notEmpty: {
                        message: 'The message content is required and cannot be empty'
                    },
                    stringLength: {
                        max: 500,
                        message: 'The message content must be less than 500 characters long'
                    }
                }
            }
        }
    });
});