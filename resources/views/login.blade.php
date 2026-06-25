<!DOCTYPE html>
<html>
<head>
    <title>Test Auth BPF</title>
</head>
<body>

<h2>Login Test</h2>

<form id="loginForm">

    <input
        type="email"
        id="email"
        placeholder="Email"
        required
    >

    <br><br>

    <input
        type="password"
        id="password"
        placeholder="Password"
        required
    >

    <br><br>

    <button type="submit">
        Login
    </button>

</form>

<hr>

<button onclick="getProfile()">
    Cek Profile
</button>

<button onclick="logout()">
    Logout
</button>

<hr>

<pre id="result"></pre>

<script>

let token = localStorage.getItem('token') || '';

document
.getElementById('loginForm')
.addEventListener('submit', async (e) => {

    e.preventDefault();

    const response = await fetch('/api/login', {

        method: 'POST',

        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },

        body: JSON.stringify({

            email: document.getElementById('email').value,
            password: document.getElementById('password').value

        })

    });

    const data = await response.json();

    document.getElementById('result').innerText =
        JSON.stringify(data, null, 2);

    if(data.success){

        token = data.token;

        localStorage.setItem(
            'token',
            data.token
        );

        alert('Login Berhasil');

    }

});

async function getProfile(){

    const response = await fetch('/api/me', {

        headers: {
            'Authorization': 'Bearer ' + token,
            'Accept': 'application/json'
        }

    });

    const data = await response.json();

    document.getElementById('result').innerText =
        JSON.stringify(data, null, 2);

}

async function logout(){

    const response = await fetch('/api/logout', {

        method: 'POST',

        headers: {
            'Authorization': 'Bearer ' + token,
            'Accept': 'application/json'
        }

    });

    const data = await response.json();

    document.getElementById('result').innerText =
        JSON.stringify(data, null, 2);

    localStorage.removeItem('token');

    token = '';

    alert('Logout Berhasil');

}

</script>

</body>
</html>