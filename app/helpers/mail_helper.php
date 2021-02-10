<?php

// sendMail("mavaraf506@dashseat.com", "test hassan");
function verification_mail($email, $token)
{
    $subject = "Email verification";
    $message = '
                    <h4>Hello dear,</h4>
                    <br>
                    <span>You can confirm your account email through the link below:</span> <br>
                    <a href="' . URLROOT . '/users/verify_email?email=' . $email . '&token=' . $token . '" >Confirm Email</a>
        ';
    $message = wordwrap($message, 200, "\r\n");
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    $res = mail($email, $subject, $message, $headers);
    return $res;
}

function forgetPassword_mail($email, $token)
{
    $subject = "Password Reset Request for Camagru";
    $message = '
                    <h4>Hello dear,</h4>
                    <br>
                    <span>
                    Your Camagru password can be reset by clicking the link below. If you did not request a new password, please ignore this email.
                    :</span> <br>
                    <a href="' . URLROOT . '/users/change_forgottenPassword/' . $token . '" >Reset password</a>
        ';
    $message = wordwrap($message, 200, "\r\n");
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    $res = mail($email, $subject, $message, $headers);
    return $res;
}

function comment_mail($email, $id)
{
    $subject = "Someone leave a comment on your post";
    $message = '
                    <h4>Hello dear,</h4>
                    <br>
                    <span>
                        Someone leave a comment on your picture ! check it out : 
                    </span><br>
                    <a href="' . URLROOT . '/posts/show/' . $id . '" >Click here</a>
        ';
    $message = wordwrap($message, 200, "\r\n");
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    $res = mail($email, $subject, $message, $headers);
    return $res;
}
