<!DOCTYPE html>
<html>
<head>
    <title>CRUD Job Fair Perusahaan</title>
</head>
<body>

<h1>CRUD Job Fair Perusahaan</h1>

<h3 id="formTitle">Tambah Perusahaan Peserta Job Fair</h3>

<input type="hidden" id="id">

<input type="number" id="job_fair_id" placeholder="ID Job Fair">
<input type="number" id="perusahaan_id" placeholder="ID Perusahaan Mitra">
<input type="number" id="jumlah_lowongan" placeholder="Jumlah Lowongan">
<input type="number" id="realisasi_penempatan" placeholder="Realisasi Penempatan">

<br><br>

<button onclick="simpanData()">Simpan</button>
<button onclick="resetForm()">Reset</button>

<hr>

<h3>Daftar Perusahaan Peserta Job Fair</h3>

<button onclick="getData()">Refresh Data</button>

<br><br>

<table border="1" cellpadding="10">
    <thead>
        <tr>
            <th>ID</th>
            <th>ID Job Fair</th>
            <th>ID Perusahaan Mitra</th>
            <th>Jumlah Lowongan</th>
            <th>Realisasi Penempatan</th>
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
    fetch('/api/job-fair-perusahaan', {
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
                    <td>${item.job_fair_id} ${item.job_fair ? '(' + item.job_fair.nama_kegiatan + ')' : ''}</td>
                    <td>${item.perusahaan_id} ${item.perusahaan ? '(' + item.perusahaan.nama_perusahaan + ')' : ''}</td>
                    <td>${item.jumlah_lowongan ?? '-'}</td>
                    <td>${item.realisasi_penempatan ?? '-'}</td>
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
        job_fair_id: document.getElementById('job_fair_id').value,
        perusahaan_id: document.getElementById('perusahaan_id').value,
        jumlah_lowongan: document.getElementById('jumlah_lowongan').value || null,
        realisasi_penempatan: document.getElementById('realisasi_penempatan').value || null
    };

    let url = '/api/job-fair-perusahaan';
    let method = 'POST';

    if(id){
        url = '/api/job-fair-perusahaan/' + id;
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
    fetch('/api/job-fair-perusahaan/' + id, {
        headers:{
            'Authorization':'Bearer ' + token,
            'Accept':'application/json'
        }
    })
    .then(res => res.json())
    .then(response => {
        const data = response.data;
        document.getElementById('id').value = data.id;
        document.getElementById('job_fair_id').value = data.job_fair_id;
        document.getElementById('perusahaan_id').value = data.perusahaan_id;
        document.getElementById('jumlah_lowongan').value = data.jumlah_lowongan ?? '';
        document.getElementById('realisasi_penempatan').value = data.realisasi_penempatan ?? '';
        document.getElementById('formTitle').innerText = 'Edit Perusahaan Peserta Job Fair';
    });
}

function hapusData(id){
    if(!confirm('Yakin ingin menghapus data ini?')){
        return;
    }
    fetch('/api/job-fair-perusahaan/' + id, {
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
    document.getElementById('job_fair_id').value = '';
    document.getElementById('perusahaan_id').value = '';
    document.getElementById('jumlah_lowongan').value = '';
    document.getElementById('realisasi_penempatan').value = '';
    document.getElementById('formTitle').innerText = 'Tambah Perusahaan Peserta Job Fair';
}
</script>
</body>
</html>
