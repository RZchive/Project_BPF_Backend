<!DOCTYPE html>
<html>
<head>
    <title>CRUD Tenaga Kerja</title>
</head>
<body>

<h1>CRUD Tenaga Kerja</h1>

<h3 id="formTitle">Tambah Tenaga Kerja</h3>

<input type="hidden" id="id">

<input type="text" id="nik" placeholder="NIK">
<input type="text" id="nama" placeholder="Nama">
<input type="email" id="email" placeholder="Email">
<input type="text" id="no_hp" placeholder="No HP">

<select id="jenis_kelamin">
    <option value="">Pilih Jenis Kelamin</option>
    <option value="L">Laki-laki</option>
    <option value="P">Perempuan</option>
</select>

<input type="date" id="tanggal_lahir">

<input type="text" id="alamat" placeholder="Alamat">

<input type="text"
       id="pendidikan_terakhir"
       placeholder="Pendidikan Terakhir">

<input type="text"
       id="status_pekerjaan"
       placeholder="Status Pekerjaan">

<br><br>

<button onclick="simpanData()">Simpan</button>
<button onclick="resetForm()">Reset</button>

<hr>

<h3>Daftar Tenaga Kerja</h3>

<button onclick="getData()">Refresh Data</button>

<br><br>

<table border="1" cellpadding="10">

    <thead>
        <tr>
            <th>ID</th>
            <th>NIK</th>
            <th>Nama</th>
            <th>Email</th>
            <th>No HP</th>
            <th>JK</th>
            <th>Pendidikan</th>
            <th>Status</th>
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

    fetch('/api/tenaga-kerja', {

        headers:{
            'Authorization':'Bearer ' + token,
            'Accept':'application/json'
        }

    })

    .then(res => res.json())

    .then(response => {

        let html = '';

        response.data.forEach(item => {

            let jk = '-';

            if(item.jenis_kelamin === 'L'){
                jk = 'Laki-laki';
            }

            if(item.jenis_kelamin === 'P'){
                jk = 'Perempuan';
            }

            html += `
                <tr>

                    <td>${item.id}</td>
                    <td>${item.nik ?? '-'}</td>
                    <td>${item.nama ?? '-'}</td>
                    <td>${item.email ?? '-'}</td>
                    <td>${item.no_hp ?? '-'}</td>
                    <td>${jk}</td>
                    <td>${item.pendidikan_terakhir ?? '-'}</td>
                    <td>${item.status_pekerjaan ?? '-'}</td>

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

        nik: document.getElementById('nik').value,
        nama: document.getElementById('nama').value,
        email: document.getElementById('email').value,
        no_hp: document.getElementById('no_hp').value,
        jenis_kelamin: document.getElementById('jenis_kelamin').value,
        tanggal_lahir: document.getElementById('tanggal_lahir').value,
        alamat: document.getElementById('alamat').value,
        pendidikan_terakhir:
            document.getElementById('pendidikan_terakhir').value,
        status_pekerjaan:
            document.getElementById('status_pekerjaan').value

    };

    let url = '/api/tenaga-kerja';
    let method = 'POST';

    if(id){

        url = '/api/tenaga-kerja/' + id;
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

    fetch('/api/tenaga-kerja/' + id, {

        headers:{
            'Authorization':'Bearer ' + token,
            'Accept':'application/json'
        }

    })

    .then(res => res.json())

    .then(response => {

        const data = response.data;

        document.getElementById('id').value = data.id;
        document.getElementById('nik').value = data.nik ?? '';
        document.getElementById('nama').value = data.nama ?? '';
        document.getElementById('email').value = data.email ?? '';
        document.getElementById('no_hp').value = data.no_hp ?? '';
        document.getElementById('jenis_kelamin').value = data.jenis_kelamin ?? '';
        document.getElementById('tanggal_lahir').value = data.tanggal_lahir ?? '';
        document.getElementById('alamat').value = data.alamat ?? '';
        document.getElementById('pendidikan_terakhir').value =
            data.pendidikan_terakhir ?? '';
        document.getElementById('status_pekerjaan').value =
            data.status_pekerjaan ?? '';

        document.getElementById('formTitle').innerText =
            'Edit Tenaga Kerja';

    });

}

function hapusData(id){

    if(!confirm('Yakin ingin menghapus data ini?')){
        return;
    }

    fetch('/api/tenaga-kerja/' + id, {

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
    document.getElementById('nik').value = '';
    document.getElementById('nama').value = '';
    document.getElementById('email').value = '';
    document.getElementById('no_hp').value = '';
    document.getElementById('jenis_kelamin').value = '';
    document.getElementById('tanggal_lahir').value = '';
    document.getElementById('alamat').value = '';
    document.getElementById('pendidikan_terakhir').value = '';
    document.getElementById('status_pekerjaan').value = '';

    document.getElementById('formTitle').innerText =
        'Tambah Tenaga Kerja';

}

</script>

</body>
</html>