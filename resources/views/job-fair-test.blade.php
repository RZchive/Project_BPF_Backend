<!DOCTYPE html>
<html>
<head>
    <title>CRUD Job Fair</title>
</head>
<body>

<h1>CRUD Job Fair</h1>

<h3 id="formTitle">Tambah Job Fair</h3>

<input type="hidden" id="id">

<input type="text" id="nama_kegiatan" placeholder="Nama Kegiatan">
<label>Tanggal:</label>
<input type="date" id="tanggal">
<input type="text" id="lokasi" placeholder="Lokasi">
<input type="text" id="deskripsi" placeholder="Deskripsi">

<br><br>

<button onclick="simpanData()">Simpan</button>
<button onclick="resetForm()">Reset</button>

<hr>

<h3>Daftar Job Fair</h3>

<button onclick="getData()">Refresh Data</button>

<br><br>

<table border="1" cellpadding="10">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama Kegiatan</th>
            <th>Tanggal</th>
            <th>Lokasi</th>
            <th>Deskripsi</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody id="tableData"></tbody>
</table>

<script>
const token = localStorage.getItem('token');

if(!token){
    alert('Silakan login terlebih dahulu');
    window.location.href = '/';
}

getData();

function getData(){
    fetch('/api/job-fair', {
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
                    <td>${item.nama_kegiatan ?? '-'}</td>
                    <td>${item.tanggal ?? '-'}</td>
                    <td>${item.lokasi ?? '-'}</td>
                    <td>${item.deskripsi ?? '-'}</td>
                    <td>
                        <button onclick="editData(${item.id})">Edit</button>
                        <button onclick="hapusData(${item.id})">Hapus</button>
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
        nama_kegiatan: document.getElementById('nama_kegiatan').value,
        tanggal: document.getElementById('tanggal').value,
        lokasi: document.getElementById('lokasi').value,
        deskripsi: document.getElementById('deskripsi').value
    };

    let url = '/api/job-fair';
    let method = 'POST';

    if(id){
        url = '/api/job-fair/' + id;
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
    fetch('/api/job-fair/' + id, {
        headers:{
            'Authorization':'Bearer ' + token,
            'Accept':'application/json'
        }
    })
    .then(res => res.json())
    .then(response => {
        const data = response.data;
        document.getElementById('id').value = data.id;
        document.getElementById('nama_kegiatan').value = data.nama_kegiatan ?? '';
        document.getElementById('tanggal').value = data.tanggal ?? '';
        document.getElementById('lokasi').value = data.lokasi ?? '';
        document.getElementById('deskripsi').value = data.deskripsi ?? '';
        document.getElementById('formTitle').innerText = 'Edit Job Fair';
    });
}

function hapusData(id){
    if(!confirm('Yakin ingin menghapus data ini?')){
        return;
    }
    fetch('/api/job-fair/' + id, {
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
    document.getElementById('nama_kegiatan').value = '';
    document.getElementById('tanggal').value = '';
    document.getElementById('lokasi').value = '';
    document.getElementById('deskripsi').value = '';
    document.getElementById('formTitle').innerText = 'Tambah Job Fair';
}
</script>
</body>
</html>
