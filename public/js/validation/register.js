const validation = new JustValidate("#register");

validation
    .addField("#username", [
        {
            rule: "required"
        },
        {
            rule: 'customRegexp',
            value: /^[a-zA-Z0-9]{5,}$/,
            errorMessage: "Invalid username format. Please use only letters and numbers, and ensure it's at least five characters long",
        },
    ])
    .addField("#email", [
        {
            rule: "required"
        },
        {
            rule: "email"
        },
        {
            validator: function (value) {
                return function () {
                    return fetch("http://127.0.0.1/register?email=" + encodeURIComponent(value))
                        .then(function(response) {
                            return response.json();
                        })
                        .then(function(json) {
                            return json.available;
                        })
                }
            },
            errorMessage: "Email is already taken"
        }
    ])
    .addField("#password", [
        {
            rule: "required"
        },
        {
            rule: 'customRegexp',
            value: /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/m,
            errorMessage: "Password must be at least 8 characters and contain at least 1 uppercase letter, 1 lowercase letter, and 1 number",
        },
    ])
    .addField('#password_confirmation', [
        {
            rule: 'required',
        },
        {
            validator: (value, fields) => {
                if (fields['#password'] && fields['#password'].elem) {
                    const repeatPasswordValue = fields['#password'].elem.value;
                    return value === repeatPasswordValue;
                }

                return true;
            },
            errorMessage: 'Passwords should be the same',
        },
    ])
    .onSuccess((event) => {
        document.getElementById("register").submit();
    })
