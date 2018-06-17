<?php

require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'class.phpmailer.php';

class yiiPhpMailer extends PHPMailer
{	
	/**
	 * [pushMail description]
	 * @param  [type] $subject [description]
	 * @param  [type] $message [description]
	 * @param  [type] $address [description]
	 * @return [type]          [description]
	 */
	public static function pushMail($subject, $message, $address)
	{
		if (isset($address['email']) && isset($address['name']))
		{
			$email = new Emails;
			$email->subject = $subject;
			$email->body = $message;
			$email->status = 0; // Estaba puesto el valor 3, pero la funcion process/sendmails busca status 0
			$email->creationDate = date("Y-m-d G:i:s");
			$email->toName = $address['name'];
			$email->toMail = $address['email'];
			$email->save(false);
		}
		else 
		{
			for ($i=0; $i<count($address); $i++)
			{ 
				$email = new Emails;
				$email->subject = $subject;
				$email->body = $message;
				
				if (is_array($address[$i]))
				{
					$email->toName = $address[$i]['name'];
					$email->toMail = $address[$i]['email'];
					$email->save(false);
				}
				else
				{
					$email->toName = str_replace('"','',$address[$i]);
					$email->toMail = str_replace('"','',$address[$i]);
					$email->save(false);
				}
			}
		}

		return true;
	}
	
	/**
	 * [Ready description]
	 * @param [type] $subject        [description]
	 * @param [type] $message        [description]
	 * @param [type] $address        [description]
	 * @param [type] $attachFilePath [description]
	 */
	public function Ready($subject, $message, $address, $attachFilePath = null)
	{
		$exito = false;
		$mailer = new PhpMailer;
		$mailer->IsSMTP();
		$mailer->Host = Yii::app()->params['mailHost'];
		$mailer->SMTPAuth = Yii::app()->params['mailSMTPAuth'];
		$mailer->Username = Yii::app()->params['mailUsername'];
		$mailer->Password = Yii::app()->params['mailPassword'];
		$mailer->From = Yii::app()->params['mailSenderEmail'];
		$mailer->FromName = Yii::app()->params['mailSenderName'];
		$mailer->CharSet = 'UTF-8';
		$mailer->AltBody = 'To view the message, please use an HTML compatible email viewer!';
		
		if (isset($address['email']) && isset($address['name']))
		{
			// Esto permite que se detecte direcciones del tipo test@mail.com;otro@mail.com y envie 2 o mas correos
			$pos = strrpos($address['email'], ';');
			if ($pos !== false) // se encuentra el ;
			{
				$porciones = explode(';', $address['email']);
				foreach ($porciones as $clave => $valor)
				{
					$mailer->AddAddress($valor, $address['name']);
				}
			}
			else // no hay ; entonces es una sola direccion
			{
				$mailer->AddAddress($address['email'], $address['name']);
			}
		}
		else 
		{
			for ($i=0; $i<count($address); $i++) 
			{
				$mailer->AddBCC(str_replace('"','',$address[$i]), str_replace('"','',$address[$i]));
			}
		}
		
		$mailer->Subject = $subject;
		$mailer->Body = $message;
		
		if ($attachFilePath != null)
		{
			$mailer->AddAttachment($attachFilePath);
		}
		
		$exito = $mailer->Send();

		if (!$exito)
		{
			$message = $mailer->ErrorInfo;
			Yii::log($message, CLogger::LEVEL_ERROR, 'application');
		}

		/*if (!$exito) 
		{
			$cabeceras = 'From: ' . Yii::app()->params['mailSenderEmail'] . "\r\n" .
				'Reply-To: ' . Yii::app()->params['mailSenderEmail'] . "\r\n" .
				'X-Mailer: PHP/' . phpversion() . "\r\n";
			$cabeceras .= 'MIME-Version: 1.0' . "\r\n";
			$cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

			//$exito = mail($address['email'], $subject, $message, $cabeceras);
			
			if (isset($address['email']) && isset($address['name']))
			{
				$exito = mail($address['email'], $subject, $message, $cabeceras);
			}
			else 
			{
				for ($i=0; $i<count($address); $i++)
				{
					$exito = mail(str_replace('"','',$address[$i]), $subject, $message, $cabeceras);
				}

				$exito = true;
			}
		}*/
		
		$mailer->ClearAddresses();
		
		return $exito;
	}
}