<?php

require_once '../model/alumno.php';

use \PHPMailer\PHPMailer\Exception;
use \PHPMailer\PHPMailer\PHPMailer;
require '../static/resources/PHPMailer/src/PHPMailer.php';
require '../static/resources/PHPMailer/src/Exception.php';
require '../static/resources/PHPMailer/src/SMTP.php';

$asunto = $_POST['asunto'];
$cuerpo = $_POST['cuerpo'];
$correo = $_POST['correo'];

if ($_POST['grupo'] != 'none') {
    $correo = $_POST['grupo'];
}


function sendMail($asunto, $cuerpo, $correo/*, $adjunto=null*/) {
    
    $email = new PHPMailer(true);
    $email->isSMTP();
    $email->Host = 'smtp.gmail.com';
    $email->Port = 587;
    $email->SMTPSecure = 'tls';
    $email->SMTPAuth = true;
    // $email->Username = 'cahm.secretaria@gmail.com';
    $email->Username = 'hectorjimenezrafael18@gmail.com';
    // $email->Password = 'Contra12';
    // $email->Password = 'xqzhntrjanixgywh';
    $email->Password = 'udpbevfoqtehwsoj';
    
    $email->isHTML(true);
    $email->CharSet = 'UTF-8';
    // $email->SetFrom('cahm.secretaria@gmail.com');
    $email->SetFrom('hectorjimenezrafael18@gmail.com');
    $email->Subject=$asunto;
    $email->Body=$cuerpo;
    
    require_once '../proc/func.php';
    require_once '../config/conexion.php';
    
    // comprovar que se haya seleccionado un alumno o una clase

    // hacer array de las ids de los modulos
    $modulos = Alumno::getModulos($conexion);
    $arrayModulosIds = [];
    foreach ($modulos as $value) {
        array_push($arrayModulosIds, $value['id_modulo']);
    }

    if (in_array($correo, $arrayModulosIds)) {
        $lista_alumnos = Alumno::getEmailAlumnosDeModulo($correo, $conexion);
        
        foreach ($lista_alumnos as $correo_alumno) {
            $email->AddAddress($correo_alumno);
        }
    } else {
        foreach (explode(',',$correo) as $value) {
            $email->AddAddress($value);
        }
    }

    // COMPROBAR SI HAY ARCHIVOS ADJUNTOS Y ENVIARLOS
    // if ($adjunto != null) {
    //     $email->AddAttachment( $adjunto );
    //     $email->Send();
    //     unlink($adjunto);
    // } else {
    //     $email->Send();
    // }

    $email->send();
    // mail($correo, $asunto, $cuerpo);
}

sendMail($asunto, $cuerpo, $correo);
echo "<script>window.location.href='../controller/index_controller.php?correo_enviado=correo_enviado'</script>";
