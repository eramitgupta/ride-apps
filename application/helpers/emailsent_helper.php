<?php

if (!function_exists('emialSent')) {
	function emialSent($name ,$otp, $masg, $userEmail, $subject)
	{
		$CI = &get_instance();
		$CI->load->library('email');
		$data = $CI->Curd_model->Select('tbl_smtp');
		$config = array(
			'protocol' => $data[0]['protocol'],
			'smtp_host' => $data[0]['smtp_host'],
			'smtp_port' => $data[0]['smtp_port'],
			'smtp_user' => $data[0]['smtp_user_email'],
			'smtp_pass' => $data[0]['smtp_pass'],
			'crlf' => "\r\n",
			'mailtype'  => 'html',
			'charset'   => 'utf-8',
		);


		$logo = 'https://ridingmoto.eswa.in/' . 'public/logo-white.png';

		$message = <<<HTML
			<!doctype html>
				<html lang="en" dir="ltr">
					<head>
						<meta charset="utf-8" />
						<title>Email</title>
						<meta name="viewport" content="width=device-width, initial-scale=1.0">
						<!-- favicon -->
						<link rel="shortcut icon" href="assets/images/favicon.ico" />
						<!-- Font -->
						<link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700&display=swap" rel="stylesheet">
					</head>
				<body style="font-family: Nunito, sans-serif; font-size: 15px; font-weight: 400;">

					<!-- Hero Start -->
					<div style="margin-top: 50px;">
						<table cellpadding="0" cellspacing="0" style="font-family: Nunito, sans-serif; font-size: 15px; font-weight: 400; max-width: 600px; border: none; margin: 0 auto; border-radius: 6px; overflow: hidden; background-color: #fff; box-shadow: 0 0 3px rgba(60, 72, 88, 0.15);">
							<thead>
								<tr style="background-color:#000000; padding: 3px 0; border: none; line-height: 68px; text-align: center; color: #fff; font-size: 24px; letter-spacing: 1px;">
									<th scope="col"><img src="$logo" height="36" alt=""></th>
								</tr>
							</thead>

							<tbody>
								<tr>
									<td style="padding: 48px 24px 0; color: #161c2d; font-size: 18px; font-weight: 600;">
										$name
									</td>
								</tr>
								<tr>
									<td style="padding: 15px 24px 15px; color: #8492a6;">
										$masg
									</td>
								</tr>
								<tr>
									<td style="padding: 15px 24px 15px; color: #000000; font-size: 15px; font-weight: 500;">
										<center>$otp</center>
									</td>
								</tr>
								<tr>
									<td style="padding: 15px 24px 15px; color: #8492a6;">
										Riding Moto <br> Support Team
									</td>
								</tr>

								<tr>
									<td style="padding: 16px 8px; color: #8492a6; background-color: #f8f9fc; text-align: center;">
										© <script>
											document.write(new Date().getFullYear())
										</script> Riding Moto.
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<!-- Hero End -->
				</body>
			</html>
		HTML;

		$CI->email->initialize($config);
		$CI->email->from($data[0]['email'], $data[0]['title']);
		$CI->email->to($userEmail);
		$CI->email->subject($subject);
		$CI->email->message($message);
		$CI->load->library('email', $config);
		$send = $CI->email->send();
		if ($send) {
			return true;
		} else {
			return false;
		}
	}
}
