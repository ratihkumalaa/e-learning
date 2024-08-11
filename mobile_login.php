<?php
require("config/config.default.php");
require("config/config.ocbt.php");

session_start(); // Pastikan session dimulai

// Redirect user yang sudah login
if (isset($_SESSION['id_pengawas'])) {
    echo "<script>window.location = '". $homeurl ."/admin/index.php'; </script>";
    exit();
}

if (isset($_SESSION['id_siswa'])) {
    echo "<script>window.location = '". $homeurl ."/index.php'; </script>";
    exit();
}

if (isset($_POST['username']) && isset($_POST['password'])) {
    // Masuk cek user sebagai siswa atau admin
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];
    $mobile = isset($_POST['mobile']) ? $_POST['mobile'] : '';

    $query = mysqli_query($koneksi, "SELECT * FROM siswa WHERE username='$username'");

    if ($query && mysqli_num_rows($query) > 0) {
        $query = mysqli_fetch_array($query);
        
        if ($query['password'] == $password) {
            $_SESSION['id_siswa'] = $query['id_siswa'];
            $_SESSION['level'] = 'siswa';
            $_SESSION['nama'] = $query['nama'];
            $_SESSION['id_kelas'] = $query['id_kelas'];
            
            if ($mobile == 'android' || $mobile == 'ios') {
                $_SESSION['is_mobile'] = $mobile;
            }
            
            echo "<script>window.location = '". $homeurl ."'; </script>";
            exit();
        } else {
            echo "<script>alert('Username atau password salah'); </script>";
        }
    } else {
        $query = mysqli_query($koneksi, "SELECT * FROM pengawas WHERE username='$username'");
        
        if ($query && mysqli_num_rows($query) > 0) {
            $query = mysqli_fetch_array($query);
            $password_valid = ($query['level'] != 'guru') ? password_verify($password, $query['password']) : ($password == $query['password']);
            
            if ($password_valid) {
                $_SESSION['id_pengawas'] = $query['id_pengawas'];
                $_SESSION['level'] = $query['level'];
                $_SESSION['nama'] = $query['nama'];
                
                if ($mobile == 'android' || $mobile == 'ios') {
                    $_SESSION['is_mobile'] = $mobile;
                }
                
                echo "<script>window.location = '". $homeurl ."/admin'; </script>";
                exit();
            } else {
                echo "<script>alert('Username atau password salah'); </script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="E-Learning SMK Satya Bhakti">
    <meta name="author" content="e-learning">
    <title>E-Learning SMK Satya Bhakti</title>
    <link href="<?= $homeurl ?>/dist/bootstrap-4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> 
    <link rel="icon" type="image/png" sizes="192x192" href="<?php echo $homeurl; ?>/dist/img/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $homeurl; ?>/dist/img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="<?php echo $homeurl; ?>/dist/img/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $homeurl; ?>/dist/img/favicon-16x16.png">
    <style>
        body { 
            font-size: 14px; 
            line-height: 1.6; 
            color: #000;
        }
        .container-xxl { 
            max-width: 1200px; 
            margin: 0 auto; 
            padding: 20px; 
        }
        .container-xxl section { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
        }
        .banner img { 
            width: 100%; 
            max-width: 800px; 
            height: auto; 
        }
        .logo img { 
            max-width: 250px; 
            height: auto; 
        }
        .content { 
            margin-top: 25px; 
            display: flex; 
            justify-content: space-between; 
        }
        .content .col-md-8 { 
            flex: 2; 
        }
        nav { 
            margin-top: 20px; 
            background-color: #09619F; 
            padding: 10px; 
            border-radius: 5px; 
        }
        .navbar-brand, .nav-link { 
            color: white !important; 
            font-size: 16px; 
        }
        .col-md-8{
            margin-top:-8em;
        }
        .form-signin { 
            width: 100%; 
            max-width: 400px; 
            padding: 15px; 
            margin: auto; 
            z-index: 2; 
        }
        .timeline { 
            margin-top: 20px; 
            padding-left: 0; 
        }
        .timeline li { 
            list-style: none; 
        }
        .timeline-item { 
            border-left: 1px solid #e0e0e0; 
            padding-left: 15px; 
            margin-left: 10px; 
        }
        .timeline-header { 
            font-size: 16px; 
            margin-bottom: 10px; 
        }
        .timeline-body { 
            margin: 10px 0; 
        }
        .bg-green { 
            background-color: #d4edda; 
        }
        .text-green { 
            color: #155724; 
        }
        .bg-blue { 
            background-color: #cce5ff; 
        }
        .text-blue { 
            color: #004085; 
        }
        .video-container { 
            margin-top: 30px; 
            text-align: center; 
        }
        .video-container iframe { 
            width: 322px; 
            height: 176px; 
        }
        .banner img,
        .video-container iframe {
            width: 100%;
            height: auto;
        }
        .timeline {
            padding: 0;
            list-style: none;
        }

        .timeline-item {
            position: relative;
            padding-left: 20px;
            margin-bottom: 20px;
        }

        .timeline-item:before {
            content: "";
            position: absolute;
            left: 0;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #ddd;
        }

        footer {
            background-color: #09619F;
            color: white;
            text-align: center;
            padding: 10px;
            margin-top: 30px;
            border-radius: 5px;
            font-size: 14px;
        }
        @media (max-width: 768px) {
            .container-xxl {
                padding: 10px;
            }
            .banner img {
                max-width: 100%;
            }
            .logo img {
                max-width: 200px;
            }
            .content {
                flex-direction: column;
            }
            .content .col-md-8 {
                margin-top: 0;
            }
            .video-container iframe {
                width: 100%;
                height: auto;
            }
            .form-signin {
                width: 100%;
                max-width: 100%;
            }
            @media (max-width: 576px) {
            .container {
                padding: 10px;
            }
            .banner img {
                max-width: 100%;
            }
            .logo img {
                max-width: 150px;
            }
            .form-signin {
                width: 100%;
                max-width: 100%;
            }
            .video-container iframe {
                width: 100%;
                height: auto;
            }
        }
        }
    </style>
</head>
<body>
    <div class="container-xxl">
        <section>
            <div class="banner">
                <img src="<?= $homeurl ?>/dist/img/banner1.png" alt="Banner">
            </div>
            <div class="logo">
                <img src="<?= $homeurl ?>/dist/img/logo.png" alt="Logo">
            </div>
        </section>

        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container-fluid">
                <a class="navbar-brand text-light bg-grey" href="#">Halaman Utama</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link active text-light" aria-current="page" href="https://smksbi.com/">Profil</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>   

        <section class="content">
            <div class='col-md-8'>
                <div class='box box-solid'>
                    <div class='box-header with-border'>
                        <h3 class='box-title'><i class="fas fa-bullhorn announcement-icon"></i> Pengumuman</h3>
                    </div>
                    <div class='box-body'>
                        <div id='pengumuman'>
                        <?php
                            echo "<ul class='timeline'><br>";
                            // Query untuk mengambil semua pengumuman
                            $logQ = mysqli_query($koneksi, "SELECT * FROM pengumuman ORDER BY date DESC");

                            if (mysqli_num_rows($logQ) == 0) {
                                echo "<p class='text-center'>Tidak ada pengumuman.</p>";
                            } else {
                                while ($log = mysqli_fetch_array($logQ)) {
                                    // Ambil data pengguna yang mengirimkan pengumuman
                                    $userQuery = mysqli_query($koneksi, "SELECT * FROM pengawas WHERE id_pengawas='" . $log['user'] . "'");
                                    $user = mysqli_fetch_array($userQuery);

                                    // Menentukan warna latar belakang dan warna teks berdasarkan jenis pengumuman
                                    $bg = ($log['type'] == 'internal') ? 'bg-green' : 'bg-blue';
                                    $color = ($log['type'] == 'internal') ? 'text-green' : 'text-blue';

                                    // Menampilkan data pengumuman
                                    echo "
                                        <li>
                                            <i class='fa fa-envelope $bg'></i>
                                            <div class='timeline-item'>
                                                <span class='time'> 
                                                    <i class='fa fa-calendar'></i> " . date('d-m-Y', strtotime($log['date'])) . " 
                                                    <i class='fa fa-clock-o'></i> " . date('H:i', strtotime($log['date'])) . "
                                                </span>
                                                <h3 class='timeline-header' style='background-color:#f9f0d5'>
                                                    <a class='$color' href='#'>$log[judul]</a> 
                                                    <small> $user[nama]</small>
                                                </h3>
                                                <div class='timeline-body'>
                                                    " . ucfirst($log['text']) . "
                                                </div>
                                            </div>
                                        </li>";
                                }
                            }
                            echo "</ul>";
                        ?>
                        </div>
                    </div>
                </div>
            </div>
            <aside id="particles">
                <form class="form-signin" action="<?= $homeurl ?>/mobile_login.php" method="POST" name="login">
                    <label for="inputEmail" class="sr-only">Username</label>
                    <input type="text" name="username" id="inputUsername" class="form-control mb-2" placeholder="Username" required="" autofocus="">
                    <label for="inputPassword" class="sr-only">Password</label>
                    <input type="password" name="password" id="inputPassword" class="form-control mb-2" placeholder="Password" required="">
                    <input type="hidden" name="mobile" value="<?= $_GET['mobile'] ?>">
                    <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
                    <p class="mt-3" style="margin-bottom: 0;">
                        <a href="registrasi.php">Belum punya akun ? Daftar disini.</a>
                    </p>
                </form>
                <!-- Menambahkan video YouTube di bawah halaman login -->
                <div class="video-container" style="height: 200px;">
                    <iframe src="https://www.youtube.com/embed/YOUR_VIDEO_ID" frameborder="0" allowfullscreen></iframe>
                </div>
            </aside>
        </section>
    </div>
    <!-- Footer -->
    <footer>
        <p>&copy; 2023 E-Learning SMK Satya Bhakti Ilmu</p> 
    </footer>


    <script src="<?= $homeurl ?>/dist/vendor/particles.js-master/particles.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="<?= $homeurl ?>/dist/bootstrap-4.5.2/js/bootstrap.min.js"></script>

    <script>
        particlesJS('particles', {
            "particles": {
                "number": {
                    "value": 80,
                    "density": {
                        "enable": true,
                        "value_area": 1499
                    }
                },
                "color": {
                    "value": "#797fed"
                },
                "shape": {
                    "type": "circle",
                    "stroke": {
                        "width": 0,
                        "color": "#000000"
                    },
                    "polygon": {
                        "nb_sides": 5
                    }
                },
                "opacity": {
                    "value": 0.5,
                    "random": false,
                    "anim": {
                        "enable": false,
                        "speed": 1,
                        "opacity_min": 0.1,
                        "sync": false
                    }
                },
                "size": {
                    "value": 10,
                    "random": true,
                    "anim": {
                        "enable": false,
                        "speed": 80,
                        "size_min": 0.1,
                        "sync": false
                    }
                },
                "line_linked": {
                    "enable": true,
                    "distance": 150,
                    "color": "#797fed",
                    "opacity": 0.4,
                    "width": 1
                },
                "move": {
                    "enable": true,
                    "speed": 6,
                    "direction": "none",
                    "random": false,
                    "straight": false,
                    "out_mode": "out",
                    "bounce": false,
                    "attract": {
                        "enable": false,
                        "rotateX": 600,
                        "rotateY": 1200
                    }
                }
            },
            "interactivity": {
                "detect_on": "canvas",
                "events": {
                    "onhover": {
                        "enable": true,
                        "mode": "repulse"
                    },
                    "onclick": {
                        "enable": true,
                        "mode": "push"
                    },
                    "resize": true
                },
                "modes": {
                    "grab": {
                        "distance": 800,
                        "line_linked": {
                            "opacity": 1
                        }
                    },
                    "bubble": {
                        "distance": 800,
                        "size": 80,
                        "duration": 2,
                        "opacity": 0.8,
                        "speed": 3
                    },
                    "repulse": {
                        "distance": 150,
                        "duration": 0.4
                    },
                    "push": {
                        "particles_nb": 4
                    },
                    "remove": {
                        "particles_nb": 2
                    }
                }
            },
            "retina_detect": true
        });
    </script>
</body>

</html>

