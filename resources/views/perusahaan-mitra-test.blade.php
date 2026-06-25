<!DOCTYPE html>
<html>
<head>
    <title>CRUD Perusahaan Mitra</title>
</head>
<body>

<h1>CRUD Perusahaan Mitra</h1>

<h3 id="formTitle">Tambah Perusahaan Mitra</h3>

<input type="hidden" id="id">

<input type="text"
       id="nama_perusahaan"
       placeholder="Nama Perusahaan">

<input type="text"
       id="bidang_usaha"
       placeholder="Bidang Usaha">

<input type="text"
       id="alamat"
       placeholder="Alamat">

<input type="text"
       id="kontak"
       placeholder="Kontak">

<input type="email"
       id="email"
       placeholder="Email">

<br><br>

<button onclick="simpanData()">
    Simpan
</button>

<button onclick="resetForm()">
    Reset
</button>

<hr>

<h3>Daftar Perusahaan Mitra</h3>

<button onclick="getData()">
    Refresh Data
</button>

<br><br>

<table border="1" cellpadding="10">

    <thead>
        <tr>
            <th>ID</th>
            <th>Nama Perusahaan</th>
            <th>Bidang Usaha</th>
            <th>Kontak</th>
            <th>Email</th>
            <th>Aksi</th>
        </tr>
    </thead>

    <tbody id="tableData">

    </tbody>

</table>

<script>

const token = localStorage.getItem('token');

if(!token){

    alert('Silakan login terlebih dahulu');

    window.location.href = '/';

}

getData();

function getData(){

    fetch('/api/perusahaan-mitra', {

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
                    <td>${item.nama_perusahaan ?? '-'}</td>
                    <td>${item.bidang_usaha ?? '-'}</td>
                    <td>${item.kontak ?? '-'}</td>
                    <td>${item.email ?? '-'}</td>

                    <td>

                        <button onclick="editData(${item.id})">
                            Edit
                        </button>

                        <button onclick="hapusData(${item.id})">
                            Hapus
                        </button>

                    </td>

                </tr>
            `;

        });

        document.getElementById('tableData').innerHTML = html;

    });

}

function simpanData(){

    const id = document.getElementById('id').value;

    const payload = {

        nama_perusahaan:
            document.getElementById('nama_perusahaan').value,

        bidang_usaha:
            document.getElementById('bidang_usaha').value,

        alamat:
            document.getElementById('alamat').value,

        kontak:
            document.getElementById('kontak').value,

        email:
            document.getElementById('email').value

    };

    let url = '/api/perusahaan-mitra';
    let method = 'POST';

    if(id){

        url = '/api/perusahaan-mitra/' + id;
        method = 'PUT';

    }

    fetch(url, {

        method: method,

        headers:{
            'Authorization':'Bearer ' + token,
            'Accept':'application/json',
            'Content-Type':'application/json'
        },

        body: JSON.stringify(payload)

    })

    .then(res => res.json())

    .then(data => {

        if(data.message){
            alert(data.message);
        }

        resetForm();

        getData();

    })

    .catch(error => {

        console.error(error);

        alert('Terjadi kesalahan');

    });

}

function editData(id){

    fetch('/api/perusahaan-mitra/' + id, {

        headers:{
            'Authorization':'Bearer ' + token,
            'Accept':'application/json'
        }

    })

    .then(res => res.json())

    .then(response => {

        const data = response.data;

        document.getElementById('id').value =
            data.id;

        document.getElementById('nama_perusahaan').value =
            data.nama_perusahaan ?? '';

        document.getElementById('bidang_usaha').value =
            data.bidang_usaha ?? '';

        document.getElementById('alamat').value =
            data.alamat ?? '';

        document.getElementById('kontak').value =
            data.kontak ?? '';

        document.getElementById('email').value =
            data.email ?? '';

        document.getElementById('formTitle').innerText =
            'Edit Perusahaan Mitra';

    });

}

function hapusData(id){

    if(!confirm('Yakin ingin menghapus data ini?')){
        return;
    }

    fetch('/api/perusahaan-mitra/' + id, {

        method:'DELETE',

        headers:{
            'Authorization':'Bearer ' + token,
            'Accept':'application/json'
        }

    })

    .then(res => res.json())

    .then(data => {

        alert(data.message);

        getData();

    });

}

function resetForm(){

    document.getElementById('id').value = '';

    document.getElementById('nama_perusahaan').value = '';

    document.getElementById('bidang_usaha').value = '';

    document.getElementById('alamat').value = '';

    document.getElementById('kontak').value = '';

    document.getElementById('email').value = '';

    document.getElementById('formTitle').innerText =
        'Tambah Perusahaan Mitra';

}

</script>

</body>
</html>