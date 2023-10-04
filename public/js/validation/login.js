const validation = new JustValidate("#login");

validation
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
        }
    ])
    .onSuccess((event) => {
        document.getElementById("login").submit();
    })
