<!DOCTYPE html>
<html>
<head>
    <title>CRUD Peserta Pelatihan</title>
</head>
<body>

<h1>CRUD Peserta Pelatihan</h1>

<h3 id="formTitle">Tambah Peserta Pelatihan</h3>

<input type="hidden" id="id">

<input type="number" id="tenaga_kerja_id" placeholder="ID Tenaga Kerja">
<input type="number" id="pelatihan_id" placeholder="ID Pelatihan">
<select id="status_peserta">
    <option value="aktif">Aktif</option>
    <option value="lulus">Lulus</option>
    <option value="tidak_lulus">Tidak Lulus</option>
</select>

<br><br>

<button onclick="simpanData()">Simpan</button>
<button onclick="resetForm()">Reset</button>

<hr>

<h3>Daftar Peserta Pelatihan</h3>

<button onclick="getData()">Refresh Data</button>

<br><br>

<table border="1" cellpadding="10">
    <thead>
        <tr>
            <th>ID</th>
            <th>ID Tenaga Kerja</th>
            <th>ID Pelatihan</th>
            <th>Status</th>
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
    fetch('/api/peserta-pelatihan', {
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
                    <td>${item.pelatihan_id} ${item.pelatihan ? '(' + item.pelatihan.nama_pelatihan + ')' : ''}</td>
                    <td>${item.status_peserta ?? '-'}</td>
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
        pelatihan_id: document.getElementById('pelatihan_id').value,
        status_peserta: document.getElementById('status_peserta').value
    };

    let url = '/api/peserta-pelatihan';
    let method = 'POST';

    if(id){
        url = '/api/peserta-pelatihan/' + id;
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
    fetch('/api/peserta-pelatihan/' + id, {
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
        document.getElementById('pelatihan_id').value = data.pelatihan_id;
        document.getElementById('status_peserta').value = data.status_peserta ?? 'aktif';
        document.getElementById('formTitle').innerText = 'Edit Peserta Pelatihan';
    });
}

function hapusData(id){
    if(!confirm('Yakin ingin menghapus data ini?')){
        return;
    }
    fetch('/api/peserta-pelatihan/' + id, {
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
    document.getElementById('pelatihan_id').value = '';
    document.getElementById('status_peserta').value = 'aktif';
    document.getElementById('formTitle').innerText = 'Tambah Peserta Pelatihan';
}
</script>
</body>
</html>
