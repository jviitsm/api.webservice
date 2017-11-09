<?php


use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use PHPMailer\PHPMailer\PHPMailer;

$app->post('/email', function (Request $request, Response $response) use ($app) {


    $entityManager = $this->get('em');

    //Atributos
    $id = $request->getParam('id_login');

    //Buscar login
    $loginRepository = $entityManager->getRepository('App\Models\Entity\Login');
    $login = $loginRepository->find($id);

    //Buscar email no login
    $email =  $login->getEmail();

    //Gerar senha randomica
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $novaSenha =  substr(str_shuffle($chars),0,8);

    //Criptografar senha randomica
    $login->setSenha($novaSenha);
    $body = $novaSenha;
    //Salvar nova senha no banco
    $entityManager->merge($login);
    $entityManager->flush();

    //Envio de e-mail
    $mail = new PHPMailer(true);
    $mail = new PHPMailer;
    # $mail->isSMTP();
    $mail->SMTPDebug = 2;
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->SMTPSecure = 'tls';
    $mail->SMTPAuth = true;
    $mail->Username = "projetocitycare@gmail.com";
    $mail->Password = "citycare123";
    $mail->setFrom("projetocitycare@gmail.com", 'City Care');
    $mail->addAddress($email);
    $mail->Subject = 'Nova Senha';
    $mail->Body = '<html>
<body>

<table align="center" border="0" cellpadding="0" cellspacing="0" style="width:600px">
	<tbody>
		<tr>
			<td align="center" style="background-color:#2196f3;padding-bottom: 10px"><img src="http://projetocitycare.com.br/Imgs/Email/CityCare_logo.png" style="display:block" /></td>
		</tr>
		<tr>
			<td style="background-color:#ffffff;font-family: arial;font-size:20px;color: #2196f3;text-align: left;font-weight: bold;padding: 15px 0px;">Esqueceu sua senha de acesso ao app? Nao tem problema, aqui vai uma nova:</td>
		</tr>
		
		<tr>
			<td style="background-color:#ffffff;padding-button: 15px;"></td>
		</tr>
		
		<tr>
			<td style="background-color:#f44336;font-family: arial;font-size:40px;color: #ffffff;text-align: center;font-weight: bold;padding: 20px 0px"><?php echo $novaSenha; ?></td>
		</tr>
		<tr>
			<td style="background-color:#ffffff;font-family: arial;font-size:17px;color: #2196f3;text-align: left;font-weight: bold;;padding: 15px 0px;">Agora e so digitar essa senha para entrar no app.</td>
		</tr>
		<tr>
			<td></td>
		</tr>
		<tr>
			<td style="font-family: arial;font-size:17px;color: #2196f3;text-align: justify;font-weight: bold;padding-bottom: 15px"> IMPORTANTE: Apos acessar o aplicativo, escolha uma nova senha, assim fica mais facil de lembrar no proximo acesso ;)</td>
		</tr>
		
			<td style="background-color:#f44336; width:300px;color: #ffffff;font-family: arial;font-weight: bold ;font-size: 25px;text-align:center;padding: 25px 0px">Ficamos felizes em te ajudar ;)</td>
		
		</tr>
		
		</tr>
		
			<td style="background-color:#ffffff; width:300px;color: #2196f3;font-weight: bold ;font-family: arial;font-size: 25px;text-align:center;padding: 25px 0px">Equipe City Care.</td>
		
		</tr>
		
		<tr>
			<td>
			<table  align="center" border="0" cellpadding="0" cellspacing="0" style="width:540px ">
				
				<tbody>
					<tr>
						
						<td colspan="3" style="background-color: #2196f3;padding-top: 10px;padding-bottom: 10px">

						<table border="0" cellpadding="0" cellspacing="0" style="width:600px">
							<tbody>
								<tr>
									<td style="background-color:#2196f3; width:300px;color: #ffffff;font-family: arial;font-size: 14px;padding-left: 25px"><a href="projetocitycare.com.br" style="color: #ffffff; text-decoration: none"><b>www.projetocitycare.com.br</b></a></td>
									
								</tr>
							</tbody>
						</table>
						</td>
					</tr>
				</tbody>
			</table>
			</td>
		</tr>
	</tbody>
</table>
</body>
</html>';
    $mail->isHTML(true);
    if (!$mail->send()) {

        return "Mailer Error: " . $mail->ErrorInfo;
    } else
    {
        $return = $response->withJson(1, 201);
    }
    return $return;
});
