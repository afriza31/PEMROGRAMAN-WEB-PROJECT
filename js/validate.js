document.addEventListener("DOMContentLoaded", function() {
    // Validasi Form Signup
    function validateForm() {
        var form = document.forms["signupForm"];
        var uname = form["uname"].value;
        var fname = form["fname"].value;
        var lname = form["lname"].value;
        var email = form["email"].value;
        var pass = form["pass"].value;
        var repass = form["repass"].value;

        var unamePattern = /^[A-Za-z][A-Za-z0-9_]{3,}$/;
        var namePattern = /^[A-Za-z]+$/;
        var emailPattern = /^[A-Za-z_0-9]+@[A-Za-z]+\.[A-Za-z]+$/;
        var passPattern = /.{5,}/;

        var isValid = true;

        // Reset error messages
        document.getElementById("uname-error").innerText = "";
        document.getElementById("fname-error").innerText = "";
        document.getElementById("lname-error").innerText = "";
        document.getElementById("email-error").innerText = "";
        document.getElementById("pass-error").innerText = "";
        document.getElementById("repass-error").innerText = "";

        if (!unamePattern.test(uname)) {
            document.getElementById("uname-error").innerText = "Username can contain alphabet letters, numbers and underscore(_), but must begin with a letter and be at least 4 characters long.";
            isValid = false;
        }
        if (!namePattern.test(fname)) {
            document.getElementById("fname-error").innerText = "First name can only contain alphabet letters.";
            isValid = false;
        }
        if (!namePattern.test(lname)) {
            document.getElementById("lname-error").innerText = "Last name can only contain alphabet letters.";
            isValid = false;
        }
        if (!emailPattern.test(email)) {
            document.getElementById("email-error").innerText = "Please enter a valid email address.";
            isValid = false;
        }
        if (!passPattern.test(pass)) {
            document.getElementById("pass-error").innerText = "Password must be at least 5 characters long.";
            isValid = false;
        }
        if (pass !== repass) {
            document.getElementById("repass-error").innerText = "Password and Confirm Password do not match.";
            isValid = false;
        }

        return isValid;
    }

    // Attach validateForm to signupForm onsubmit event
    document.forms["signupForm"].onsubmit = validateForm;
     
});


