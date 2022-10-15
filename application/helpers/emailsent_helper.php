<?php

 if(! function_exists('emialSent'))
 {
	function emialSent($otp, $masg , $userEmail,$subject)
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


         $logo = 'https://ridingmoto.eswa.in/'.'public/logo.png';

		$message = <<<HTML
					<!DOCTYPE html>
					<html lang="en">

					<head>
						<meta charset="UTF-8">
						<meta http-equiv="X-UA-Compatible" content="IE=edge">
						<meta name="viewport" content="width=device-width, initial-scale=1.0">
						<title>Email</title>
					</head>

					<body>
						<div class="row">
							<table class="body-wrap"
								style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; background-color: transparent; margin: 0;">
								<tr
									style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
									<td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;"
										valign="top"></td>
									<td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; display: block !important; max-width: 1000px !important; clear: both !important; margin: 0 auto;"
										valign="top">
										<div class="content"
											style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; max-width: 800px; display: block; margin: 0 auto; padding: 0px;">
											<table class="main" width="100%" cellpadding="0" cellspacing="0"
												style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; border-radius: 3px; margin: 0; border: none;">
												<tr
													style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
													<td class="content-wrap"
														style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; color: #495057; font-size: 14px; vertical-align: top; margin: 0;padding: 30px; box-shadow: 0 0.75rem 1.5rem rgba(18,38,63,.03); ;border-radius: 7px; background-color: #fff;"
														valign="top">
														<meta itemprop="name" content="Confirm Email"
															style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;" />
														<table width="100%" cellpadding="0" cellspacing="0"
															style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
															<tr
																style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
																<td class="content-block"
																	style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;"
																	valign="top">
																	<center>
																		<img src="$logo" alt=""
																			style="height: 60px; width: auto;">
																	</center>
																</td>
															</tr>
															<tr
																style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
																<td class="content-block"
																	style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;"
																	valign="top">
																	$masg
																</td>
															</tr>
															<tr
																style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
																<td class="content-block"
																	style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;"
																	valign="top">
																	<center>
																		<a
																			style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 18px; color: #FFF; text-decoration: none; line-height: 2em; font-weight: bold; text-align: center; cursor: pointer; display: inline-block; border-radius: 5px; text-transform: capitalize; background-color: #34c38f; margin: 0; border-color: #34c38f; border-style: solid; border-width: 8px 16px; margin-top: 20px;">
																			$otp
																		</a>
																	</center>
																</td>
															</tr>

															<tr
																style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
																<td class="content-block"
																	style="text-align: center;font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0; "
																	valign="top">
																	<br>
																	© 2021 Riding Moto <a href="https://swasoftech.com/" target="_blank"
																		style="text-decoration: none;">Design & Develop By Swasoftech Pvt.
																		Ltd.</a>
																</td>
															</tr>
														</table>
													</td>
												</tr>
											</table>
										</div>
									</td>
								</tr>
							</table>
						</div>
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
