<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Aplikasi Biodata Mahasiswa</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            height: 100vh; /* full layar */
            display: flex;
            justify-content: center; /* center horizontal */
            align-items: center;     /* center vertical */
            color: #333;
            background: linear-gradient(135deg, #74ebd5 0%, #9face6 100%);
        }

        /* Container card */
        .container {
            background: #fff;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.1);
            max-width: 550px;
            width: 100%;
            animation: fadeIn 0.6s ease-in-out;
        }

        h2 {
            margin-bottom: 15px;
            color: #2c3e50;
            text-align: center;
        }

        /* animasi card */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        form {
            margin-bottom: 25px;
        }

        label {
            font-weight: 600;
            display: block;
            margin: 10px 0 5px;
        }

        input[type="text"],
        select,
        textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            outline: none;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        input[type="text"]:focus,
        select:focus,
        textarea:focus {
            border-color: #3498db;
            box-shadow: 0 0 6px rgba(52, 152, 219, 0.4);
        }

        input[type="radio"],
        input[type="checkbox"] {
            margin-right: 6px;
        }

        textarea {
            resize: none;
        }

        input[type="submit"] {
            background: #3498db;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            margin-top: 10px;
            transition: background 0.3s, transform 0.2s;
        }

        input[type="submit"]:hover {
            background: #2980b9;
            transform: scale(1.05);
        }

        table {
            border-collapse: collapse;
            margin-top: 15px;
            width: 100%;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        table th {
            background: #3498db;
            color: white;
            text-align: left;
            padding: 10px;
            width: 160px;
        }

        table td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        .result {
            margin-top: 10px;
            font-weight: bold;
            color: #2c3e50;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Form Biodata Mahasiswa</h2>
        <!-- Form Biodata (POST) -->
        <form method="POST">
            <label>Nama Lengkap:</label>
            <input type="text" name="nama" required>

            <label>NIM:</label>
            <input type="text" name="nim" required>

            <label>Program Studi:</label>
            <select name="prodi">
                <option value="Informatika">Informatika</option>
                <option value="Sistem Informasi">Sistem Informasi</option>
                <option value="Teknik Elektro">Teknik Elektro</option>
            </select>

            <label>Jenis Kelamin:</label>
            <input type="radio" name="jk" value="Laki-laki" required> Laki-laki
            <input type="radio" name="jk" value="Perempuan" required> Perempuan

            <label>Hobi:</label>
            <input type="checkbox" name="hobi[]" value="Olahraga"> Olahraga
            <input type="checkbox" name="hobi[]" value="Membaca"> Membaca
            <input type="checkbox" name="hobi[]" value="Gaming"> Gaming
            <input type="checkbox" name="hobi[]" value="Musik"> Musik

            <label>Alamat:</label>
            <textarea name="alamat" rows="3" required></textarea>

            <input type="submit" value="Kirim Biodata">
        </form>

        <?php
        // Proses Form Biodata (POST)
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nama'])) {
            $nama = $_POST['nama'];
            $nim = $_POST['nim'];
            $prodi = $_POST['prodi'];
            $jk = $_POST['jk'];
            $hobi = isset($_POST['hobi']) ? implode(", ", $_POST['hobi']) : "-";
            $alamat = $_POST['alamat'];

            echo "<h3>Hasil Biodata:</h3>";
            echo "<table>
                    <tr><th>Nama Lengkap</th><td>$nama</td></tr>
                    <tr><th>NIM</th><td>$nim</td></tr>
                    <tr><th>Program Studi</th><td>$prodi</td></tr>
                    <tr><th>Jenis Kelamin</th><td>$jk</td></tr>
                    <tr><th>Hobi</th><td>$hobi</td></tr>
                    <tr><th>Alamat</th><td>$alamat</td></tr>
                  </table>";
        }
        ?>

        <h2>Form Pencarian</h2>
        <!-- Form Pencarian (GET) -->
        <form method="GET">
            <label>Kata Kunci:</label>
            <input type="text" name="keyword" required>
            <input type="submit" value="Cari">
        </form>

        <?php
        // Proses Form Pencarian (GET)
        if (isset($_GET['keyword'])) {
            $keyword = htmlspecialchars($_GET['keyword']);
            echo "<div class='result'>Anda mencari data dengan kata kunci: <b>$keyword</b></div>";
        }
        ?>
    </div>

</body>
</html>
