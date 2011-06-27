<?php /*====================================================================================
		SamNews [http://samjlevy.com/samnews], open-source PHP social news application
    	sam j levy [http://samjlevy.com]

    	This program is free software: you can redistribute it and/or modify it under the
    	terms of the GNU General Public License as published by the Free Software
    	Foundation, either version 3 of the License, or (at your option) any later
    	version.

    	This program is distributed in the hope that it will be useful, but WITHOUT ANY
    	WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
    	PARTICULAR PURPOSE.  See the GNU General Public License for more details.

    	You should have received a copy of the GNU General Public License along with this
    	program.  If not, see <http://www.gnu.org/licenses/>.
      ====================================================================================*/

function emailer($to,$subject,$message) {
	require_once(CLASSES_PATH . 'phpmailer/class.phpmailer.php');

	$mail = new PHPMailer();

	/* uncomment if you want to use SMTP
	$mail->IsSMTP();
	$mail->SMTPAuth   = true;
	$mail->Host       = "";
	$mail->Port       = 25;
	$mail->Username   = "";
	$mail->Password   = "";
	*/

	$mail->SetFrom(OUTGOING_EMAIL,SITE_NAME);
	$mail->AddReplyTo(OUTGOING_EMAIL,SITE_NAME);
	$mail->AddAddress($to);
	$mail->Subject = $subject;
	$mail->MsgHTML($message);

	// make plain-text alternate body
	$altbody = str_replace("</title>","\n\n",$message);
	$altbody = str_replace("</p>","\n\n",$altbody);
	$altbody = str_replace("<br />","\n",$altbody);
	$mail->AltBody = strip_tags($altbody);
	
	// send e-mail
	if($mail->Send()) {
		return true;
	} else {
		echo "Error sending email: " . $mail->ErrorInfo;
		return false;
	}
}
?>