const express = require('express');
const mysql = require('mysql');
const cors = require('cors');
const path = require('path');

const app = express();
const port = 5000;
// app.use(express.static(path.join(__dirname, '../../../public')));
app.use(cors());

//Konfigurasi ke database
const connection = mysql.createConnection({
    host: 'gis_2105551157.local.net',
    user: '2105551157',
    password: '2105551157',
    database: 'db_2105551157',
    port: '3306'
});


// Membuat koneksi kedatabase
connection.connect((err) => {
    if(err) {
        console.error('Error Connect to Database...', err)
        return;
    }
    console.log('Connected to Database...')
});

// Menambahkan endpoint
app.get('/getData', (req, res) => {
    //Query 
    const sql = 'SELECT * FROM tb_rs'
    
    connection.query(sql, (err,result)=> {
        if(err) {
            console.error('Error executing Query', err);
            res.status(500).send('Internal Server Error');
            return;
        }
    
        console.log('Data from SQL')
        console.log(result)
    
        //Mengirim data sebagai response
        res.json(result);
    });
    
});

// Endpoint untuk mengirimkan gambar dari database ke klien
app.get("/getImage/:id_rs", (req, res) => {
    const id = req.params.id_rs;
    const sql = "SELECT foto_rs FROM tb_rs WHERE id_rs = ?";
  
    // Eksekusi query untuk mengambil gambar dari database
    connection.query(sql, [id], (err, results) => {
      if (err) {
        console.error("Error executing SQL query:", err);
        res.status(500).send("Internal Server Error");
        return;
      }
  
      if (results.length === 0) {
        res.status(404).send("Image not found");
        return;
      }
  
      // Kirim gambar dalam format Base64 ke klien
      res.send(results[0].foto_rs);
    });
  });

app.listen(port, () => {
  console.log(`Server running at http://localhost:${port}`)
});