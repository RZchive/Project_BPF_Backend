<!DOCTYPE html>
<html>
<head>
    <title>CRUD LPK</title>
</head>
<body>

<h1>CRUD LPK</h1>

<h3 id="formTitle">Tambah LPK</h3>

<input type="hidden" id="lpk_id">

<input type="text" id="nama_lpk" placeholder="Nama LPK">
<input type="text" id="alamat" placeholder="Alamat">
<input type="text" id="bidang_keahlian" placeholder="Bidang Keahlian">
<input type="text" id="kontak" placeholder="Kontak">
<input type="email" id="email" placeholder="Email">

<br><br>

<button onclick="simpanLPK()">
    Simpan
</button>

<button onclick="resetForm()">
    Reset
</button>

<hr>

<h3>Daftar LPK</h3>

<button onclick="getLPK()">
    Refresh
</button>

<br><br>

<table border="1" cellpadding="10">

    <thead>
        <tr>
            <th>ID</th>
            <th>Nama LPK</th>
            <th>Bidang</th>
            <th>Email</th>
            <th>Aksi</th>
        </tr>
    </thead>

    <tbody id="tableLPK">

    </tbody>

</table>

<script>

const token = localStorage.getItem('token');

if(!token){
    alert('Silakan login terlebih dahulu');
    window.location.href = '/';
}

getLPK();

function getLPK(){

    fetch('/api/lpk', {
        headers:{
            'Authorization':'Bearer ' + token,
            'Accept':'application/json'
        }
    })
    .then(res => res.json())
    .then(response => {

        let html = '';

        response.data.forEach(item => {

            html += `
                <tr>
                    <td>${item.id}</td>
                    <td>${item.nama_lpk}</td>
                    <td>${item.bidang_keahlian ?? '-'}</td>
                    <td>${item.email ?? '-'}</td>
                    <td>

                        <button onclick="editLPK(${item.id})">
                            Edit
                        </button>

                        <button onclick="hapusLPK(${item.id})">
                            Hapus
                        </button>

                    </td>
                </tr>
            `;

        });

        document.getElementById('tableLPK').innerHTML = html;

    });

}

function simpanLPK(){

    const id = document.getElementById('lpk_id').value;

    const data = {

        nama_lpk: document.getElementById('nama_lpk').value,
        alamat: document.getElementById('alamat').value,
        bidang_keahlian: document.getElementById('bidang_keahlian').value,
        kontak: document.getElementById('kontak').value,
        email: document.getElementById('email').value,
        status_aktif: true

    };

    let url = '/api/lpk';
    let method = 'POST';

    if(id){

        url = '/api/lpk/' + id;
        method = 'PUT';

    }

    fetch(url, {

        method: method,

        headers:{
            'Authorization':'Bearer ' + token,
            'Accept':'application/json',
            'Content-Type':'application/json'
        },

        body: JSON.stringify(data)

    })

    .then(res => res.json())

    .then(data => {

        alert(data.message);

        resetForm();

        getLPK();

    });

}

function editLPK(id){

    fetch('/api/lpk/' + id, {

        headers:{
            'Authorization':'Bearer ' + token,
            'Accept':'application/json'
        }

    })

    .then(res => res.json())

    .then(response => {

        const data = response.data;

        document.getElementById('lpk_id').value = data.id;
        document.getElementById('nama_lpk').value = data.nama_lpk;
        document.getElementById('alamat').value = data.alamat;
        document.getElementById('bidang_keahlian').value = data.bidang_keahlian;
        document.getElementById('kontak').value = data.kontak;
        document.getElementById('email').value = data.email;

        document.getElementById('formTitle').innerText =
            'Edit LPK';

    });

}

function hapusLPK(id){

    if(!confirm('Yakin ingin menghapus data ini?')){
        return;
    }

    fetch('/api/lpk/' + id, {

        method:'DELETE',

        headers:{
            'Authorization':'Bearer ' + token,
            'Accept':'application/json'
        }

    })

    .then(res => res.json())

    .then(data => {

        alert(data.message);

        getLPK();

    });

}

function resetForm(){

    document.getElementById('lpk_id').value = '';
    document.getElementById('nama_lpk').value = '';
    document.getElementById('alamat').value = '';
    document.getElementById('bidang_keahlian').value = '';
    document.getElementById('kontak').value = '';
    document.getElementById('email').value = '';

    document.getElementById('formTitle').innerText =
        'Tambah LPK';

}

</script>

</body>
</html>