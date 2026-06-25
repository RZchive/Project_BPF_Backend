<!DOCTYPE html>
<html>
<head>
    <title>CRUD Laporan</title>
</head>
<body>

<h1>CRUD Laporan</h1>

<h3 id="formTitle">Tambah Laporan</h3>

<input type="hidden" id="id">

<input type="number" id="user_id" placeholder="ID User">
<input type="text" id="jenis_laporan" placeholder="Jenis Laporan">
<select id="format_file">
    <option value="pdf">PDF</option>
    <option value="excel">Excel</option>
</select>

<br><br>

<button onclick="simpanData()">Simpan</button>
<button onclick="resetForm()">Reset</button>

<hr>

<h3>Daftar Laporan</h3>

<button onclick="getData()">Refresh Data</button>

<br><br>

<table border="1" cellpadding="10">
    <thead>
        <tr>
            <th>ID</th>
            <th>ID User</th>
            <th>Jenis Laporan</th>
            <th>Format File</th>
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
    fetch('/api/laporan', {
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
                    <td>${item.user_id} ${item.user ? '(' + item.user.nama + ')' : ''}</td>
                    <td>${item.jenis_laporan ?? '-'}</td>
                    <td>${item.format_file ?? '-'}</td>
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
        user_id: document.getElementById('user_id').value,
        jenis_laporan: document.getElementById('jenis_laporan').value,
        format_file: document.getElementById('format_file').value
    };

    let url = '/api/laporan';
    let method = 'POST';

    if(id){
        url = '/api/laporan/' + id;
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
    fetch('/api/laporan/' + id, {
        headers:{
            'Authorization':'Bearer ' + token,
            'Accept':'application/json'
        }
    })
    .then(res => res.json())
    .then(response => {
        const data = response.data;
        document.getElementById('id').value = data.id;
        document.getElementById('user_id').value = data.user_id;
        document.getElementById('jenis_laporan').value = data.jenis_laporan ?? '';
        document.getElementById('format_file').value = data.format_file ?? 'pdf';
        document.getElementById('formTitle').innerText = 'Edit Laporan';
    });
}

function hapusData(id){
    if(!confirm('Yakin ingin menghapus data ini?')){
        return;
    }
    fetch('/api/laporan/' + id, {
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
    document.getElementById('user_id').value = '';
    document.getElementById('jenis_laporan').value = '';
    document.getElementById('format_file').value = 'pdf';
    document.getElementById('formTitle').innerText = 'Tambah Laporan';
}
</script>
</body>
</html>
