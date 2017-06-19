$(document).ready(function() {
    $('#support-form').bootstrapValidator({
        container: '#messages',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
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
                        min: 30,
                        message: 'The message content must be less than 500 characters and more then than 30 characters long'
                    }
                }
            }
        }
    });
});