<!DOCTYPE html>
<html>
<head>
    <title>CRUD Pemagangan</title>
</head>
<body>

<h1>CRUD Pemagangan</h1>

<h3 id="formTitle">Tambah Pemagangan</h3>

<input type="hidden" id="id">

<input type="number" id="tenaga_kerja_id" placeholder="ID Tenaga Kerja">
<input type="number" id="perusahaan_id" placeholder="ID Perusahaan Mitra">
<input type="text" id="bidang" placeholder="Bidang">
<input type="text" id="durasi" placeholder="Durasi (misal: 6 Bulan)">
<label>Tanggal Mulai:</label>
<input type="date" id="tanggal_mulai">
<label>Tanggal Selesai:</label>
<input type="date" id="tanggal_selesai">
<select id="status">
    <option value="berjalan">Berjalan</option>
    <option value="selesai">Selesai</option>
</select>

<br><br>

<button onclick="simpanData()">Simpan</button>
<button onclick="resetForm()">Reset</button>

<hr>

<h3>Daftar Pemagangan</h3>

<button onclick="getData()">Refresh Data</button>

<br><br>

<table border="1" cellpadding="10">
    <thead>
        <tr>
            <th>ID</th>
            <th>ID Tenaga Kerja</th>
            <th>ID Perusahaan</th>
            <th>Bidang</th>
            <th>Durasi</th>
            <th>Tanggal Mulai</th>
            <th>Tanggal Selesai</th>
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
    fetch('/api/pemagangan', {
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
                    <td>${item.perusahaan_id} ${item.perusahaan ? '(' + item.perusahaan.nama_perusahaan + ')' : ''}</td>
                    <td>${item.bidang ?? '-'}</td>
                    <td>${item.durasi ?? '-'}</td>
                    <td>${item.tanggal_mulai ?? '-'}</td>
                    <td>${item.tanggal_selesai ?? '-'}</td>
                    <td>${item.status ?? '-'}</td>
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
        perusahaan_id: document.getElementById('perusahaan_id').value,
        bidang: document.getElementById('bidang').value,
        durasi: document.getElementById('durasi').value,
        tanggal_mulai: document.getElementById('tanggal_mulai').value,
        tanggal_selesai: document.getElementById('tanggal_selesai').value,
        status: document.getElementById('status').value
    };

    let url = '/api/pemagangan';
    let method = 'POST';

    if(id){
        url = '/api/pemagangan/' + id;
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
    fetch('/api/pemagangan/' + id, {
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
        document.getElementById('perusahaan_id').value = data.perusahaan_id;
        document.getElementById('bidang').value = data.bidang ?? '';
        document.getElementById('durasi').value = data.durasi ?? '';
        document.getElementById('tanggal_mulai').value = data.tanggal_mulai ?? '';
        document.getElementById('tanggal_selesai').value = data.tanggal_selesai ?? '';
        document.getElementById('status').value = data.status ?? 'berjalan';
        document.getElementById('formTitle').innerText = 'Edit Pemagangan';
    });
}

function hapusData(id){
    if(!confirm('Yakin ingin menghapus data ini?')){
        return;
    }
    fetch('/api/pemagangan/' + id, {
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
    document.getElementById('perusahaan_id').value = '';
    document.getElementById('bidang').value = '';
    document.getElementById('durasi').value = '';
    document.getElementById('tanggal_mulai').value = '';
    document.getElementById('tanggal_selesai').value = '';
    document.getElementById('status').value = 'berjalan';
    document.getElementById('formTitle').innerText = 'Tambah Pemagangan';
}
</script>
</body>
</html>
