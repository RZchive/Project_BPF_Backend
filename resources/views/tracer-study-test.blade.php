<!DOCTYPE html>
<html>
<head>
    <title>CRUD Tracer Study</title>
</head>
<body>

<h1>CRUD Tracer Study</h1>

<h3 id="formTitle">Tambah Tracer Study</h3>

<input type="hidden" id="id">

<input type="number" id="tenaga_kerja_id" placeholder="ID Tenaga Kerja">
<select id="status_alumni">
    <option value="bekerja_sesuai_bidang">Bekerja Sesuai Bidang</option>
    <option value="membuka_usaha">Membuka Usaha</option>
    <option value="belum_bekerja">Belum Bekerja</option>
</select>
<input type="text" id="nama_perusahaan" placeholder="Nama Perusahaan">
<input type="text" id="jabatan" placeholder="Jabatan">
<input type="text" id="gaji" placeholder="Gaji">
<input type="text" id="keterangan" placeholder="Keterangan">
<label>Tanggal Update:</label>
<input type="date" id="tanggal_update">

<br><br>

<button onclick="simpanData()">Simpan</button>
<button onclick="resetForm()">Reset</button>

<hr>

<h3>Daftar Tracer Study</h3>

<button onclick="getData()">Refresh Data</button>

<br><br>

<table border="1" cellpadding="10">
    <thead>
        <tr>
            <th>ID</th>
            <th>ID Tenaga Kerja</th>
            <th>Status Alumni</th>
            <th>Nama Perusahaan</th>
            <th>Jabatan</th>
            <th>Gaji</th>
            <th>Keterangan</th>
            <th>Tanggal Update</th>
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
    fetch('/api/tracer-study', {
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
                    <td>${item.status_alumni ?? '-'}</td>
                    <td>${item.nama_perusahaan ?? '-'}</td>
                    <td>${item.jabatan ?? '-'}</td>
                    <td>${item.gaji ?? '-'}</td>
                    <td>${item.keterangan ?? '-'}</td>
                    <td>${item.tanggal_update ?? '-'}</td>
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
        status_alumni: document.getElementById('status_alumni').value,
        nama_perusahaan: document.getElementById('nama_perusahaan').value || null,
        jabatan: document.getElementById('jabatan').value || null,
        gaji: document.getElementById('gaji').value || null,
        keterangan: document.getElementById('keterangan').value || null,
        tanggal_update: document.getElementById('tanggal_update').value || null
    };

    let url = '/api/tracer-study';
    let method = 'POST';

    if(id){
        url = '/api/tracer-study/' + id;
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
    fetch('/api/tracer-study/' + id, {
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
        document.getElementById('status_alumni').value = data.status_alumni ?? 'bekerja_sesuai_bidang';
        document.getElementById('nama_perusahaan').value = data.nama_perusahaan ?? '';
        document.getElementById('jabatan').value = data.jabatan ?? '';
        document.getElementById('gaji').value = data.gaji ?? '';
        document.getElementById('keterangan').value = data.keterangan ?? '';
        document.getElementById('tanggal_update').value = data.tanggal_update ?? '';
        document.getElementById('formTitle').innerText = 'Edit Tracer Study';
    });
}

function hapusData(id){
    if(!confirm('Yakin ingin menghapus data ini?')){
        return;
    }
    fetch('/api/tracer-study/' + id, {
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
    document.getElementById('status_alumni').value = 'bekerja_sesuai_bidang';
    document.getElementById('nama_perusahaan').value = '';
    document.getElementById('jabatan').value = '';
    document.getElementById('gaji').value = '';
    document.getElementById('keterangan').value = '';
    document.getElementById('tanggal_update').value = '';
    document.getElementById('formTitle').innerText = 'Tambah Tracer Study';
}
</script>
</body>
</html>
