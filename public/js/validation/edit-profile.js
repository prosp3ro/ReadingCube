const editDataValidation = new JustValidate("#edit-data");
const editPasswordValidation = new JustValidate("#edit-password");

editDataValidation
    .addField("#newUsername", [
        {
            rule: 'customRegexp',
            value: /^[a-zA-Z0-9]{5,}$/,
            errorMessage: "Invalid username format. Please use only letters and numbers, and ensure it's at least five characters long",
        },
    ])
    .addField("#newEmail", [
        {
            rule: "email"
        },
        // {
        //     validator: (value) => () => {
        //         return fetch("http://127.0.0.3/register?email=" + encodeURIComponent(value))
        //             .then(function(response) {
        //                 return response.json();
        //             })
        //             .then(function(json) {
        //                 return json.available;
        //             })
        //     },
        //     errorMessage: "Email is already taken"
        // }
    ])
    .addField("#password", [
        {
            rule: "required"
        },
        {
            rule: "password"
        }
    ])
    .onSuccess((event) => {
        document.getElementById("edit-data").submit();
    })

editPasswordValidation
