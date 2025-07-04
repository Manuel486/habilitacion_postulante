<?php

require_once __DIR__ . "/../../PHPMailer/PHPMailerAutoload.php";

function sendMailStatus($nombrePostulante, $correo, $pass, $cargo, $fase, $proyecto, $responsable, $correoResponsable)
{

    $destino = $correo;
    $origen = "fichas@sepcon.net";
    $password = "c9Hz8Nz4Zj5Or7Q";
    
    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->SMTPDebug = 0;
    $mail->Debugoutput = 'html';
    $mail->Host = 'mail.sepcon.net';
    $mail->SMTPAuth = true;
    $mail->Username = $origen;
    $mail->Password = $password;
    $mail->Port = 465;
    $mail->SMTPSecure = "ssl";
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            // 'verify_depth' => 3,
            'verify_peer_name' => false,
            'allow_self_signed' => true,
            'peer_name' => 'mail.sepcon.net',
        )
    );

    $mail->setFrom($origen, 'SEPCON-RRHH');
    $mail->addAddress($destino, "Postulante");
    $mail->addAddress($correoResponsable, "RRHH");

    $mail->Subject = utf8_decode('CODIGO DE ACCESO A LA PLATAFORMA DE RRHH - SEPCON');
    $message = "<html><body>";
    $message .= "<table width='100%' bgcolor='#e0e0e0' cellpadding='0' cellspacing='0' border='0'>";
    $message .= "<tr><td>";
    $message .= "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0' style='max-width:650px; background-color:#fff; font-family:Verdana, Geneva, sans-serif;'>";
    $message .= "<thead>
            <tr height='80'>
            <th colspan='4' style='background-color:#f5f5f5; border-bottom:solid 1px #bdbdbd; font-family:Verdana, Geneva, sans-serif; color:#004E8C; font-size:34px;' >Código de Confirmación</th>
            </tr></thead>";
    $message .= "<tbody><tr>
                <td colspan='4' style='padding:15px;'>
                    <p style='font-size:10px;'>Estimado(a): $nombrePostulante</p>
                    <hr />
                    <p style='font-size:12px;'>Le informamos que se está procediendo a dar inicio a su proceso de habilitación  y posterior ingreso al proyecto/Sede: $proyecto, fase/area de $fase, cargo de $cargo,</p>
                    <p style='font-size:12px;'>por lo cual usted deberá ingresar al siguiente enlace: </p>
                    <p style='font-size:12px;'><a href='https://rrhhperu.sepcon.net/postulantesv2/postulante/login'>https://rrhhperu.sepcon.net/postulantesv2/postulante/login</a>.</p>
                    <p style='font-size:12px;'><strong>Y digitar el siguiente usuario y contraseña</strong></p>
                    <p style='font-size:12px;'><p style='width: 100%; text-align: center;font-family:Verdana, Geneva, sans-serif; color:#004E8C; font-size:34px;'><strong>$pass</strong></p></p>
                    <p style='font-size:12px;'><strong>En el cual encontrara la relación de los documentos que deberá adjuntar como requisitos de Obligatorio antes de su ingreso.</strong></p>
                    <p style='font-size:12px;'><strong>Atte.</strong></p>
                    <p style='font-size:12px;'><strong>$responsable</strong></p>
                    <img src='../img/mail.jpg' style='height:auto; width:100%; max-width:100%;'/>
                    <p style='font-size:15px; font-family:Verdana, Geneva, sans-serif; text-align: center; '>R.R.H.H. - Registro de Postulantes</p>
                </td>
                </tr></tbody>";
    $message .= "</table>";
    $message .= "</td></tr>";
    $message .= "</table>";
    $message .= "</body></html>";

    $mail->msgHTML(utf8_decode($message));
    

    if ($mail->send()) {
        return true;
    } else {
        return false;
    }

    $mail->ClearAddresses();


    
}
