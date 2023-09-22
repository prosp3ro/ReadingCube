console.log("hi");

const validation = new JustValidate("#register");

validation
    .addField("#username", [
        {
            rule: "required"
        },
        {
            rule: 'customRegexp',
            value: /^[a-zA-Z0-9]{5,}$/,
            errorMessage: "Invalid username format. Please use only letters and numbers, and ensure it's at least 5 characters long",
        },
    ])
    .addField("#email", [
        {
            rule: "required"
        },
        {
            rule: "email"
        }
    ])
    .addField("#password", [
        {
            rule: "required"
        },
        {
            rule: "password"
        }
    ])

