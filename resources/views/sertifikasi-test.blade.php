<!DOCTYPE html>
<html>
<head>
    <title>CRUD Sertifikasi</title>
</head>
<body>

<h1>CRUD Sertifikasi</h1>

<h3 id="formTitle">Tambah Sertifikasi</h3>

<input type="hidden" id="id">

<input type="number" id="tenaga_kerja_id" placeholder="ID Tenaga Kerja">
<input type="text" id="nama_sertifikasi" placeholder="Nama Sertifikasi">
<input type="text" id="lembaga_sertifikasi" placeholder="Lembaga Sertifikasi">
<input type="text" id="nomor_sertifikat" placeholder="Nomor Sertifikat">
<label>Tanggal Terbit:</label>
<input type="date" id="tanggal_terbit">
<label>Masa Berlaku:</label>
<input type="date" id="masa_berlaku">

<br><br>

<button onclick="simpanData()">Simpan</button>
<button onclick="resetForm()">Reset</button>

<hr>

<h3>Daftar Sertifikasi</h3>

<button onclick="getData()">Refresh Data</button>

<br><br>

<table border="1" cellpadding="10">
    <thead>
        <tr>
            <th>ID</th>
            <th>ID Tenaga Kerja</th>
            <th>Nama Sertifikasi</th>
            <th>Lembaga</th>
            <th>Nomor</th>
            <th>Tanggal Terbit</th>
            <th>Masa Berlaku</th>
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
    fetch('/api/sertifikasi', {
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
                    <td>${item.tenaga_kerja_id} ${item.tenaga_kerja ? '(' + item.tenaga_kerja.nama + ')' : ''}</td>
                    <td>${item.nama_sertifikasi ?? '-'}</td>
                    <td>${item.lembaga_sertifikasi ?? '-'}</td>
                    <td>${item.nomor_sertifikat ?? '-'}</td>
                    <td>${item.tanggal_terbit ?? '-'}</td>
                    <td>${item.masa_berlaku ?? '-'}</td>
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
        tenaga_kerja_id: document.getElementById('tenaga_kerja_id').value,
        nama_sertifikasi: document.getElementById('nama_sertifikasi').value,
        lembaga_sertifikasi: document.getElementById('lembaga_sertifikasi').value,
        nomor_sertifikat: document.getElementById('nomor_sertifikat').value,
        tanggal_terbit: document.getElementById('tanggal_terbit').value,
        masa_berlaku: document.getElementById('masa_berlaku').value || null
    };

    let url = '/api/sertifikasi';
    let method = 'POST';

    if(id){
        url = '/api/sertifikasi/' + id;
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
    fetch('/api/sertifikasi/' + id, {
        headers:{
            'Authorization':'Bearer ' + token,
            'Accept':'application/json'
        }
    })
    .then(res => res.json())
    .then(response => {
        const data = response.data;
        document.getElementById('id').value = data.id;
        document.getElementById('tenaga_kerja_id').value = data.tenaga_kerja_id;
        document.getElementById('nama_sertifikasi').value = data.nama_sertifikasi ?? '';
        document.getElementById('lembaga_sertifikasi').value = data.lembaga_sertifikasi ?? '';
        document.getElementById('nomor_sertifikat').value = data.nomor_sertifikat ?? '';
        document.getElementById('tanggal_terbit').value = data.tanggal_terbit ?? '';
        document.getElementById('masa_berlaku').value = data.masa_berlaku ?? '';
        document.getElementById('formTitle').innerText = 'Edit Sertifikasi';
    });
}

function hapusData(id){
    if(!confirm('Yakin ingin menghapus data ini?')){
        return;
    }
    fetch('/api/sertifikasi/' + id, {
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
    document.getElementById('tenaga_kerja_id').value = '';
    document.getElementById('nama_sertifikasi').value = '';
    document.getElementById('lembaga_sertifikasi').value = '';
    document.getElementById('nomor_sertifikat').value = '';
    document.getElementById('tanggal_terbit').value = '';
    document.getElementById('masa_berlaku').value = '';
    document.getElementById('formTitle').innerText = 'Tambah Sertifikasi';
}
</script>
</body>
</html>
