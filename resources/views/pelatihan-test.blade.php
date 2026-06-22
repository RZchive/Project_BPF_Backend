<!DOCTYPE html>
<html>
<head>
    <title>CRUD Pelatihan</title>
</head>
<body>

<h1>CRUD Pelatihan</h1>

<h3 id="formTitle">Tambah Pelatihan</h3>

<input type="hidden" id="id">

<input type="number" id="lpk_id" placeholder="ID LPK">
<input type="text" id="nama_pelatihan" placeholder="Nama Pelatihan">
<input type="text" id="jenis_pelatihan" placeholder="Jenis Pelatihan">
<input type="text" id="jurusan" placeholder="Jurusan">
<input type="number" id="kuota" placeholder="Kuota">
<label>Tanggal Mulai:</label>
<input type="date" id="tanggal_mulai">
<label>Tanggal Selesai:</label>
<input type="date" id="tanggal_selesai">
<input type="text" id="deskripsi" placeholder="Deskripsi">
<select id="status">
    <option value="aktif">Aktif</option>
    <option value="selesai">Selesai</option>
</select>

<br><br>

<button onclick="simpanData()">Simpan</button>
<button onclick="resetForm()">Reset</button>

<hr>

<h3>Daftar Pelatihan</h3>

<button onclick="getData()">Refresh Data</button>

<br><br>

<table border="1" cellpadding="10">
    <thead>
        <tr>
            <th>ID</th>
            <th>ID LPK</th>
            <th>Nama Pelatihan</th>
            <th>Jenis</th>
            <th>Jurusan</th>
            <th>Kuota</th>
            <th>Mulai</th>
            <th>Selesai</th>
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
    fetch('/api/pelatihan', {
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
                    <td>${item.lpk_id} ${item.lpk ? '(' + item.lpk.nama_lpk + ')' : ''}</td>
                    <td>${item.nama_pelatihan ?? '-'}</td>
                    <td>${item.jenis_pelatihan ?? '-'}</td>
                    <td>${item.jurusan ?? '-'}</td>
                    <td>${item.kuota ?? '-'}</td>
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
        lpk_id: document.getElementById('lpk_id').value,
        nama_pelatihan: document.getElementById('nama_pelatihan').value,
        jenis_pelatihan: document.getElementById('jenis_pelatihan').value,
        jurusan: document.getElementById('jurusan').value,
        kuota: document.getElementById('kuota').value,
        tanggal_mulai: document.getElementById('tanggal_mulai').value,
        tanggal_selesai: document.getElementById('tanggal_selesai').value,
        deskripsi: document.getElementById('deskripsi').value || null,
        status: document.getElementById('status').value
    };

    let url = '/api/pelatihan';
    let method = 'POST';

    if(id){
        url = '/api/pelatihan/' + id;
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
    fetch('/api/pelatihan/' + id, {
        headers:{
            'Authorization':'Bearer ' + token,
            'Accept':'application/json'
        }
    })
    .then(res => res.json())
    .then(response => {
        const data = response.data;
        document.getElementById('id').value = data.id;
        document.getElementById('lpk_id').value = data.lpk_id;
        document.getElementById('nama_pelatihan').value = data.nama_pelatihan ?? '';
        document.getElementById('jenis_pelatihan').value = data.jenis_pelatihan ?? '';
        document.getElementById('jurusan').value = data.jurusan ?? '';
        document.getElementById('kuota').value = data.kuota ?? '';
        document.getElementById('tanggal_mulai').value = data.tanggal_mulai ?? '';
        document.getElementById('tanggal_selesai').value = data.tanggal_selesai ?? '';
        document.getElementById('deskripsi').value = data.deskripsi ?? '';
        document.getElementById('status').value = data.status ?? 'aktif';
        document.getElementById('formTitle').innerText = 'Edit Pelatihan';
    });
}

function hapusData(id){
    if(!confirm('Yakin ingin menghapus data ini?')){
        return;
    }
    fetch('/api/pelatihan/' + id, {
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
    document.getElementById('lpk_id').value = '';
    document.getElementById('nama_pelatihan').value = '';
    document.getElementById('jenis_pelatihan').value = '';
    document.getElementById('jurusan').value = '';
    document.getElementById('kuota').value = '';
    document.getElementById('tanggal_mulai').value = '';
    document.getElementById('tanggal_selesai').value = '';
    document.getElementById('deskripsi').value = '';
    document.getElementById('status').value = 'aktif';
    document.getElementById('formTitle').innerText = 'Tambah Pelatihan';
}
</script>
</body>
</html>
