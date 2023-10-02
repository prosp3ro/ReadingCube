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
        },
        // {
        //     rule: "password"
        // }
    ])
    .onSuccess((event) => {
        document.getElementById("login").submit();
    })
